<?php
namespace Cli\Controller;
use Cli\Event\TelnetEvent;
use Cli\Model\TelnetModel;

class IndexController extends \Think\Controller{

    const INTERVAL_PING=60000;

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
            $server->sendMessage($data,$server->worker_id-1);
            $data=json_decode($data,true);
            if($data['act']=='Telnet'&&isset($data['ip'])&&isset($data['cmd'])){//执行命令
                $service=TelnetEvent::getService($data['ip']);
                if($service==null){
                    $result['code']=2;
                }else{
                    if($data['cmd']>0){
                        if(isset($data['arg'])){
                            $result['data']=$service->exec($data['cmd'],$data['arg']);
                        }else{
                            $result['data']=$service->exec($data['cmd']);
                        }
                        if($result==null||$result['data']==null){
                            $result=['code'=>3];
                        }else{
                            $result['code']=1;
                        }
                    }else{
                        $service->getSwitch()->connect();
                        if($service->getSwitch()->isConnect()){
                            $result['code']=1;
                        }else{
                            $result['code']=3;
                        }
                    }
                }
                $server->send($fd,json_encode($result),$from_id);
                $server->sendMessage(json_encode($result),$server->worker_id-1);
            }else{
                $server->send($fd,'unknown command!',$from_id);
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