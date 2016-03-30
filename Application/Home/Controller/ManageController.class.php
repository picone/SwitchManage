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
            $data=$this->exec(array('ip'=>$ip,'cmd'=>$cmd,'arg'=>$int));
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
                $this->error('接收数据错误');
        }
    }
    
    public function getInterface($ip,$cmd){
        $cmd=D('Command')->getCommand($cmd);
        if(!$cmd)$this->ajaxReturn(7);
        if($cmd['arg_type']==0){
            $this->ajaxReturn(1,array());
        }
        $data=F('Interface_'.long2ip($ip));
        if(!$data){
            $this->exec(array('ip'=>$ip,'cmd'=>4));
            $data=F('Interface_'.long2ip($ip));
        }
        if(!$data)$this->ajaxReturn(9);
        if($cmd['arg_type']==1){
            array_unshift($data,'全局');
        }
        $this->ajaxReturn(1,$data);
    }
    
    private function exec($cmd){
        $cmd['act']='Telnet';
        $client=new \swoole_client(SWOOLE_TCP,SWOOLE_SYNC);
        if(!$client->connect(C('SERVICE_IP'),C('SERVICE_PORT'),10)){
            $this->error('服务未启动');
        }
        if(!$client->send(json_encode($cmd))){
            $this->error('发送命令失败');
        }
        $c=5;
        $str='';
        do{
            $str.=$client->recv();
            $data=json_decode($str,true);
        }while($c-->0&&!isset($data['code']));
        $client->close();
        return $data;
    }
}