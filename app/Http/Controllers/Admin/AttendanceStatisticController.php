<?php

namespace App\Http\Controllers\Admin;

use App\Services\LogSvr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Busi\AttendanceStatistic;
use App\Models\Busi\Employee;
use DB;
use Auth;
use SysConfigRepo;

class AttendanceStatisticController extends AdminController
{

    //
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new AttendanceStatistic($attributes);
	}

	public function index()
	{
		return view('admin.attendance_statistic.index');
	}

	/**
	 * @param Request $request
	 * @param array $searchCols
	 * @param array $with
	 * @param null $conditionCall
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function pagination(Request $request, $searchCols = [], $with = [], $conditionCall = null){
		$searchCols = [];
		return parent::pagination($request, $searchCols, $with, function ($queryBuilder)use($request){
			$search = $request->input('search', []);
			$empQuery = DB::table('bd_employees');//,[[$emp,'fname','femp_id']]
			if (!empty($search['value'])) {
				$empQuery->where('bd_employees.fname', 'like binary', '%' . $search['value'] . '%');
			}
			$curUser = Auth::user();
			if(!$curUser->isAdmin()) {
				if (SysConfigRepo::isMgtDataIsolate()) {
					$flags = $curUser->positions->pluck('flag')->all();
					if(!empty($flags)) {
						$empQuery->join('bd_positions', 'bd_employees.fpost_id', '=', 'bd_positions.id');
						$empQuery->where(function ($empQuery) use ($flags){
							foreach ($flags as $flag){
								$empQuery->orWhere('bd_positions.flag', 'like', $flag. '%');
							}
						});
					}
				}
			}
			$entities = $empQuery->select('bd_employees.id')->get();
			LogSvr::sql()->info($empQuery->toSql());
		    $ids = $entities->pluck('id')->all(); //array_map(function ($item){	return $item->id;}, $entities);
			LogSvr::sql()->info(json_encode($ids));
			if(!empty($ids))
			{
				$queryBuilder->whereIn('femp_id', $ids);
			}
		});
	}

	public function attendanceInfo($id){
        $att = AttendanceStatistic::find($id);

        if (!empty($att->beginAttendance)){
            $att->begin_img = '/admin/show-image?imageId='.$att->beginAttendance->fphoto;
        }

        if (!empty($att->completeAttendance)){
            $att->complete_img = '/admin/show-image?imageId='.$att->completeAttendance->fphoto;
        }

        return view('admin.attendance_statistic.info',compact('att'));
    }

}
