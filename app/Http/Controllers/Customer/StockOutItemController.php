<?php
namespace App\Http\Controllers\Customer;

use App\Models\Busi\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Busi\StockOutItem;

class StockOutItemController extends BaseController
{
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new StockOutItem($attributes);
	}

	/**
	* Display a listing of the resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		return view('customer.stock-out-item.index');
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return  \Illuminate\Http\Response
	*/
	public function create()
	{
		return view('customer.stock-out-item.create');
	}

	/**
	* Display the specified resource.
	*
	* @param    int  $id
	* @return  \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$entity = StockOutItem::find($id);
		return view('customer.stock-out-item.edit', ['entity' => $entity]);
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

	public function store(Request $request, $extraFields = [])
    {
        $data = $request->input('data', []);
        $props = current($data);
        $material = Material::find($props['fmaterial_id']);
        $extraFields = [
            'fsale_unit' => $material->fsale_unit,
            'fbase_unit' => $material->fbase_unit,
            'fbase_qty' => $material->fratio*$props['fqty']
        ];
        return parent::store($request, $extraFields); // TODO: Change the autogenerated stub
    }

    public function update(Request $request, $id, $extraFields=[])
    {
        $data = $request->input('data', []);
        $props = current($data);
        $extraFields = [
            'fbase_qty' => Material::find($props['fmaterial_id'])->FRatio*$props['fqty']
        ];
        return parent::update($request, $id,$extraFields); // TODO: Change the autogenerated stub
    }

    /**
	* @param  Request $request
	* @param  array $searchCols
	* @param  array $with
	* @param  null $conditionCall
	* @return  \Illuminate\Http\JsonResponse
	*/
	public function pagination(Request $request, $searchCols = [], $with=[], $conditionCall = null){
		$searchCols = ["fbase_unit","fdocument_status","fsale_unit"];
        $with=['stockout','material'];
		return parent::pagination($request, $searchCols, $with);
	}

}
