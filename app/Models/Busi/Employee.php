<?php

namespace App\Models\Busi;

use App\Models\SysConfig;
use App\Models\User;
use App\Repositories\ISysConfigRepo;
use App\Repositories\SysConfigRepo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use DB;

/**
 * Class Employee
 * @package App\Models\Busi
 * @author xrs
 * @SWG\Model(id="Employee")
 * @SWG\Property(name="faddress", type="string", description="faddress")
 * @SWG\Property(name="fauditor_id", type="string", description="fauditor_id")
 * @SWG\Property(name="faudit_date", type="string", description="faudit_date")
 * @SWG\Property(name="fcreate_date", type="string", description="fcreate_date")
 * @SWG\Property(name="fcreator_id", type="string", description="fcreator_id")
 * @SWG\Property(name="fdept_id", type="string", description="fdept_id")
 * @SWG\Property(name="fdocument_status", type="string", description="fdocument_status")
 * @SWG\Property(name="femail", type="string", description="femail")
 * @SWG\Property(name="femp_num", type="string", description="femp_num")
 * @SWG\Property(name="fforbidder_id", type="string", description="fforbidder_id")
 * @SWG\Property(name="fforbid_date", type="string", description="fforbid_date")
 * @SWG\Property(name="fforbid_status", type="string", description="fforbid_status")
 * @SWG\Property(name="fmodify_date", type="string", description="fmodify_date")
 * @SWG\Property(name="fmodify_id", type="string", description="fmodify_id")
 * @SWG\Property(name="fname", type="string", description="fname")
 * @SWG\Property(name="fnumber", type="string", description="fnumber")
 * @SWG\Property(name="fpassword", type="string", description="fpassword")
 * @SWG\Property(name="fphone", type="string", description="fphone")
 * @SWG\Property(name="fphoto", type="string", description="fphoto")
 * @SWG\Property(name="fpost_id", type="string", description="fpost_id")
 * @SWG\Property(name="fremark", type="string", description="fremark")
 * @SWG\Property(name="id", type="string", description="id")
 * @SWG\Property(name="login_time", type="string", description="登陆次数")
 * @SWG\Property(name="device", type="string", description="设备号")
 * @SWG\Property(name="forg_id", type="string", description="组织id")
 * @SWG\Property(name="fstart_date", type="string", description="入职日期")
 */
class Employee extends BaseModel
{
    //
    protected $table = 'bd_employees';
    

    public $validateRules=['fname' => 'required', 'fphone' => 'required'];

	protected static function boot()
	{
		parent::boot(); // TODO: Change the autogenerated stub
		static::creating(function ($model){
			if(empty($model->fpassword)){
				$model->fpassword = md5('888888');
			}
		});

		static::created(function ($employee){
			$employee->user()->create([
				'name' => $employee->fphone,
				'password' => $employee->fpassword,
				'login_time' => $employee->login_time,
				'status' => 1
			]);
		});
	}

	public function customer(){
		return $this->customers()->wherePivot('fdefault', 1);
	}

	public function customers(){
		return $this->belongsToMany(Customer::class, 'bd_employee_customers', 'femp_id', 'fcust_id');
	}

    public function organization(){
        return $this->hasOne(Organization::class, 'id', 'forg_id');
    }

    public function department(){
        return $this->hasOne(Department::class, 'id', 'fdept_id');
    }

    public function position(){
        return $this->hasOne(Position::class, 'id', 'fpost_id');
    }

    public function attendance_statistics(){
        return $this->hasMany(AttendanceStatistic::class, 'femp_id')->select('attendance_statistics.*');;
    }

	public function received_messages(){
		return $this->morphMany(Message::class, 'to');
	}

	public function send_messages(){
		return $this->morphMany(Message::class, 'from');
	}

    public function getSenior(){
        $position = $this->position;
        //$psenior = $this->position->senior;
        if(empty($position->senior)){
           return [];
        }
        return static::where('fpost_id', $position->senior->id)->first();
    }

    public function visit_line_stores(){
        return $this->hasMany(VisitLineStore::class, 'femp_id', 'id');
    }

	/**
	 * 获取下属(包括自己在内)
	 * @return array
	 */
    public function getSubordinates(){
	    $subs = [];
	    if(!empty($this->position)) {
		    $flag = $this->position->flag;
		    $sql = "select e.* from bd_employees e, bd_positions p where e.fpost_id = p.id and p.flag like '{$flag}%'";
		    $subs = DB::select($sql);
	    }
	    return $subs;
    }

	/**
	 * 是否数据隔离限制
	 * @return bool
	 */
    public function isDataIsolate(){
	    $ids = app(ISysConfigRepo::class)->noDataIsolateEmployees();
    	return !str_contains($ids, $this->id);
    }

	public function user(){
		return $this->morphOne(User::class, 'reference');
	}

}
