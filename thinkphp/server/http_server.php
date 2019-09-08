<?php
$http = new Swoole\Http\Server("127.0.0.1", 8801);

$http->set([
    'document_root' => '/Users/fujie/object/swoole/thinkphp/public/static', // v4.4.0以下版本, 此处必须为绝对路径
    'enable_static_handler' => true,
    'worker_num'=>5
]);

$http->on('WorkerStart', function ($serv, $worker_id){
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    //加载框架里面的文件
    require __DIR__ . '/../thinkphp/base.php';
});

$http->on('request', function ($request, $response){
    $_GET = [];
    $_POST = [];
    $_SERVER = [];

    if(isset($request->server)){
        foreach ($request->server as $k=>$v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    if(isset($request->header)){
        foreach ($request->header as $k=>$v){
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    if(isset($request->get)) {
        foreach($request->get as $k => $v) {
            $_GET[$k] = $v;
        }
    }

    if(isset($request->post)){
        foreach ($request->post as $k=>$v){
            $_POST[strtoupper($k)] = $v;
        }
    }

    ob_start();
    try{
        think\App::run()->send();
    }catch (\Exception $e){
    }
    $res = ob_get_contents();
    ob_end_clean();
    $response->end($res);
});


@$http->start();