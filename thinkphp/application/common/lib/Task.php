<?php
namespace app\common\lib;

class Task
{
    public function sendCode($data,$serv)
    {
        PRedis::getInstance()->set(RedisKey::smsKey($data['phoneNum']),$data['code']);
        return true;
    }

    public function pushLive($data,$serv)
    {
        //获取链接用户
        $client = PRedis::getInstance()->smembers(config('redis.live_redis_key'));
        //赛况信息入库
        foreach ($client as $fd){
            $serv->push($fd,json_encode($data));
        }
    }
}