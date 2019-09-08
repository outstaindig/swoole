<?php
class AysMysql
{
    public $dbSource;
    public $dbConfig;

    public function __construct()
    {
        $this->dbSource = new swoole_mysql();
        $this->dbConfig = array(
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'toor',
            'database' => 'zhong',
            'charset' => 'utf8', //指定字符集
            'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
        );
    }

    public function execute()
    {
        $this->dbSource->connect($this->dbConfig, function ($db, $r) {
            if ($r === false) {
                var_dump($db->connect_errno, $db->connect_error);
                die;
            }
            $sql = 'show tables';
            $db->query($sql, function(swoole_mysql $db, $r) {
                if ($r === false) {
                    var_dump($db->error, $db->errno);
                } elseif ($r === true ) {
                    var_dump($db->affected_rows, $db->insert_id);
                }
                var_dump($r);
                $db->close();
            });
        });
    }
}

$aysMysql = new AysMysql();
$aysMysql->execute();