<?php
class Ws
{
    const HOST = '127.0.0.1';
    const PORT = 9502;

    public $ws;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server(self::HOST, self::PORT);
        $this->ws->set([
            'worker_num'=>2,
            'task_worker_num'=>2
        ]);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws,$request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
        if($request->fd){
            swoole_timer_tick(2000,function ($timer_id){
                echo "timer:{$timer_id}\n";
            });
        }
    }

    /**
     * 监听ws事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws,$frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $data = [
            'task'=>1,
            'fd'=>$frame->fd
        ];
        //$ws->task($data);

        swoole_timer_tick(5000,function () use($ws,$frame){
            $ws->push($frame->fd, "5s server push:".date('Y-m-d H:i:s'));
        });

        $ws->push($frame->fd, "server push:".date('Y-m-d H:i:s'));
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($serv,$taskId,$workerId,$data)
    {
        print_r($data);
        sleep(10);
        return 'on task finish';
    }

    public function OnFinish($serv,$taskId,$data)
    {
        echo "taskId: $taskId";
        echo "data : $data";
    }

    /**
     * @param $ws
     * @param $fd
     */
    public function onClose($ws,$fd)
    {
        echo "client {$fd} closed\n";
    }
}

$ws = new Ws();