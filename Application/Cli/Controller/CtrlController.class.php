<?php
namespace Cli\Controller;
class CtrlController extends \Think\Controller{

    private $client;

    public function _initialize(){
        $this->client=new \swoole_client(SWOOLE_SOCK_TCP);
        if(!$this->client->connect('127.0.0.1',9501)){
            echo '连接失败:',$this->client->errCode,PHP_EOL;
            $this->client=null;
        }
    }

    public function stop(){
        if($this->client!=null){
            $this->client->send('Stop');
            if($this->client->recv()=='success'){
                echo '关闭成功';
            }else{
                echo '服务无响应';
            }
            echo PHP_EOL;
            $this->client->close();
        }
    }

    public function stats(){
        if($this->client!=null){
            $this->client->send('Stats');
            $data=json_decode($this->client->recv(),true);
            echo '服务启动时间',date('Y-m-d H:i:s',$data['start_time']),PHP_EOL;
            echo '当前连接数量',$data['connection_num'],PHP_EOL;
            echo '接受的链接数',$data['accept_count'],PHP_EOL;
            echo '关闭的连接数',$data['close_count'],PHP_EOL;
            echo '当前正在排队的任务数',$data['tasking_num'],PHP_EOL;
            $this->client->close();
        }
    }

    public function reload(){
        if($this->client!=null){
            $this->client->send('Reload');
            if($this->client->recv()=='success'){
                echo '重启成功';
            }else{
                echo '服务无响应';
            }
            echo PHP_EOL;
            $this->client->close();
        }
    }

    public function reset_telnet(){
        if($this->client!=null){
            $this->client->send('ResetTelnet');
            if($this->client->recv()=='success'){

            }else{

            }
            $this->client->close();
        }
    }
}