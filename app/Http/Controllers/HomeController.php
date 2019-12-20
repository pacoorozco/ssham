<?php

namespace App\Http\Controllers;

use App\Host;
use App\Rule;
use App\User;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user_count = User::all()->count();
        $host_count = Host::all()->count();
        $rule_count = Rule::all()->count();

        return view('home')
            ->with(compact('user_count'))
            ->with(compact('host_count'))
            ->with(compact('rule_count'));
    }
}
