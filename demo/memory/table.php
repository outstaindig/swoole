<?php
use Swoole\Table;
//创建内存表
$table = new Swoole\Table(1024);

$table->column('id',Table::TYPE_INT,4);
$table->column('name',Table::TYPE_STRING,64);
$table->column('age',Table::TYPE_INT,3);
$table->create();

$table->set(1,['id'=>'1','name'=>'jie','age'=>24]);
var_dump($table->get(1));
