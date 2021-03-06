<?php

namespace App\Http\Controllers\Api;

use App\Services\WorkFlowEngine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\Busi\WorkFlowLog;

class WorkFlowLogController extends ApiController
{
	//
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new WorkFlowLog($attributes);
	}

	/**
	 * 同意，审批通过
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function agree(Request $request, $id){
		$engine = new WorkFlowEngine();
		$remark = $request->input('remark','同意，审批通过');
		$formData = $request->except(['remark', '_sign']);
		$logs = $engine->agree($id, $remark, $formData);
		return response(['success' => 1], 200);
	}

	/**
	 * 不同意，审批结束
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function against(Request $request, $id){
		$engine = new WorkFlowEngine();
		$remark = $request->input('remark','不同意，审批结束');
		$engine->against($id, $remark);
		return response(['success' => 1], 200);
	}

	public function fillQueryForIndex(Request $request, Builder &$query)
	{
		$query->with(['wf_instance.data', 'work_flow']);
		parent::fillQueryForIndex($request, $query); // TODO: Change the autogenerated stub
	}

}