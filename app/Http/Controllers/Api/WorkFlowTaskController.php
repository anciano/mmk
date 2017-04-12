<?php

namespace App\Http\Controllers\Api;

use App\Services\WorkFlow\Engine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\Busi\WorkFlowTask;

class WorkFlowTaskController extends ApiController
{
	//
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new WorkFlowTask($attributes);
	}

	public function fillQueryForIndex(Request $request, Builder &$query)
	{
		$query->with(['instance.variables', 'workflow']);
		parent::fillQueryForIndex($request, $query); // TODO: Change the autogenerated stub
	}

	/**
	 * 同意，审批通过
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function agree(Request $request, $id){
		$engine = new Engine();
//		$remark = $request->input('remark','同意，审批通过');
		$variablesStr = $request->input('variables', '{}');
		$variables = json_decode($variablesStr, true);
		$engine->agree($id, $variables);
		return response(['success' => 1], 200);
	}

	/**
	 * 不同意，审批结束
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function against(Request $request, $id){
		$engine = new Engine();
//		$remark = $request->input('remark','不同意，审批结束');
		$variablesStr = $request->input('variables', '{}');
		$variables = json_decode($variablesStr, true);
		$engine->against($id, $variables);
		return response(['success' => 1], 200);
	}

}