<?php
use Swoole\Coroutine as co;
$filename = __DIR__ . "/1.txt";
co::create(function () use ($filename)
{
    $r =  co::readFile($filename);
    var_dump($r);
});