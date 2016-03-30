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
        if($int==null)
            $data=$this->exec(array('ip'=>$ip,'cmd'=>$cmd));
        else
            $data=$this->exec(array('ip'=>$ip,'cmd'=>$cmd,'int'=>$int));
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
    
    public function getInterface($ip,$cmd){
        $cmd=D('Command')->getCommand($cmd);
        if(!$cmd)$this->ajaxReturn(7);
        if($cmd['arg_type']==0){
            $this->ajaxReturn(1,[]);
        }
        $this->exec(array('ip'=>$ip,'cmd'=>4));
        $data=F('Interface_'.$ip);
        if(!$data)$this->ajaxReturn(9);
        $this->ajaxReturn(1,$data);
    }
    
    private function exec($cmd){
        $cmd['act']='Telnet';
        $client=new \swoole_client(SWOOLE_TCP,SWOOLE_SYNC);
        if(!$client->connect(C('SERVICE_IP'),C('SERVICE_PORT'),10)){
            $this->error('服务未启动');
        }
        $data=array($cmd);
        if(!$client->send(json_encode($data))){
            $this->error('发送命令失败');
        }
        $c=30;
        $str='';
        do{
            $str.=$client->recv();
            $data=json_decode($str,true);
        }while($c-->0&&!isset($data['code']));
        $client->close();
        return $data;
    }
}