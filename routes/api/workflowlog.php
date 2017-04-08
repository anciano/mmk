<?php
/**
* @SWG\Resource(
*  resourcePath="/work-flow-log",
*  description="WorkFlowLog"
* )
*/
Route::group(['prefix' => 'work-flow-log', 'middleware' => 'api.sign'], function () {

    /**
    * @SWG\Api(
    *     path="/api/work-flow-log",
    *     @SWG\Operation(
    *      method="GET",
    *      nickname="work-flow-log-list",
    *      summary="page list",
    *      notes="page list",
    *      type="array",
    *     items="$ref:WorkFlowLog",
    *      @SWG\Parameters(
    *          @SWG\Parameter(name="page", description="当前页", required=false, type="integer", paramType="query", defaultValue="1"),
    *          @SWG\Parameter(name="pageSize", description="页大小", required=false, type="integer", paramType="query", defaultValue="10"),
    *          @SWG\Parameter(name="sort", description="排序", required=false, type="string", paramType="query", defaultValue="id asc"),
    *          @SWG\Parameter(name="search", description="查询条件（数组的json格式, 键里面可带有比较符号，不带默认为: =）", required=false, type="string", paramType="query", defaultValue="{&quot;id >=&quot;:1}"),
    *          @SWG\Parameter(name="_sign", description="签名", required=true, type="string", paramType="query", defaultValue="****")
    *      )
    *    )
    * )
    */
    Route::get('/', ['as' => 'WorkFlowLog.index', 'uses' => 'WorkFlowLogController@index']);

    /**
    * @SWG\Api(
    *     path="/api/work-flow-log/{id}",
    *     @SWG\Operation(
    *      method="GET",
    *      nickname="work-flow-log-show",
    *      summary="信息详情",
    *      notes="信息详情",
    *      type="Attendance",
    *      @SWG\Parameters(
    *          @SWG\Parameter(name="id", description="id", required=true, type="integer", paramType="path", defaultValue="1"),
    *          @SWG\Parameter(name="_sign", description="签名", required=true, type="string", paramType="query", defaultValue="****")
    *      )
    *  )
    * )
    */
    Route::get('/{id}', ['as' => 'WorkFlowLog.show', 'uses' => 'WorkFlowLogController@show']);

    /**
    * @SWG\Api(
    *     path="/api/work-flow-log",
    *     @SWG\Operation(
    *      method="POST",
    *      nickname="work-flow-log-store",
    *      summary="新增",
    *      notes="新增",
    *      type="",
    *      @SWG\Parameters(
    *          @SWG\Parameter(name="action", description="审批动作", required=true,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="approver_id", description="审批人id（user id）", required=true,type="integer", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="created_at", description="", required=true,type="string", paramType="form", defaultValue="" ),
                    *          @SWG\Parameter(name="node_id", description="审批节点", required=true,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="remark", description="备注", required=true,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="status", description="处理状态（0-未处理，1-已经处理）", required=false,type="integer", paramType="form", defaultValue="0" ),
            *          @SWG\Parameter(name="updated_at", description="", required=true,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="work_flow_id", description="", required=false,type="integer", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="work_flow_instance_id", description="", required=false,type="integer", paramType="form", defaultValue="" ),
        *          @SWG\Parameter(name="_sign", description="签名", required=true, type="string", paramType="form", defaultValue="****")
    *      )
    *  )
    * )
    */
    Route::post('/', ['as' => 'WorkFlowLog.store', 'uses' => 'WorkFlowLogController@store']);

    /**
    * @SWG\Api(
    *     path="/api/work-flow-log/{id}",
    *     @SWG\Operation(
    *      method="PUT",
    *      nickname="work-flow-log-update",
    *      summary="更新",
    *      notes="更新",
    *      type="",
    *      @SWG\Parameters(
    *          @SWG\Parameter(name="action", description="审批动作", required=false,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="approver_id", description="审批人id（user id）", required=false,type="integer", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="created_at", description="", required=false,type="string", paramType="form", defaultValue="" ),
                    *          @SWG\Parameter(name="node_id", description="审批节点", required=false,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="remark", description="备注", required=false,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="status", description="处理状态（0-未处理，1-已经处理）", required=false,type="integer", paramType="form", defaultValue="0" ),
            *          @SWG\Parameter(name="updated_at", description="", required=false,type="string", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="work_flow_id", description="", required=false,type="integer", paramType="form", defaultValue="" ),
            *          @SWG\Parameter(name="work_flow_instance_id", description="", required=false,type="integer", paramType="form", defaultValue="" ),
        *          @SWG\Parameter(name="id", description="id", required=true,type="integer", paramType="path", defaultValue="" ),
    *          @SWG\Parameter(name="_sign", description="签名", required=true, type="string", paramType="form", defaultValue="****")
    *      )
    *  )
    * )
    */
    Route::put('/{id}', ['as' => 'WorkFlowLog.update', 'uses' => 'WorkFlowLogController@update']);

    /**
    * @SWG\Api(
    *     path="/api/work-flow-log/{id}",
    *     @SWG\Operation(
    *      method="DELETE",
    *      nickname="work-flow-log-delete",
    *      summary="删除",
    *      notes="删除",
    *      type="",
    *      @SWG\Parameters(
    *          @SWG\Parameter(name="id", description="id", required=true,type="integer", paramType="path", defaultValue="1" ),
    *          @SWG\Parameter(name="_sign", description="签名", required=true, type="string", paramType="form", defaultValue="****")
    *      )
    *  )
    * )
    */
    Route::delete('/{id}', ['as' => 'WorkFlowLog.delete', 'uses' => 'WorkFlowLogController@destroy']);

});