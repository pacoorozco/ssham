<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace SSHAM\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller {

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
     * @return View
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Log the user in and redirect to the submitted page
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
            'enabled'  => 1
        ], $request->has('remember'))
        ) {
            return redirect()->intended();
        }

        flash()->error(trans('auth.invalid_credentials'));

        return redirect()->back()
            ->withInput($request->only('username', 'remember'));
    }

    /**
     * Log the user out of the application.
     *
     * @return RedirectResponse
     */
    public function getLogout()
    {
        Auth::logout();

        flash()->success(trans('auth.logout_successfully'));

        return redirect()->route('login');
    }

}
