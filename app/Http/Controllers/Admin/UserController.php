<?php
namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\User;

class UserController extends AdminController
{

	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new User($attributes);
	}

	/**
	* Display a listing of the resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		return view('admin.user.index');
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function create()
	{
		return view('admin.user.create');
	}

	/**
	* Display the specified resource.
	*
	* @param    int  $id
	* @return  \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$entity = User::find($id);
		return view('admin.user.edit', ['entity' => $entity]);
	}

	/**
	* Display the specified resource.
	*
	* @param    int  $id
	* @return  \Illuminate\Http\Response
	*/
	public function show($id)
	{
		//
	}

	/**
	* @param  Request $request
	* @param  array $searchCols
	* @return  \Illuminate\Http\JsonResponse
	*/
	public function pagination(Request $request, $searchCols = [], $with = []){
		$searchCols = ["email","name","password","remember_token"];
		return parent::pagination($request, $searchCols);
	}

	public function setRole(Request $request, $id){
		$roles = Role::all();
		$user = User::find($id);
		if($request->isMethod('post')){
			$roleIds = $request->input('roles',[]);
			$user->roles()->sync($roleIds);
		}
		return view('admin.user.role', ['roles' => $roles, 'user' => $user]);
	}


}
