<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user= Auth::user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id . ',id',
            'password' => [
                'nullable',
                'min:8',
                Rule::requiredIf(function () use ($request) {
                    return !is_null($request->password);
                }),
                'confirmed',
            ],
            'current_password' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    return !is_null($request->password);
                }),
                function ($attribute, $value, $fail) use ($request) {
                    if (!empty($value)) {
                        if (!Hash::check($value, Auth::user()->password)) {
                            $fail('The current password does not match.');
                        }
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } 
        
        $updateUser = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ];
        if (!empty($request->password)) {
            $updateUser['password'] = Hash::make($request->password);
        }
        $user = User::where('id', $user->id)->update($updateUser);
        return redirect()->route('edit.personal.profile')->with(['success' => 'Profile updated successfully.']);
    }

}
