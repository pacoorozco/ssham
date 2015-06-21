<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use SSHAM\Http\Requests\UserRequest;
use SSHAM\User;
use yajra\Datatables\Datatables;
use Symfony\Component\Process\Process;

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
            \Illuminate\Support\Facades\Storage::put('hola323.pub', $keypair['publickey']);
            $fingerprint = substr(chunk_split(md5($keypair['publickey']), 2, ':'), 0, -1);
            dd($fingerprint);

//
//
//            $private_key = str_random();
//            $public_key = $private_key . '.pub';
//
//
//            $command = '/usr/bin/ssh-keygen -q -C ' . $request->name .' -f ' . storage_path() .'/app/' . $private_key . ' -t rsa -P ' . $request->name;
//            $process = new Process($command);
//            $process->run();
//
//            // executes after the command finishes
//            if (!$process->isSuccessful()) {
//                throw new \RuntimeException($process->getErrorOutput());
//            }
//
//            $command = '/usr/bin/ssh-keygen -l -f ' . storage_path() .'/app/' . $public_key . ' | cut -d" " -f2';
//            $process = new Process($command);
//            $process->run();
//
//            // executes after the command finishes
//            if (!$process->isSuccessful()) {
//                throw new \RuntimeException($process->getErrorOutput());
//            }
//
//            $user->fingerprint = $process->getOutput();
//
//            $contents = \Illuminate\Support\Facades\Storage::get($public_key);
//            $user->publickey = $contents;
        }
        $user->save();

        flash()->success(\Lang::get('user/messages.create.success'));
        
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
