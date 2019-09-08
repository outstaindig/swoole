<?php
namespace app\index\controller;

use app\common\lib\PRedis;
use app\common\lib\RedisKey;

class Send
{
    public function index()
    {
        $phoneNum = request()->get('phone_num',0,'intval');

        if(empty($phoneNum)){
            return show(0,'error');
        }
        $code = rand(1000,9999);

        $dataTask = [
            'method'=>'sendCode',
            'data'=>[
                'phoneNum'=>$phoneNum,
                'code'=>$code
            ]
        ];

        $_POST['ws_server']->task($dataTask);
        //PRedis::getInstance()->set(RedisKey::smsKey($phoneNum),$code);
        return show(1,'success');
    }
}
