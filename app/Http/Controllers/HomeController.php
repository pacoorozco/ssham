<?php namespace SSHAM\Http\Controllers;

use Illuminate\View\View;
use SSHAM\Host;

class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return View
     */
    public function index()
    {
        $synced = (Host::where('synced', 1)->count() + 1);
        $unsynced = Host::where('synced', 0)->count();

        return view('home', compact('synced', 'unsynced'));
    }

}
