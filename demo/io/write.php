<?php
use Swoole\Coroutine as co;

$filename = __DIR__ . "/1.log";
co::create(function () use ($filename)
{
    $r =  co::writeFile($filename,"hello swoole!",FILE_APPEND);
    var_dump($r);
});
