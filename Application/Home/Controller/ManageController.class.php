<?php
namespace Home\Controller;
class ManageController extends PublicController{
    private $client;

    public function index(){
        $this->display();
    }

    public function tree(){
        $this->display();
    }

    public function detail($ip=2886756613){
        if($ip==0)$this->error('该交换机不存在');
        $this->init_client();
        if($this->client==null){
            //echo '服务未启动';
            $data='{"uptime":"0 week,4 days,1 hour,39 minutes","cpu":"11","online_list_count":"2","online_list":[["3112000353","3112000384"],["0860-6e15-cb41","b888-e3de-121a"],["10.10.30.4","10.10.30.3"]]}';
            $data=json_decode($data,true);
            $this->client->close();
            $this->assign($data);
            $this->display();
        }else{
            $data=D('Device')->getVersion($ip);
            $this->assign($data);
            $data=array('act'=>'Telnet','ip'=>long2ip($ip),'cmd'=>'getInfo');
            $this->client->send(json_encode($data));
            $data=$this->client->recv();
            $data=json_decode($data,true);
            $this->client->close();
            $this->assign($data);
            $this->display();
        }
    }

    private function init_client(){
        $this->client=new \swoole_client(SWOOLE_SOCK_TCP);
        if(!$this->client->connect('127.0.0.1',9501)){
            $this->client=null;
        }
    }
}