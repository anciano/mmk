<?php

namespace App\Http\Controllers\Api;

use App\Events\UserLoginedEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Sms;

class UserController extends ApiController
{
	//
	public function newEntity(array $attributes = [])
	{
		// TODO: Implement newEntity() method.
		return new User($attributes);
	}

	public function fillQueryForIndex(Request $request, Builder &$query)
	{
		parent::fillQueryForIndex($request, $query); // TODO: Change the autogenerated stub
		$query->with(['reference']);
	}

	public function show($id)
	{
		//return parent::show($id); // TODO: Change the autogenerated stub
		if ($id == 0) {
			return response('{}', 404);
		} else {
			$entity = User::with(['reference'])->find($id);
			// var_dump($entity);
			return response($entity, 200);
		}
	}

	public function login(Request $request){
		$this->validate($request, [
			'phone' => 'required',
			'password' => 'required',
			'type' => 'required'
		]);
		$phone = $request->input('phone', '');
		$pwd = $request->input('password', '');
		$type = $request->input('type','customer');
		$user = User::with(['reference'])
			->where('name', $phone)
			->where('password', $pwd)
			->where('reference_type', $type)
			->where('status', 1)
			->first();
		if(!empty($user)){
			event(new UserLoginedEvent($user));
			return $this->success($user);
		}else {
			return $this->fail('用户名或者密码错误');
		}
	}

	public function changePwd(Request $request){
		$this->validate($request, [
			'phone' => 'required',
			'password' => 'required',
			'code' => 'required',
		]);
		$phone = $request->input('phone', '');
		$pwd = $request->input('password', '');
		$code = $request->input('code');

		$resp = Sms::checkVerifyCode($phone, $code);
		if($resp){
			$emp = User::where('name', $phone)->first();
			if(!empty($emp)) {
				$emp->password = $pwd;
				$emp->save();

				return $this->success($emp, '修改密码成功');
			}else{
				return $this->fail('用户不存在!');
			}
		}else{
			return $this->fail('验证码错误!');
		}
	}

}