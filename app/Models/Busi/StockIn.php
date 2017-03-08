<?php

namespace App\Models\Busi;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * model description
 * Class StockIn
 * @package  App\Models
 *
 * @author  xrs
 * @SWG\Model(id="StockIn")
 * @SWG\Property(name="fbill_no", type="string", description="订单单号(门店编码+日期)")
 * @SWG\Property(name="fcreate_date", type="string", description="创建时间")
 * @SWG\Property(name="fcreator_id", type="integer", description="创建人")
 * @SWG\Property(name="fcust_id", type="integer", description="经销商ID")
 * @SWG\Property(name="fdocument_status", type="string", description="审核状态")
 * @SWG\Property(name="fin_date", type="string", description="到货日期")
 * @SWG\Property(name="fmodify_date", type="string", description="修改时间")
 * @SWG\Property(name="fmodify_id", type="integer", description="修改人")
 * @SWG\Property(name="fsend_date", type="string", description="发货日期")
 * @SWG\Property(name="fsend_status", type="string", description="发货状态(A-未发货，B-发货中，C-已到货)")
 * @SWG\Property(name="fuser_id", type="integer", description="到货确认人id")
 * @SWG\Property(name="id", type="integer", description="")
  */
class StockIn extends BaseModel
{
	//
	protected $table = 'st_stock_ins';
	protected $guarded = ['id'];

	public function customer(){
        return $this->hasOne(Customer::class,'id','fcust_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','fuser_id');
    }
}
