<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get authenticated user details
     */
    public function edit(Request $request): JsonResponse
    {
        $user = $request->user();
        $userData = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country_code' => $user->country_code,
            'timezone' => $user->timezone,
            'phone_verified_at' => $user->phone_verified_at,
            'email_verified_at' => $user->email_verified_at,
        ];
         
        return response()->json([
            'success' => true,
            // 'user' => [
            //     ...$user->toArray(),
            //     'verified_email' => $user instanceof MustVerifyEmail && !$user->hasVerifiedEmail(),
            // ],
            'user' => $userData
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            // 'first_name' => 'required|min:3|max:100',
            // 'last_name' => 'required|min:3|max:100',
            // 'email' => 'required|string|email|max:100|unique:users,email,' . $user->id . ',id',
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
                function ($attribute, $value, $fail) use ($request, $user) {
                    if (!empty($value)) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('The current password does not match.');
                        }
                    }
                },
            ],
        ]);
        
        
        $updateUser = [];
        if (!empty($request->password)) {
            $updateUser['password'] = Hash::make($request->password);
            $user = User::where('id', $user->id)->update($updateUser);
        }
       
        
        return response()->json([
            'success' => true,
            'message' => 'Password updated success.'
        ], 201);
    }
}
