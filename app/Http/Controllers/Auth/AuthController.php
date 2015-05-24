<?php namespace SSHAM\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use SSHAM\User;
use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller {

    /**
     * the model instance
     * @var User
     */
    protected $user; 
    /**
     * The Guard implementation.
     *
     * @var Authenticator
     */
    protected $auth;
 
    /**
     * Create a new authentication controller instance.
     *
     * @param  Authenticator  $auth
     * @return void
     */
    public function __construct(Guard $auth, User $user)
    {
        $this->user = $user; 
        $this->auth = $auth;
 
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
     * Handle a login request to the application.
     *
     * @param  LoginRequest  $request
     * @return Response
     */
    public function postLogin(LoginRequest $request)
    {
        if ($this->auth->attempt(['name' => $request->name, 'password' => $request->password, 'active' => 1], $request->remember))
        {
            return redirect()->intended('home');
        }
 
        return redirect('/auth/login')->withErrors([
            'email' => 'The credentials you entered did not match our records. Try again?',
        ]);
    }    

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function getLogout()
    {
        $this->auth->logout();
 
        return redirect('/');
    }

}
