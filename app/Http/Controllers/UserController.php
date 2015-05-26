<?php namespace SSHAM\Http\Controllers;

use SSHAM\Http\Requests;
use SSHAM\Http\Controllers\Controller;

use Illuminate\Http\Request;
use SSHAM\Http\Requests\UserForm;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            // Title
            $title = \Lang::get('user/title.user_management');

            /*
            $table = Datatable::table()             
            ->addColumn(
                Lang::get('admin/user/table.fullname'),
                Lang::get('admin/user/table.username'),
                Lang::get('admin/user/table.email'),
                Lang::get('admin/user/table.activated'),
                Lang::get('admin/user/table.created_at'),
                Lang::get('table.actions')
                )
            ->setUrl(route('admin.users.data'))
            ->noScript();
            */
            $table = '';

            // The list of users will be filled later using the JSON Data method
            // below - to populate the DataTables table.
            return view('user/index', compact('title', 'table'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            // Title
            $title = Lang::get('user/title.create_a_new_user');
            
            return view('user.create', compact($title));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
