<?php

namespace App\Models\Busi;

use App\Events\ModelCreatedEvent;
use App\Events\ModelUpdatedEvent;
use Illuminate\Database\Eloquent\Model;


class VisitLineStore extends BaseModel
{
    //
	 protected $table = 'visit_line_store';

	 protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::created(function ($model){
            event(new ModelCreatedEvent($model));
            $store = Store::find($model->fstore_id);
            $store->fline_id = $model->fline_id;
            $store->femp_id = $model->femp_id;
            $store->save();
        });
        static::updated(function ($model){
            event(new ModelUpdatedEvent($model));
            $store = Store::find($model->fstore_id);
            $store->fline_id = $model->fline_id;
            $store->femp_id = $model->femp_id;
            $store->save();
        });
    }

	 public function employee(){
	 	return $this->hasOne(Employee::class, 'id', 'femp_id');
	 }
	 
	 public function line(){
	 	return $this->hasOne(VisitLine::class, 'id', 'fline_id');
	 }

	 public function store(){
         return $this->hasOne(Store::class, 'id', 'fstore_id');
     }

}
