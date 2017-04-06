<?php

namespace App\Http\Controllers\Customer;

use App\Models\Busi\Channel;
use App\Models\Busi\Customer;
use App\Models\Busi\Department;
use App\Models\Busi\Employee;
use App\Models\Busi\VisitLineStore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Busi\Store;
use App\Models\Busi\VisitLine;
use App\Models\City;
use App\Models\Busi\Resources;
use Image;
use Illuminate\Http\Response;
use DB;
use Auth;
use SysConfigRepo;

class StoreController extends BaseController
{

    //
    public function newEntity(array $attributes = [])
    {
        // TODO: Implement newEntity() method.
        return new Store($attributes);
    }

    public function index()
    {
        $citys = City::query()->where('LevelType', 1)->get();
        $channels = Channel::all();
        $cus = Customer::all();
        $lines = VisitLine::all();
        return view('customer.store.index', compact('citys', 'channels', 'cus','lines'));
    }

    public function entityQuery()
    {
	    $customer = Auth::user()->reference;
	    //return $customer->stores(); //parent::entityQuery(); // TODO: Change the autogenerated stub
	    return $customer->stores()->join('bd_employees', 'st_stores.femp_id', '=', 'bd_employees.id');
    }

	/**
	 * @param Request $request
	 * @param array $searchCols
	 * @param array $with
	 * @param null $conditionCall
	 * @param bool $all_columns
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function pagination(Request $request, $searchCols = [], $with = [], $conditionCall = null, $all_columns = false)
    {
        $searchCols = ['fnumber', 'ffullname', 'fshortname', 'faddress', 'fcontracts'];
        return parent::pagination($request, $searchCols, ['employee'], $conditionCall, true);
    }

    //门店路线规划 预分配门店查询
    public function readyAllotStoreQuery($data,$query)
    {
        if (!empty($data['femp_id'])){
            $query->where('femp_id', $data['femp_id']);

            //预分配门店列表 过滤掉该线路中已存在的门店
            $exist_ids = VisitLineStore::query()->where('fline_id', $data['fline_id'])->pluck('fstore_id')->toArray();
            $query->whereNotIn('id', $exist_ids);
        }

        if (!empty($data['fname'])) {
            $query->where('ffullname', 'like', '%' . $data['fname'] . '%')->get();
        }
        if (!empty($data['faddress'])) {
            $query->where('faddress', 'like', '%' . $data['faddress'] . '%')->get();
        }

        if (!empty($data['fnumber'])) {
            $line_ids = VisitLine::query()->where('fnumber', 'like', '%' . $data['fnumber'] . '%')->pluck('id')->toArray();
            $vls_ids = VisitLineStore::query()->where('femp_id', $data['femp_id'])->whereIn('fline_id', $line_ids)->pluck('id')->toArray();

            $query->whereIn('id', $vls_ids);
        }
        if (!empty($data['is_allot'])) {
            $ids = VisitLineStore::query()->where('femp_id', $data['femp_id'])->pluck('fstore_id')->toArray();

            if ($data['is_allot'] == 1) {
                $query->whereIn('id', $ids);
            } else if ($data['is_allot'] == 2) {
                $query->whereNotIn('id', $ids);
            }
        }

    }

    //自定义查询
    public function diyquery(Request $request)
    {
        $data = $request->all();
        $query = Store::query();
        //预分配门店列表 过滤掉该线路中已存在的门店
        $ids1 = VisitLineStore::query()->where('fline_id', $data['fline_id'])->pluck('fstore_id')->toArray();

        $query->whereNotIn('id', $ids1);

        if (!empty($data['fprovince'])) {
            $query->where('fprovince', $data['fprovince']);
        }
        if (!empty($data['fcity'])) {
            $query->where('fcity', $data['fcity']);
        }
        if (!empty($data['fcountry'])) {
            $query->where('fcountry', $data['fcountry']);
        }
        if (!empty($data['femp_id'])) {
            $query->where('femp_id', $data['femp_id']);
        }

        return response()->json($query->get());
    }


    //门店添加
    public function createStore(Request $request)
    {
        $result = $this->saveData($request->all(), 'create');

        return response()->json($result);
    }

    //门店编辑
    public function editStore(Request $request)
    {
        $result = $this->saveData($request->all(), 'edit');

        return response()->json($result);
    }

    //数据保存
    public function saveData($data, $action)
    {

        //图片保存
        if (!empty($data['storephoto'])) {
            //$file = $request->file('storephoto');
            $file = $data['storephoto'];
            //var_dump($file);
            if ($file->isValid()) {
                $path = $file->store('upload/images');
                if ($path) {
                    $res = Resources::create([
                        'name' => $file->getClientOriginalName(),
                        'ext' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                        'path' => 'app/' . $path,
                        'mimetype' => $file->getMimeType(),
                    ]);
                    $data['fphoto'] = $res->id;
                }
            }
        }

        //数据处理

//        $data['fprovince'] = City::find($data['fprovince'])->Name;
//        $data['fcity'] = City::find($data['fcity'])->Name;
//        $data['fcountry'] = City::find($data['fcountry'])->Name;

        $postalcode = City::getPostalCode($data['fprovince'], $data['fcity'], $data['fcountry']);
        if ($postalcode) {
            $fn = Store::where('fpostalcode', $postalcode)->max('fnumber');
            if ($fn) {
                $fn++;
                $data['fnumber'] = $fn;
            } else {
                $data['fnumber'] = $postalcode . sprintf('%05d', 1);
            }
            $data['fpostalcode'] = $postalcode;
        }
        unset($data['_token'], $data['storephoto']);
		$data['fcust_id'] =  Auth::user()->reference->id;
        if ($action == 'create') {
            $entity = $this->newEntity($data);
            //$entity = Entity::create($data);
            $re = $entity->save();

            //生成路线
            VisitLineStore::create([
                'fline_id' => $data['fline_id'],
                'fstore_id' => $entity->id,
                'femp_id' => $data['femp_id'],
                'fweek_day' => VisitLine::find($data['fline_id'])->fnumber,
            ]);

            if ($re) {
                return [
                    'code' => 200,
                    'result' => '添加门店成功！'
                ];
            } else {
                return [
                    'code' => 500,
                    'result' => '添加门店失败！'
                ];
            }
        } else {
            $re = Store::query()->where('id', $data['id'])->update($data);

            VisitLineStore::query()->where('fstore_id',$data['id'])->where('femp_id',$data['femp_id'])->update([
                'fline_id' => $data['fline_id'],
                'fweek_day' => VisitLine::find($data['fline_id'])->fnumber,
            ]);

            if ($re) {
                return [
                    'code' => 200,
                    'result' => '修改门店成功！'
                ];
            } else {
                return [
                    'code' => 500,
                    'result' => '修改门店失败！'
                ];
            }
        }


    }

    //获取门店信息
    public function getStore($id)
    {
        $store = Store::find($id);

        $store->image = '/customer/show-image?imageId=' . $store->fphoto;

        return response()->json([
            'code' => 200,
            'result' => 'success',
            'data' => $store
        ]);
    }

    //获取门店信息
    public function storeInfo($id)
    {
        $store = Store::find($id);

        $store->image = '/customer/show-image?imageId=' . $store->fphoto;

        return view('customer.store.info', compact('store'));
    }
}
