<?php

namespace App\Models\Busi;

use App\Services\WorkFlow\Engine;
use App\Services\WorkFlow\Instance;
use App\Services\WorkFlow\Sponsor;
use Illuminate\Database\Eloquent\Model;

/**
 * model description
 * Class StoreChange
 * @package  App\Models
 *
 * @author  xrs
 * @SWG\Model(id="StoreChange")
   */
class StoreChange extends BaseModel
{
	//
	protected $table = 'st_store_changes';
	protected $guarded = ['id'];

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'fcust_id');
	}

	/**
	 * 从门店数据新增变更数据
	 * @param array $store
	 * @param int $type | 0-新增，1-修改，2-删除
	 */
	public static function addFromStore(array $store, $type = 0)
	{
		$props = $store;
		$props['fstore_id'] = $store['id'];
		$props['type'] = $type;
		unset($props['id']);
		unset($props['customer']);
		return StoreChange::create($props);
	}


	protected static function boot()
	{
		Engine::boot();
		parent::boot(); // TODO: Change the autogenerated stub
		static::created(function ($model) {
			$sponsor = new Sponsor($model->type == 0 ? $model->fcreator_id : $model->fmodify_id );
			$engine = new Engine();
			$engine->startInstance('store-change', $sponsor,
				[
					'store_change_list' => $model,
					'creator' => $sponsor->nick_name,
					'action' => $model->type == 0 ? '新增' : $model->type == 1 ? '修改' : '删除',
					'store_name' => $model->ffullname,
					'store_address' => $model->faddress,
					'created' => $model->fcreate_date
				]);
		});

		Instance::variablesSaved(function (Instance $instance) {
			if ($instance->workflow->name == 'store_change') {
				if (array_key_exists('store_change_list', $instance->variables)) {
					//$instance->variables['store_change_list']->save();
				}
			}
		});


	}
}
