<?php
namespace app\index\controller;

use app\common\lib\PRedis;
use app\common\lib\RedisKey;

class Login
{
    public function index()
    {
        $phoneNum = intval($_GET['phone_num']);
        $code = intval($_GET['code']);

        if(empty($phoneNum) || empty($code)){
            return show(config('code.error'),'error'.$code);
        }

        try{
            $rCode = PRedis::getInstance()->get(RedisKey::smsKey($phoneNum));
        }catch (\Exception $e){
            echo $e->getMessage();
            return '';
        }

        if($rCode == $code){
            $data = ['user'=>$phoneNum,'key'=>md5(RedisKey::userKey($phoneNum)),'time'=>time(),'islogin'=>true];
            PRedis::getInstance()->set(RedisKey::userKey($phoneNum),$data);
            $_COOKIE[RedisKey::userKey($phoneNum)] = json_encode($data);
            return show(config('code.success'),'success',$data);
        }

        return show(config('code.error'),'login fail');
    }
}
