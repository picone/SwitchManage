<?php
namespace Cli\Controller;
use Cli\Model\TelnetModel;

class IndexController extends \Think\Controller{

    const INTERVAL_PING=60000;

    private $conn;

    public function index(){
        $server=new \swoole_server(C('SERVICE_IP'),C('SERVICE_PORT'));
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
        $server->on('pipeMessage',array($this,'onPipeMessage'));
        $server->on('WorkerError',array($this,'onWorkerError'));
        $this->conn=array();
        $server->start();
    }

    public function onWorkerStart(\swoole_server $server,$worker_id){
        if($server->taskworker){
            swoole_set_process_name('switch_manage_task_worker');
            $server->sendMessage('worker start.',$server->worker_id-1);
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

        }else{
            $data=json_decode($data);
            if($data->act=='Telnet'&&isset($data->ip)&&isset($data->cmd)){//telnet交换机
                if(!isset($this->conn[$data->ip])){
                    $this->conn[$data->ip]=new TelnetModel($data->ip,C('TELNET_PASSWORD'));
                }
                $switch=$this->conn[$data->ip];
                $switch->connect();
                $result=array();
                if($data->cmd=='getInfo'){
                    $result=array();
                    if(preg_match('/uptime is (.*?)\\r\\n/',$switch->exec('dis version'),$match)){
                        $result['uptime']=$match[1];
                    }
                    if(preg_match('/(\d+)% in last 5 seconds/',$switch->exec('dis cpu'),$match)){
                        $result['cpu']=intval($match[1]);
                    }
                    $res=$switch->exec('dis connection');
                    if(preg_match('/Total (\d+) connection/',$res,$match)){
                        $result['online_list_count']=intval($match[1]);
                    }
                    if(preg_match_all('/(\w+)@system\\r\\n.*?(\w{4}\-\w{4}\-\w{4}).*?(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/',$res,$match)){
                        array_shift($match);
                        $result['online_list']=$match;
                    }
                    $result['status']=0;
                }
                $server->send($fd,json_encode($result),$from_id);
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

    public function onPipeMessage(\swoole_server $server,$worker_id,$data){
        echo date('Y-m-d H:i:s'),':#',$worker_id,':',$data,PHP_EOL;
    }

    public function onWorkerError(\swoole_server $server,$worker_id,$worker_pid,$exit_code){
        $msg='WorkerError:#'.$worker_id.' PID:'.$worker_pid.' ExitCode:'.$exit_code.PHP_EOL;
        echo $msg;
        \Think\Log::write($msg);
    }
}