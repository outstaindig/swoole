<?php
class Http
{
    const HOST = '127.0.0.1';
    const PORT = 8801;

    public $http;

    public function __construct()
    {
        $this->http = new Swoole\Http\Server(self::HOST, self::PORT);
        $this->http->set([
            'document_root' => '/Users/fujie/object/swoole/thinkphp/public/static', // v4.4.0以下版本, 此处必须为绝对路径
            'enable_static_handler' => true,
            'worker_num'=>5,
            'task_worker_num'=>4
        ]);

        $this->http->on('WorkerStart',[$this,'onWorkerStart']);
        $this->http->on('request',[$this,'onRequest']);
        $this->http->on('task',[$this,'onTask']);
        $this->http->on('finish',[$this,'onFinish']);
        $this->http->on('close',[$this,'onClose']);
        @$this->http->start();
    }

    public function onWorkerStart($serv,$worker_id)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        //加载框架里面的文件
        //require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';
    }

    public function onRequest($request, $response)
    {
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

        $_POST['http_server'] = $this->http;

        ob_start();
        try{
            think\App::run()->send();
        }catch (\Exception $e){
        }
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    public function onTask($serv,$taskId,$workerId,$data)
    {
        try{
            $task = new app\common\lib\Task();
            $method = $data['method'];
            $task->$method($data);
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        $serv->finish([123, 'hello']);
        //return 'on task finish';
    }

    public function OnFinish($serv,$taskId,$data)
    {
        echo "taskId: $taskId";
        echo "data : $data";
    }

    public function onClose($ws,$fd)
    {
        echo "client {$fd} closed\n";
    }
}

$http = new Http();