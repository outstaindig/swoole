<?php
namespace app\index\controller;


class Chart
{
    public function index()
    {
        if(empty($_POST['game_id'])){
            show(config('code.error'),'error');
        }
        if(empty($_POST['content'])){
            show(config('code.error'),'error');
        }

        $data = [
            'user'.rand(),
            'content'=>$_POST['content']
        ];
        //echo "当前服务器共有 ".count($_POST['ws_server']->ports[1]->connections). " 个连接\n";
        foreach($_POST['ws_server']->ports[1]->connections as $fd) {
            $_POST['ws_server']->send($fd, json_encode($data));
        }

        return show(1,'success',$data);
    }


}
