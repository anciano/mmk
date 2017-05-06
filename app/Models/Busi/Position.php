<?php

namespace App\Models\Busi;

use App\Events\FlagChangedEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * 职位信息
 * Class Position
 * @package App\Models\Busi
 * @author xrs
 * @SWG\Model(id="Position")
 * @SWG\Property(name="fauditor_id", type="integer", description="fauditor_id")
 * @SWG\Property(name="faudit_date", type="string", description="faudit_date")
 * @SWG\Property(name="fcreate_date", type="string", description="fcreate_date")
 * @SWG\Property(name="fcreator_id", type="integer", description="fcreator_id")
 * @SWG\Property(name="fdept_id", type="integer", description="所属部门Id")
 * @SWG\Property(name="fdocument_status", type="integer", description="fdocument_status")
 * @SWG\Property(name="fforbidder_id", type="integer", description="fforbidder_id")
 * @SWG\Property(name="fforbid_date", type="string", description="fforbid_date")
 * @SWG\Property(name="fforbid_status", type="integer", description="fforbid_status")
 * @SWG\Property(name="fis_main", type="integer", description="是否负责人岗位")
 * @SWG\Property(name="fmodify_date", type="string", description="fmodify_date")
 * @SWG\Property(name="fmodify_id", type="integer", description="fmodify_id")
 * @SWG\Property(name="fname", type="string", description="fname")
 * @SWG\Property(name="fnumber", type="string", description="编码")
 * @SWG\Property(name="fparpost_id", type="integer", description="上级岗位")
 * @SWG\Property(name="fremark", type="string", description="fremark")
 * @SWG\Property(name="id", type="integer", description="id")
 */
class Position extends BaseModel
{
    //
    protected $table = 'bd_positions';

    public function senior(){
        return $this->hasOne(Position::class, 'id', 'fparpost_id');
    }

    public function department(){
        return $this->hasOne(Department::class, 'id', 'fdept_id');
    }

    public function children()
    {
        return $this->hasMany(Position::class, 'fparpost_id');
    }

	public function users(){
		return $this->belongsToMany(User::class, 'sys_user_position', 'position_id', 'user_id');
	}

	/**
	 *
	 */
	protected static function boot()
	{
		parent::boot(); // TODO: Change the autogenerated stub
		static::created(function ($model){
			$pflag = '';
			if($model->fparpost_id > 0) {
				$parent = Position::find($model->fparpost_id);
				$pflag = $parent->flag. '-' ;
			}
			$model->flag = $pflag . $model->id;
		});

		static::updating(function ($entity){
			$old = static::find($entity->id);
			if($old->fparpost_id != $entity->fparpost_id){
				$father = static::find($entity->fparpost_id);
				$entity->flag = $father->flag . '-' . $entity->id;
				event(new FlagChangedEvent($entity));
			}
		});
	}
}
