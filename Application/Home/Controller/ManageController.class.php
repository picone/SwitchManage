<?php
namespace Home\Controller;
class ManageController extends PublicController{

    public function index(){
        $this->display();
    }

    public function tree(){
        $this->display();
    }

    public function detail($ip,$cmd,$int=null){
        $client=new \swoole_client(SWOOLE_TCP,SWOOLE_SYNC);
        if(!$client->connect(C('SERVICE_IP'),C('SERVICE_PORT'),10)){
            $this->error('服务未启动');
        }
        $data=array('act'=>'Telnet','ip'=>$ip,'cmd'=>$cmd);
        if(!$client->send(json_encode($data)."\n")){
            $this->error('发送命令失败');
        }
        $c=30;
        $str='';
        do{
            $str.=$client->recv();
            $data=json_decode($str,true);
        }while($c-->0&&!isset($data['code']));
        $client->close();
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
                var_dump($data);
                //$this->error('接收数据错误:'.$client->errCode);
        }
    }
    
    public function getInterface(){
        
    }
}