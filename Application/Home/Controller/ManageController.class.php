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

    public function detail($ip=2886756611){
        if($ip==0)$this->error('该交换机不存在');
        $this->init_client();
        if($this->client==null){
            $this->error('服务未启动');
        }else{
            $this->assign(D('Device')->getVersion($ip));
            $data=array('act'=>'Telnet','ip'=>long2ip($ip),'cmd'=>'getInfo');
            if(!$this->client->send(json_encode($data))){
                $this->error('发送失败');
            }
            $data=$this->client->recv();
            $data=json_decode($data,true);
            $this->client->close();
            if($data['status']===0){
                $this->assign('uptime',$data['uptime']);
                $this->assign('cpu',$data['cpu']);
                $this->assign('online_list_count',$data['online_list_count']);
                $this->assign('online_list',$data['online_list']);
                $this->display();
            }else{
                //$this->error('连接交换机失败');
            }
        }
    }

    private function init_client(){
        $this->client=new \swoole_client(SWOOLE_SOCK_TCP);
        if(!$this->client->connect(C('SERVICE_IP'),C('SERVICE_PORT'))){
            $this->client=null;
        }
    }
}