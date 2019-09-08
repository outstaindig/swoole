<?php
namespace app\common\lib;

class PRedis
{
    public $redis;
    private static $_instance;

    public static function getInstance()
    {
        if(empty(self::$_instance)){
           return self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct()
    {
        $this->redis = new \Redis();;
        $result = $this->redis->connect(config('redis.host'),config('redis.prod'));
        if(empty($result)){
            throw new \Exception('redis connect error');
        }
    }

    public function set($key,$value,$time=0)
    {
        if(!$key)return '';
        if(is_array($value)) $value = json_encode($value);
        if(!$time) return $this->redis->set($key,$value);
        return $this->redis->set($key,$value,$time);
    }

    public function get($key)
    {
        if(!$key)return '';
        return $this->redis->get($key);
    }

    public function sadd($key,$value)
    {
        return $this->redis->sAdd($key,$value);
    }

    public function srem($key,$value)
    {
        return $this->redis->sRem($key,$value);
    }

    public function smembers($key)
    {
        return $this->redis->sMembers($key);
    }

    public function flushAll()
    {
        return $this->redis->flushAll();
    }

    public function __call($name, $arguments)
    {
        return $this->redis->$name($arguments[0],$arguments[1]);
    }
}