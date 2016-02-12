<?php
namespace Cli\Controller;
class CtrlController extends \Think\Controller{

    private $client;

    public function _initialize(){
        $this->client=new \swoole_client(SWOOLE_SOCK_TCP);
        if(!$this->client->connect('127.0.0.1',9502)){
            echo '连接失败:',$this->client->errCode,PHP_EOL;
            $this->client=null;
        }
    }

    public function stop(){
        if($this->client!=null){
            $this->client->send('Stop');
            echo $this->client->recv();
            $this->client->close();
        }
    }

    public function stats(){
        if($this->client!=null){
            $this->client->send('Stats');
            echo $this->client->recv();
            $this->client->close();
        }
    }

    public function reload(){
        if($this->client!=null){
            $this->client->send('Reload');
            echo $this->client->recv();
            $this->client->close();
        }
    }
}