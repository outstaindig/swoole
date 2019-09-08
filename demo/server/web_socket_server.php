<?php
/**
 * web socket 特点
 * 1、建立在tcp之上
 * 2、性能开销小通讯高效
 * 3、客服端可以任意与服务器通讯
 * 4、协议标示符 ws、wss
 * 5、持久化网络通信协议
 */
$server = new Swoole\WebSocket\Server("127.0.0.1", 9502);

$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});

$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();