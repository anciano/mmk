<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 2016-11-11
 * Time: 11:39
 */
if(!function_exists('uuid')){
    function uuid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = ''//chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                //.chr(125);// "}"
            ;
            return strtolower($uuid);
        }
    }
}

if (! function_exists('display')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @param string $theme
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function display($view = null, $data = [], $mergeData = [] , $theme = 'default')
    {
        $template = $theme . '.' . $view;
        return view($template, $data, $mergeData);
    }
}

if(!function_exists('snake_case2')){

	function snake_case2($str){
		return snake_case($str, '-');
	}
}

if(!function_exists('api_sign')){

	function api_sign($data, $request = null){
		//$data = $request->all();
		//$_sign = $data['_sign'];
		unset($data['_sign']);
		ksort($data);
		$arr = [];
		foreach($data as $k => $v) {
			if($request != null && $request->hasFile($k))
				continue;
			$arr[] = $k .'=' . $v;
		}
		$str =  implode('&', $arr). env('APP_KEY');
		$sign = md5($str);
		return $sign;
	}

}
