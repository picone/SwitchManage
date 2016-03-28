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

    public function detail($ip,$cmd){
        $this->init_client();
        if($this->client==null){
            $this->error('服务未启动');
        }else{
            $data=array('act'=>'Telnet','ip'=>$ip,'cmd'=>$cmd);
            if(!$this->client->send(json_encode($data))){
                $this->error('发送命令失败');
            }
            $data=$this->client->recv(8192, \swoole_client::MSG_PEEK | \swoole_client::MSG_WAITALL);
            if(!$data){
                $this->error('接收数据错误:'.$this->client->errCode);
            }
            $data=json_decode($data,true);
            $this->client->close();
            switch($data['code']){
                case 1:
                    $data['data']['version']=D('Device')->getVersion($ip);
                    $this->assign('data',$data['data']);
                    $this->assign('cmd',$cmd);
                    $this->assign('ip',$ip);
                    $this->display();
                    break;
                case 2:
                    $this->error('暂不支持操作该交换机');
                    break;
                case 3:
                    $this->error('暂不支持该命令');
                    break;
                default:
                    $this->error('连接交换机失败');
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