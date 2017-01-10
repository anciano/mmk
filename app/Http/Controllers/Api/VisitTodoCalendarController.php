<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Busi\VisitTodoCalendar;
use App\Models\Busi\VisitStoreTodo;
use DB;

class VisitTodoCalendarController extends ApiController
{
    //
    public function newEntity(array $attributes = [])
    {
        // TODO: Implement newEntity() method.
        return new VisitTodoCalendar($attributes);
    }

//	/**
//	 * Store a newly created resource in storage.
//	 *
//	 * @param  \Illuminate\Http\Request  $request
//	 * @return \Illuminate\Http\Response
//	 */
//	public function store(Request $request)
//	{
//		//
//		$data = $request->all();
//		unset($data['_sign']);
//
//		$todoCalendar = VisitTodoCalendar::where('femp_id', $data['femp_id'])
//			->where('fstore_calendar_id', $data['fstore_calendar_id'])
//			->where('fdate', $data['fdate'])
//			->where('ftodo_id', $data['ftodo_id'])
//			->first();
//
//		if(empty($todoCalendar)){
//			$entity = $this->newEntity($data);
//			$re = $entity->save();
//		}else{
//			$todoCalendar->fstatus =  $data['fstatus'];
//			$re = $todoCalendar->save();
//		}
//
//		//LogSvr::Sync()->info('ModelCreated : '.json_encode($entity));
//		$status = $re ? 200 : 400;
//		return response($entity, $status);
//	}
//
//	public function getStatus(Request $request){
//		$fdate = $request->input('fdate');
//		$femp_id = $request->input('femp_id');
//		$fstore_calendar_id = $request->input('fstore_calendar_id');
//		$ftodo_id = $request->input('ftodo_id');
//
//
//	}

	public function getStatus(Request $request){
		$fdate = $request->input('fdate');
		$femp_id = $request->input('femp_id');
		$fstore_calendar_id = $request->input('fstore_calendar_id');
		$ftodo_id = $request->input('ftodo_id');


	}

}
