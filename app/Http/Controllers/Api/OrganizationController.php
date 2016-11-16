<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Busi\Organization as Entity;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', 10);
        $sort = $request->input('sort', 'id asc');
        $arr = explode(' ', $sort);
        $count = Entity::count();
        $data = Entity::orderBy($arr[0], $arr[1])->take($page)->skip(($page-1)*$pageSize)->get();
        return response(['count' => $count, 'list' => $data, 'page' => $page, 'pageSize' => $pageSize], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        unset($data['_sign']);
        $entity = Entity::create($data);
        //$re = $entity->save();
        $status = !empty($entity) ? 200 : 400;
        return response($entity, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $entity = Entity::find($id);
        return response($entity, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $entity = Entity::find($id);
        $data = $request->all();
        unset($data['_sign']);
        $re = $entity->save($data);
        $status = $re ? 200 : 401;
        return response(['success' => $re], $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $entity = Entity::find($id);
        $re = $entity->delete();
        $status = $re ? 200 : 401;
        return response(['success' => $re], $status);
    }

}