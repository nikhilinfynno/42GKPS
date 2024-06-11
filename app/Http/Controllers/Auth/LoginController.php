<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAuthRequest;
use App\Models\Role;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(AdminAuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Check if the authenticated user is an admin
            if (in_array(auth()->user()->roles->first()->slug,[Role::SUPER_ADMIN, Role::ADMIN])) {
                // Authentication passed, user is an admin
                return redirect()->intended('dashboard');
            } else {
                // User is not an admin, logout and redirect back with error
                Auth::logout();
                return redirect()->back()->with('error', 'Invalid email or password.');
            }
        }

        // Authentication failed
        return redirect()->back()->withErrors(['email' => 'Invalid email or password.']);
    }
 
}
