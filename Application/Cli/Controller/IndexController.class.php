<?php
namespace Cli\Controller;
class IndexController extends \Think\Controller{

    const PING_INTERVAL=60000;

    private $conn;

    public function index(){
        $server=new \swoole_server('127.0.0.1',9501);
        $server->set(array(
            'task_worker_num'=>2,
            'daemonize'=>false,
            'max_request'=>1024,
            'debug_mode'=>1
        ));
        $server->on('WorkerStart',array($this,'onWorkerStart'));
        $server->on('Timer',array($this,'onTimer'));
        $server->on('Receive',array($this,'onReceive'));
        $server->on('Task',array($this,'onTask'));
        $server->on('Finish',array($this,'onFinish'));
        $this->conn=array();
        $server->start();
    }

    public function onWorkerStart(\swoole_server $server,$worker_id){
        $server->addtimer(self::PING_INTERVAL);//增加定时ping
    }

    public function onTimer(\swoole_server $server,$interval){
        if($interval==self::PING_INTERVAL){

        }
    }

    public function onReceive(\swoole_server $server,$fd,$from_id,$data){
        $data=trim($data);
        if($data=='Stop'){
            $server->shutdown();
            $server->send($fd,'已发送关闭命令'.PHP_EOL,$from_id);
        }else if($data=='Stats'){
            $data=$server->stats();
            $result='服务启动时间'.date('Y-m-d H:i:s',$data['start_time']).PHP_EOL;
            $result.='当前连接数量'.$data['connection_num'].PHP_EOL;
            $result.='接受的链接数'.$data['accept_count'].PHP_EOL;
            $result.='关闭的连接数'.$data['close_count'].PHP_EOL;
            $result.='当前正在排队的任务数'.$data['tasking_num'].PHP_EOL;
            $server->send($fd,$result,$from_id);
        }else if($data=='Reload'){
            $server->reload();
            $server->send($fd,'已发送重启命令'.PHP_EOL,$from_id);
        }else{
            $server->send($fd,$data,$from_id);
        }
    }

    public function onTask(\swoole_server $server,$fd,$from_id,$data){

    }

    public function onFinish(\swoole_server $server,$task_id,$data){

    }
}