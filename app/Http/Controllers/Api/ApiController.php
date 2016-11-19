<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

 abstract class  ApiController extends Controller
{
    //
    public function __construct()
    {
    }

    public abstract function newEntity(array $attributes = []);

    public function fillQueryForIndex(Request $request, Builder &$query){
        $search = $request->input('search', '{}');
        $conditions = json_decode($search, true);
        if(!empty($conditions)) {
            //dump($conditions);
            foreach ($conditions as $k => $v) {
                $tmp = explode(' ', $k);
                $query->where($tmp[0], isset($tmp[1]) ? $tmp[1] : '=', $v);
            }
        }
        //return $query;
    }

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
        $entity = $this->newEntity();
        $query = $entity->query();
        $this->fillQueryForIndex($request, $query);
        $count = $query->count();
        $data = $query->orderBy($arr[0], $arr[1])->take($page)->skip(($page-1)*$pageSize)->get();
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
        $entity = $this->newEntity($data);
        //$entity = Entity::create($data);
        $re = $entity->save();
        $status = $re ? 200 : 400;
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
        $entity =$this->newEntity()->newQuery()->find($id);
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
        $entity =$this->newEntity()->newQuery()->find($id);
        //$entity = Entity::find($id);
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
        $entity =$this->newEntity()->newQuery()->find($id);
        $re = $entity->delete();
        $status = $re ? 200 : 401;
        return response(['success' => $re], $status);
    }

}
