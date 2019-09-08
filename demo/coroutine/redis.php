<?php
$redis = new Swoole\Coroutine\Redis();
$redis->connect('127.0.0.1', 6379);
$val = $redis->get('key');