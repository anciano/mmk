<?php

namespace App\Http\Controllers\Api;

use App\Models\Busi\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Busi\Attendance as Entity;
use DB;

class AttendanceController extends ApiController
{
    public function newEntity(array $attributes = [])
    {
        return new Entity($attributes);
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
		$data['ftime'] = date('Y-m-d H:i:s');
		$employee = Employee::find($data['femp_id']);
		if(!empty($employee)){
			$data['fdept_id'] = $employee->fdept_id;
		}
		$entity = $this->newEntity($data);
		//$entity = Entity::create($data);
		$re = $entity->save();
		//LogSvr::Sync()->info('ModelCreated : '.json_encode($entity));
		$status = $re ? 200 : 400;
		return response($entity, $status);
	}

    public function month(Request $request){
        $empId = $request->input('emp_id', 0);
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $results = DB::select('select DISTINCT date_format(ftime, \'%Y-%m-%d\') as `day` from ms_attendances WHERE femp_id=? and date_format(ftime, \'%Y-%m\') = ?',
            [$empId, $year.'-'.$month]);
        $data = [];
        foreach ($results as $obj){
            $data[] = $obj->day;
        }
        return response(['list' => $data], 200);
    }

    public function day(Request $request){
        $empId = $request->input('emp_id', 0);
        $date = $request->input('date', date('Y-m-d'));

        $results = DB::select('select * from ms_attendances WHERE femp_id=? and date_format(ftime, \'%Y-%m-%d\') = ?',
            [$empId, $date]);
//        $results = Entity::where('femp_id', $empId)->where('date_format(ftime, \'%Y-%m-%d\')', $date)->get();

        return response(['list' => $results], 200);
    }

	public function exists(Request $request){
		$empId = $request->input('emp_id', 0);
		$type = $request->input('type', 0);
		$date = $request->input('date', date('Y-m-d'));

//		$results = DB::select('select count(1) c from ms_attendances WHERE ftype=? and femp_id=? and date_format(ftime, \'%Y-%m-%d\') = ?',
//			[$type, $empId, $date]);
//        $results = Entity::where('femp_id', $empId)->where('date_format(ftime, \'%Y-%m-%d\')', $date)->get();
		$c = Entity::where(DB::raw("date_format(ftime, '%Y-%m-%d')"), $date)
			->where('ftype', $type)
			->where('femp_id', $empId)
			->count();
		return response(['count' => $c], 200);
	}

    /**
     * 是否日完成
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function completed(Request $request){
        $empId = $request->input('emp_id', 0);
        $date = $request->input('date', date('Y-m-d'));

        $results = DB::selectOne('select count(*) as count from ms_attendances WHERE femp_id=? and date_format(ftime, \'%Y-%m-%d\') = ? and ftype=1',
            [$empId, $date]);
//        $results = Entity::where('femp_id', $empId)->where('date_format(ftime, \'%Y-%m-%d\')', $date)->get();

        return response(['completed' => $results->count], 200);
    }

}
