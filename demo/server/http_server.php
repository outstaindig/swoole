<?php
$http = new Swoole\Http\Server("127.0.0.1", 8801);

$http->set([
    'document_root' => '/Users/fujie/object/swoole/demo/data', // v4.4.0以下版本, 此处必须为绝对路径
    'enable_static_handler' => true,
]);

$http->on('request', function ($request, $response) {
    //$m = $request->get();
    //$response->setcookie('name','value',time()+100);
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});

$http->start();