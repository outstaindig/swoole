<?php

$client = new swoole_client(SWOOLE_SOCK_TCP);

//连接到服务器
if (!$client->connect('127.0.0.1', 9501, 0.5)) {
    die("connect failed.");
}

//php cli 常量
fwrite(STDOUT,"请输入消息：");
$msg = trim(fgets(STDIN));

//向服务器发送数据
if (!$client->send($msg)) {
    die("send failed.");
}

//从服务器接收数据
$data = $client->recv();
if (!$data) {
    die("recv failed.");
}

echo $data;
//关闭连接
if($data == 'close'){
    $client->close();
}