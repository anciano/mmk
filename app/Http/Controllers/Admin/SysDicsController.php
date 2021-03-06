<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SysDics;

class SysDicsController extends AdminController
{

    //
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new SysDics($attributes);
	}

	public function index()
	{
		return view('admin.sys_dics.index');
	}

	/**
	 * @param Request $request
	 * @param array $searchCols
	 * @param array $with
	 * @param null $conditionCall
	 * @param bool $all_columns
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function pagination(Request $request, $searchCols = [], $with = [], $conditionCall = null, $all_columns = false){
		$searchCols = ['type', 'value'];
		return parent::pagination($request, $searchCols);
	}

}
