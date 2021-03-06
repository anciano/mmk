<?php

namespace App\Http\Controllers\Admin;

use App\Models\Busi\Department;
use App\Services\ExcelService;
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
	 * @param bool $all_columns
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function pagination(Request $request, $searchCols = [], $with = [], $conditionCall = null, $all_columns = false){
		$searchCols = ['bd_employees.fname'];

		return parent::pagination($request, $searchCols, $with, function ($queryBuilder)use($request,$all_columns){
			$curUser = Auth::user();

			if(!$curUser->isAdmin()) {
				if (SysConfigRepo::isMgtDataIsolate()) {
					$flags = $curUser->positions->pluck('flag')->all();
					if(!empty($flags)) {
						$queryBuilder->join('bd_positions', 'bd_employees.fpost_id', '=', 'bd_positions.id');
						$queryBuilder->where(function ($queryBuilder) use ($flags){
							foreach ($flags as $flag){
								$queryBuilder->orWhere('bd_positions.flag', 'like', $flag. '%');
							}
						});
					}
				}
			}
		}, true);
	}

	public function entityQuery()
	{
		//return parent::entityQuery(); // TODO: Change the autogenerated stub
		$queryBuilder = DB::table('attendance_statistics')
			->select([
				'attendance_statistics.id',
				'bd_employees.fname',
				'attendance_statistics.fday',
				'attendance_statistics.fbegin',
				'bg.faddress as bg_address',
				'bg.flongitude as bg_flongitude',
				'bg.flatitude as bg_flatitude',
				'attendance_statistics.fcomplete',
				'complete.faddress as complete_address',
				'complete.flongitude as complete_flongitude',
				'complete.flatitude as complete_flatitude',
				'attendance_statistics.fstatus'
			])
			->join('bd_employees', 'bd_employees.id', '=', 'attendance_statistics.femp_id')
			->leftJoin('ms_attendances as bg', 'bg.id', '=', 'attendance_statistics.fbegin_id')
			->leftJoin('ms_attendances as complete', 'complete.id', '=', 'attendance_statistics.fcomplete_id');

		return $queryBuilder;
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

    public function treeFilter($queryBuilder, $data, $tableAlias = false)
    {
        return parent::treeFilter($queryBuilder, $data, true); // TODO: Change the autogenerated stub
    }

    public function export($datas)
    {
        $data = [['员工姓名', '日期', '签到时间', '签到地点', '签退时间', '签退地点', '签到状态']];
        foreach ($datas as $d) {
            $status = "无";
            switch ($d->fstatus){
                case 0:
                    $status= '未完成';
                    break;
                case 1:
                    $status= '正常';
                    break;
                case 2:
                    $status= '异常';
                    break;
                case 3:
                    $status= '请假';
                    break;
            }

            $data[] = [
                $d->fname,
                date('Y-m-d',strtotime($d->fday)),
                $d->fbegin,
                $d->bg_address,
                $d->fcomplete,
                $d->complete_address,
                $status,
            ];
        }

        $excel = new ExcelService();
        $excel->export($data, date('Ymd') . '_考勤信息');
    }
}
