<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 2016-11-16
 * Time: 15:07
 */

namespace App\Services;
use  GuzzleHttp\Client;

class KingdeeSyncData extends SyncData
{
    protected $loginUrl = 'http://117.28.234.39:81/k3cloud/Kingdee.BOS.WebApi.ServicesStub.AuthService.ValidateUser.common.kdsvc';
    protected $dataUrl = 'http://117.28.234.39:81/k3cloud/CYD.ApiService.ServicesStub.CustomBusinessService.Syncdb.common.kdsvc';

    public function login($cookie_jar = null){
        $data = '{ "parameters": "[\"5826e02fe123a9\",\"Administrator\",\"888888\",2052]" }';
        return $this->post($this->loginUrl, $data, 1, $cookie_jar);
    }

    public function sendData($table, $op, $data, $cookie_jar = null){
        $arr = ['parameters' => [$table, $op, json_encode($data)]];
        //var_dump(json_encode($arr));
        return $this->post($this->dataUrl, $arr, 0, $cookie_jar);
    }

    public function sync($table, $op, $data){
        $cookie_jar = tempnam('./tmp','CloudSession');
        $re = $this->login($cookie_jar);

        $result = $this->sendData($table, $op, $data, $cookie_jar);
        return json_decode($result, true);
    }

    public static function add($table, $data){
        $sync = new static();
        return $sync->sync($table, 0, $data);
    }

    public static function update($table, $data){
        $sync = new static();
        return $sync->sync($table, 1, $data);
    }

    public static function delete($table, $data){
        $sync = new static();
        return $sync->sync($table, 2, $data);
    }

}