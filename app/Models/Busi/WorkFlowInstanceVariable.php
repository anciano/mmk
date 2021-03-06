<?php

namespace App\Models\Busi;

use Illuminate\Database\Eloquent\Model;

/**
 * 实例变量
 * Class WorkFlowInstanceVariable
 * @package  App\Models
 *
 * @author  xrs
 * @SWG\Model(id="WorkFlowInstanceVariable")
 * @SWG\Property(name="created_at", type="string", description="")
 * @SWG\Property(name="data_type", type="string", description="数据类型")
 * @SWG\Property(name="display_name", type="string", description="显示名(中文)")
 * @SWG\Property(name="id", type="integer", description="")
 * @SWG\Property(name="name", type="string", description="变量名（英文）")
 * @SWG\Property(name="updated_at", type="string", description="")
 * @SWG\Property(name="value", type="string", description="变量值")
 * @SWG\Property(name="work_flow_instance_id", type="integer", description="")
  */
class WorkFlowInstanceVariable extends Model
{
	//
	protected $table = 'work_flow_instance_variables';
	protected $guarded = ['id'];

	public function definition(){
		return $this->belongsTo(WorkFlowVariable::class, 'work_flow_variable_id');
	}

	public function instance(){
		return $this->belongsTo(WorkFlowInstance::class, 'work_flow_instance_id');
	}

	protected static function boot()
	{
		parent::boot(); // TODO: Change the autogenerated stub
		static::creating(function ($model){
			$model->uid = uuid();
		});
	}
}
