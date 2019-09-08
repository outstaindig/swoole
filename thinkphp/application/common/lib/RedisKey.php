<?php
namespace app\common\lib;

class RedisKey
{
    public static $pre = 'sms_';

    public static $userpre = 'user_';

    public static function smsKey($phone)
    {
        return self::$pre.$phone;
    }

    public static function userKey($phone)
    {
        return self::$userpre.$phone;
    }
}