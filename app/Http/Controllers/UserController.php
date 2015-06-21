<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\UserRequest;
use SSHAM\User;
use yajra\Datatables\Datatables;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     * 
     * @return void
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
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $user = new User($request->all());

        if (empty($request->publickey)) {

            $rsa = new \Crypt_RSA();
            $keypair = $rsa->createKey();

            $user->publickey = $keypair['publickey'];

            $private_key = str_random();
            Storage::disk('local')->put($private_key, $keypair['privatekey']);

            $fileentry = new \SSHAM\Fileentry();
            $fileentry->filename = $private_key;
            $fileentry->mime = 'application/octet-stream';
            $fileentry->original_filename = $user->name . '.rsa';
            $fileentry->save();
        }
        $user->save();

        flash()->overlay(
            'Download private key ' . link_to(route('file.download', $private_key), 'here'),
            \Lang::get('user/messages.create.success')
            );
        
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User $user
     * @param UserRequest $request
     * @return Response
     */
    public function update(User $user, UserRequest $request)
    {
        $user->update($request->all());

        flash()->success(\Lang::get('user/messages.edit.success'));

        return redirect()->route('users.edit', [$user->id]);
    }

    /**
     * Remove user.
     *
     * @param User $user
     * @return Response
     */
    public function delete(User $user)
    {
        return view('user.delete', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        flash()->success(\Lang::get('user/messages.delete.success'));

        return redirect()->route('users.index');
    }

    public function data(Datatables $datatable)
    {
        $users = User::select(array(
                'id', 'name', 'fingerprint', 'active'
        ));

        return $datatable->usingEloquent($users)
                ->editColumn('active', function($model) {
                    return ($model->active) ? '<span class="label label-sm label-success">Activo</span>' : '<span class="label label-sm label-danger">Inactivo</span>';
                })
                ->addColumn('actions', function($model) {
                    return view('partials.actions_dd', array(
                            'model' => 'users',
                            'id' => $model->id
                        ))->render();
                })
                ->removeColumn('id')
                ->make(true);
    }

}
