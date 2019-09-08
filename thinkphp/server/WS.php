<?php
class WS
{
    const HOST = '127.0.0.1';
    const PORT = 8801;
    const CHART_PORT = 8802;

    public $ws;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server(self::HOST, self::PORT);
        $this->ws->listen(self::HOST, self::CHART_PORT, SWOOLE_SOCK_TCP);
        $this->ws->set([
            'document_root' => '/Users/fujie/object/swoole/thinkphp/public/static', // v4.4.0以下版本, 此处必须为绝对路径
            'enable_static_handler' => true,
            'worker_num'=>2,
            'task_worker_num'=>4
        ]);

        $this->ws->on('WorkerStart',[$this,'onWorkerStart']);
        $this->ws->on('request',[$this,'onRequest']);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->on('close',[$this,'onClose']);
        @$this->ws->start();
    }

    /**
     * @param $serv
     * @param $worker_id
     */
    public function onWorkerStart($serv,$worker_id)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        //加载框架里面的文件
        //require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';

        \app\common\lib\PRedis::getInstance()->flushAll();
    }

    /**
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        $_FILES = [];

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
                $_POST[strtolower($k)] = $v;
            }
        }

        if(isset($request->files)){
            foreach ($request->files as $k=>$v){
                $_FILES[strtolower($k)] = $v;
            }
        }

        $_POST['ws_server'] = $this->ws;

        ob_start();
        try{
            think\App::run()->send();
        }catch (\Exception $e){
        }
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws,$request)
    {
        echo "client {$request->fd}\n";
        if($request->fd){
            \app\common\lib\PRedis::getInstance()->sadd(config('redis.live_redis_key'),$request->fd);
        }
    }

    /**
     * 监听ws事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws,$frame)
    {
        //$ws->push($frame->fd, "server push:".date('Y-m-d H:i:s'));
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     * @return string
     */
    public function onTask($serv,$taskId,$workerId,$data)
    {
        try{
            $task = new app\common\lib\Task();
            $method = $data['method'];
            $task->$method($data['data'],$serv);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        $serv->finish([123, 'hello']);
        //return 'on task finish';
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function OnFinish($serv,$taskId,$data)
    {
        echo "taskId: $taskId";
        //echo "data : ".json_encode($data);
    }

    /**
     * @param $ws
     * @param $fd
     */
    public function onClose($ws,$fd)
    {
        \app\common\lib\PRedis::getInstance()->srem(config('redis.live_redis_key'),$fd);
        echo "client {$fd} closed\n";
    }
}

$ws = new ws();