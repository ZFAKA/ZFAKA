<?php
/*
 * CLI 的入口文件
 * CLI 下一般用于执行定时脚本或任务
 */
header('content-Type:text/html;charset=utf-8;');
define('APP_PATH',  realpath(dirname(__FILE__) . '/../'));
\Yaf\Loader::import(APP_PATH.'/application/init.php');
$app = new \Yaf\Application(APP_PATH.'/conf/application.ini');
$app->bootstrap()->getDispatcher()->dispatch(new \Yaf\Request\Simple());