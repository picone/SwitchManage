<?php
namespace Home\Controller;
class ManageController extends PublicController{

    public function index($ip = 0, $cmd = 0, $int = null)
    {
        $this->display();
        if ($ip != null) {
            $this->detail($ip, $cmd, $int);

        }
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
                $this->display('Manage:detail');
                break;
            case 2:
                $this->error('暂不支持操作该交换机');
                break;
            case 3:
                $this->error('暂不支持该命令');
                break;
            default:
                $this->error('暂不能提供服务');
        }
    }

    private function exec($cmd)
    {
        $cmd['act'] = 'Telnet';
        $client = new \swoole_client(SWOOLE_TCP, SWOOLE_SYNC);
        if (!$client->connect(C('SERVICE_IP'), C('SERVICE_PORT'), 1) || !$client->send(json_encode($cmd))) return false;
        $c = 5;
        $str = '';
        do {
            $str .= $client->recv();
            $data = json_decode($str, true);
        } while ($c-- > 0 && !isset($data['code']));
        $client->close();
        return $data;
    }

    public function tree()
    {
        $this->display();
    }
    
    public function getTree(){
        $data=D('DeviceView')->fetchAll();
        $res=array();
        foreach($data as &$val){
            $res[$val['position_name']][] = ['text' => long2ip($val['ip']), 'tags' => [$val['device_name']]];
        }
        $data=[];
        foreach($res as $key=>&$val){
            $data[]=['text'=>$key,'nodes'=>$val];
        }
        $this->ajaxReturn(1,$data);
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

    public function command($ip,$cmd,$int=null){
        if($int==null)
            $data=$this->exec(array('ip'=>$ip,'cmd'=>$cmd));
        else
            $data=$this->exec(array('ip'=>$ip,'cmd'=>$cmd,'arg'=>$int));
        if($data&&$data['code']==1){
            $this->assign('data',$data['data']);
            $this->display();
        }else{
            header('HTTP/1.1 503 Service Unavailable');
        }
    }
    
    public function connect($ip){
        $data=D('Device')->get($ip);
        if($data['val']<0)$this->ajaxReturn(8);
        $data=$this->exec(['ip'=>$ip,'cmd'=>0]);
        switch($data['code']){
            case 1:
                $this->ajaxReturn(1);
                break;
            case 2:
                $this->ajaxReturn(6);
                break;
            case 3:
                $this->ajaxReturn(8);
                break;
            default:
                $this->ajaxReturn(10);
        }
    }
}