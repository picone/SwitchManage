<?php
namespace Cli\Controller;
use Cli\Model\TelnetModel;

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

    public function onReceive(\swoole_server $server,$fd,$from_id,$data){
        $data=trim($data);
        if($data=='Stop'){//停止
            $server->shutdown();
            $server->send($fd,'success',$from_id);
        }else if($data=='Stats'){//状态
            $data=$server->stats();
            $server->send($fd,json_encode($data),$from_id);
        }else if($data=='Reload'){//重启
            $server->reload();
            $server->send($fd,'success',$from_id);
        }else if($data=='ResetTelnet'){//重置所有telnet链接
            $this->conn[]=rand(9);
            $server->send($fd,json_encode($this->conn),$from_id);
        }else{
            $data=json_decode($data);
            if($data->act=='Telnet'&&isset($data->ip)&&isset($data->cmd)){//telnet交换机
                if(!isset($this->conn[$data->ip])){
                    $this->conn[$data->ip]=new TelnetModel($data->ip);
                }
                //$this->conn[];
            }else{
                $server->send($fd,'fail',$from_id);
            }
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