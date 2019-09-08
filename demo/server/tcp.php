<?php
//创建Server对象，监听 127.0.0.1:9501端口
$serv = new Swoole\Server("127.0.0.1", 9501);

$serv->set([
    'worker_num' => 4,    //worker process num
    'max_request' => 50,
]);

//监听连接进入事件
/**
 * $fd 客服端链接标示
 * $reactor_id 线程id
 */
$serv->on('Connect', function ($serv, $fd,$reactor_id) {
    echo "Client:{$reactor_id} client:$fd.Connect\n";
});

//监听数据接收事件
$serv->on('Receive', function ($serv, $fd, $reactor_id, $data) {
    $serv->send($fd, "Server: {$reactor_id} client:$fd date:".$data);
});

//监听连接关闭事件
$serv->on('Close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();