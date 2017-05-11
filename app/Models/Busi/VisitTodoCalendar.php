<?php

namespace App\Models\Busi;

use App\Events\VisitTodoStatusChangedEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * 巡访项目日历明细
 * Class VisitTodoCalendar
 * @package App\Models\Busi
 * @author xrs
 * @SWG\Model(id="VisitTodoCalendar")
 * @SWG\Property(name="fstore_calendar_id", type="integer", description="线路门店巡防日历id")
 * @SWG\Property(name="ftodo_id", type="integer", description="门店巡访项目id")
 * @SWG\Property(name="fparent_id", type="integer", description="父级id")
 * @SWG\Property(name="fdate", type="string", description="日期")
 * @SWG\Property(name="flongitude", type="string", description="百度地图经度")
 * @SWG\Property(name="flatitude", type="string", description="百度地图纬度")
 * @SWG\Property(name="fstatus", type="integer", description="巡访状态（1-未开始， 2-进行中， 3-已完成）")
 * @SWG\Property(name="femp_id", type="integer", description="员工id")
 * @SWG\Property(name="fcreate_date", type="string", description="创建时间")
 * @SWG\Property(name="fcreator_id", type="integer", description="创建人")
 * @SWG\Property(name="fmodify_date", type="string", description="修改时间")
 * @SWG\Property(name="fmodify_id", type="integer", description="修改人")
 * @SWG\Property(name="fdocument_status", type="string", description="审核状态")
 * @SWG\Property(name="forg_id", type="integer", description="组织id")
 * @SWG\Property(name="id", type="integer", description="")
 */
class VisitTodoCalendar extends BaseModel
{
    //
    protected $table = 'visit_todo_calendar';
	protected $with = ['todo'];

    public static function boot()
    {
	    parent::boot(); // TODO: Change the autogenerated stub

	    static::updated(function ($model){
		    event(new VisitTodoStatusChangedEvent($model));

		    Rollcall::createOrUpdate([
			    'femp_id' => $model->femp_id,
			    'faddress' => $model->store_calendar->store->faddress,
			    'flongitude' => $model->flongitude,
			    'flatitude' => $model->flatitude,
		    ]);

	    });
    }

    public function todo(){
    	return $this->belongsTo(VisitStoreTodo::class, 'ftodo_id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'femp_id');
    }

	public function store_calendar(){
		return $this->belongsTo(VisitStoreCalendar::class, 'fstore_calendar_id');
	}

    public function status(){
        switch ($this->fstatus){
            case 1:
                return '未开始';
            case 2:
                return '进行中';
            case 3:
                return '已完成';
            default:
                return '系统异常';
        }
    }

}
