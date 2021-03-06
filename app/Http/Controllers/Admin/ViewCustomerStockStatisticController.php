<?php
namespace App\Http\Controllers\Admin;

use App\Models\Busi\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Busi\ViewCustomerStockStatistic;

class ViewCustomerStockStatisticController extends AdminController
{
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new ViewCustomerStockStatistic($attributes);
	}

	/**
	* Display a listing of the resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function index()
	{
		$customers = Customer::all();
		return view('admin.view-customer-stock-statistic.index',compact('customers'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function create()
	{
		return view('admin.view-customer-stock-statistic.create');
	}

	/**
	* Display the specified resource.
	*
	* @param    int  $id
	* @return  \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$entity = ViewCustomerStockStatistic::find($id);
		return view('admin.view-customer-stock-statistic.edit', ['entity' => $entity]);
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
	 * @param  array $with
	 * @param  null $conditionCall
	 * @param bool $all_columns
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function pagination(Request $request, $searchCols = [], $with=[], $conditionCall = null, $all_columns = false){
		$searchCols = ["cust_name","fbase_unit","fsale_unit","material_name","material_specification"];
		return parent::pagination($request, $searchCols);
	}

}
