<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\SettingsRequest;
use Torann\Registry\Facades\Registry;


class SettingsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = Registry::all();
        return view('settings.index', compact('settings'));
    }

    public function update(SettingsRequest $request)
    {
        Registry::store($request->except('_token', '_method'));

        flash()->success(trans('settings/messages.save.success'));

        return redirect()->route('settings.index');
    }

}
