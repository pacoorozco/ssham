<?php

namespace SSHAM\Http\Controllers\Auth;

use Auth;
use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout']]);
    }

    /**
     * Show the application login form.
     *
     * @return Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Log the user in and redirect to the submitted page
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function postLogin(LoginRequest $request)
    {
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
            'enabled' => 1
        ], $request->has('remember'))) {
            return redirect()->intended('home');
        }

        flash()->error('These credentials do not match our records. Try again?');

        return redirect()->back()
                ->withInput($request->only('username', 'remember'));
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

}
