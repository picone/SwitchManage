<?php
namespace Cli\Controller;
class IndexController extends \Think\Controller{

    const INTERVAL_PING=60000;

    private $conn;

    public function index(){
        $server=new \swoole_server('127.0.0.1',9501);
        $server->set(array(
            'task_worker_num'=>4,
            'daemonize'=>false,
            'heartbeat_check_interval'=>30,
            'heartbeat_idle_time'=>60
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
        if($server->taskworker){
            swoole_set_process_name('switch_manage_task_worker');
        }else{
            swoole_set_process_name('switch_manage_event_worker');
            if($worker_id==0){//防止tick被重复启动
                $server->tick(self::INTERVAL_PING,function() use($server){
                    $server->task('Ping');
                });
            }
        }
    }

    public function onTimer(\swoole_server $server,$interval){
        if($interval==self::INTERVAL_PING){
            $server->task('Ping');
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
        $server->close($fd);
    }

    public function onTask(\swoole_server $server,$fd,$from_id,$data){
        if($data=='Ping'){
            $res=D('Fping')->fping(DATA_PATH.'ip_list.txt');
            if(is_array($res)){
                $time=time();
                D('Device')->startTrans();
                foreach($res as $key=>&$val){
                    D('Device')->setVal($key,$val,$time);
                    D('History')->insert($key,$val,$time);
                }
                D('Device')->commit();
            }
        }
    }

    public function onFinish(\swoole_server $server,$task_id,$data){

    }
}