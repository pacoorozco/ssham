<?php

namespace SSHAM\Http\Controllers\Auth;

use Auth;
use SSHAM\User;
use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
        ]);
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

    public function postLogin(LoginRequest $request)
    {
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password, 'active' => 1], $request->has('remember'))) {
            return redirect()->intended('home');
        }

        return redirect($this->loginPath())
                ->withInput($request->only('name', 'remember'))
                ->withErrors([
                    'name' => 'These credentials do not match our records. Try again?',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect('/');
    }

}
