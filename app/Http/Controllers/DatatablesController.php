<?php

namespace App\Http\Controllers;

use App\Models\Busi\Department;
use App\Models\Busi\Employee;
use App\Services\LogSvr;
use Illuminate\Http\Request;

abstract class DatatablesController extends Controller
{
    //
    //
    public abstract function newEntity(array $attributes = []);

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param array $extraFields
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $extraFields = [])
    {
        $data = $request->input('data', []);
        if (empty($data))
            return $this->fail('data is empty');
        $props = current($data);
        $fieldErrors = $this->validateFields($props);
        if (!empty($fieldErrors)) {
            return $this->fail('validate error', $fieldErrors);
        } else {
            if (!empty($extraFields)) {
                $props += $extraFields;
            }
            $entity = $this->newEntity($props);
            $entity->save();
            return $this->success($entity);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param array $extraFields
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $extraFields = [])
    {
        //
        $data = $request->input('data', []);
        if (empty($data))
            return $this->fail('data is empty');

        $props = current($data);
        $fieldErrors = $this->validateFields($props);
        if (!empty($fieldErrors)) {
            return $this->fail('validate error', $fieldErrors);
        } else {
            if (!empty($extraFields)) {
                $props += $extraFields;
            }
            $entity = $this->newEntity()->newQuery()->find($id);
            $entity->fill($props);
            $entity->save();
            return $this->success($entity);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entity = $this->newEntity()->newQuery()->find($id);
        $entity->delete();
        $entity = [];
        return $this->success($entity);
    }

    /**
     * 实体的查询
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function entityQuery()
    {
        return $this->newEntity()->newQuery();
    }

    /**
     * Datatables UI page
     * @param Request $request
     * @param array $searchCols
     * @param array $with
     * @param null $conditionCall
     * @param bool $all_columns
     * @return \Illuminate\Http\JsonResponse
     * @internal param array $columns
     */
    public function pagination(Request $request, $searchCols = [], $with = [], $conditionCall = null, $all_columns = false)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $columns = $request->input('columns', []);
        $order = $request->input('order', []);
        $search = $request->input('search', []);
        $draw = $request->input('draw', 0);
        $filter = $request->input('filter', []);
        $tree = $request->input('tree', []);

        $queryBuilder = $this->entityQuery(); //$this->newEntity()->newQuery();
        if (!empty($with)) {
            $queryBuilder->with($with);
        }
        $fields = [];
        $conditions = [];
        foreach ($columns as $column) {
            if (!$all_columns)
                $fields[] = $column['data'];
            if (!empty($column['search']['value'])) {
                $conditions[$column['data']] = $column['search']['value'];
            }
        }

        $total = $queryBuilder->count();

        if (!empty($filter)) {
            $this->filter($queryBuilder,$filter);
        }
        if (!empty($tree)){
            $this->tree($queryBuilder,$tree);
        }
        if ($conditionCall != null && is_callable($conditionCall)) {
            $conditionCall($queryBuilder);
        }
        foreach ($conditions as $col => $val) {
            $queryBuilder->where($col, $val);
        }

        //模糊查询
        if (!empty($searchCols) && !empty($search['value'])) {
            $queryBuilder->where(function ($query) use ($search, $searchCols) {
                foreach ($searchCols as $sc) {
                    $query->orWhere($sc, 'like binary', '%' . $search['value'] . '%');
                }
            });
        }
        $filterCount = $queryBuilder->count();

        foreach ($order as $o) {
            $index = $o['column'];
            $dir = $o['dir'];
            $queryBuilder->orderBy($columns[$index]['data'], $dir);
        }
        if (!empty($request->distinct)) {
            $queryBuilder->groupBy($request->distinct)->distinct();
        }
        if (!empty($fields)) {
            $queryBuilder->select($fields);
        }
        $entities = $queryBuilder->skip($start)->take($length)->get();
        //$entities = $queryBuilder->skip($start)->take($length)->get();
        //LogSvr::sql()->info($queryBuilder->toSql());
        $result = [
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filterCount,
            'data' => $entities
        ];
        return response()->json($result);
    }

    protected function validateFields($data)
    {
        $fieldErrors = [];
        $entity = $this->newEntity();
        if (isset($entity->validateRules)) {
            $validator = Validator::make($data, $entity->validateRules);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $keys = $errors->keys();
                foreach ($keys as $k) {
                    $fieldErrors[] = ['name' => $k, 'status' => $errors->first($k)];
                }
            }
        }
        return $fieldErrors;
    }

    public function success($data)
    {
        return response()->json(['data' => [$data]]);
    }

    public function fail($error, $fieldErrors = [])
    {
        return response()->json(['data' => [], 'error' => $error, 'cancelled' => 1, 'fieldErrors' => $fieldErrors]);
    }

    public function flash_success($msg)
    {
        Session::flash('success', $msg);
    }

    public function flash_alert($msg)
    {
        Session::flash('message', $msg);
    }

    public function flash_error($msg)
    {
        Session::flash('error', $msg);
    }

    /**
     * 将实体数据转换成树形（bootstrap treeview）数据
     * @param $entity
     * @param $props 属性映射集合 ['text' => 'name', 'data-id' => 'id']
     * @param bool $expanded
     * @return array
     */
    public function toBootstrapTreeViewData($entity, $props, $expanded = true)
    {
        $data = ['item' => $entity];
        if (!empty($entity)) {
            foreach ($props as $k => $val) {
                $data[$k] = $entity->{$val};
                $data['state']['expanded'] = $expanded;
            }

            if (!empty($entity->children)) {
                $nodes = [];
                foreach ($entity->children as $child) {
                    $nodes[] = $this->toBootstrapTreeViewData($child, $props, $expanded);
                }
                if (!empty($nodes))
                    $data['nodes'] = $nodes;
            }
        }
        return $data;
    }

    /**
     *
     * @param $entity
     * @param $props
     * @param $options
     * @param string $prefix
     */
    public function toSelectOption($entity, $props, &$options, $prefix = '|--')
    {
        //$options = [];
        if (!empty($entity)) {
            $data = [];
            foreach ($props as $k => $val) {
                if ($k == 'label') {
                    $data[$k] = $prefix . ' ' . $entity->{$val};
                } else {
                    $data[$k] = $entity->{$val};
                }
            }
            $options[] = $data;
            if (!empty($entity->children)) {
                foreach ($entity->children as $child) {
                    $this->toSelectOption($child, $props, $options, $prefix . '-----|--');
                }
            }
        }
        //return $options;
    }


    /*
     * 查询过滤器
     */
    public function filter($queryBuilder,$filterdata){
        foreach ($filterdata as $f){
            if (!empty($f['value'])){
                $operator = !empty($f['operator'])?$f['operator']:'=';

                if ($operator=='like')
                    $queryBuilder->where($f['name'],$operator,'%'.$f['value'].'%');
                else
                    $queryBuilder->where($f['name'],$operator,$f['value']);
            }
        }
    }

    /*
     * tree select
     */
    public function tree($queryBuilder,$treeData){
        switch ($treeData['type']){
            case 'employee-tree':{
                $emp = Employee::find($treeData['nodeid']);
                if (empty($emp)) {
                    $dept = Department::find($treeData['nodeid']);
                    $emp_ids = $dept->getAllEmployeeByDept()->pluck('id')->toArray();

                    $queryBuilder->whereIn('femp_id', $emp_ids);
                } else {
                    $queryBuilder->where('femp_id', $treeData['nodeid']);
                }
                break;
            }
            case 'department-tree':{

            }
            case 'channel-tree' : {

            }

        }
    }
}
