<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/ip/dhcp-server';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'host-ip' => 'required|string|ipv4',
            $this->username() => 'required|string',
            'password' => 'nullable'
        ]);
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'host-ip');
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt($this->credentials($request), false);
    }

}
