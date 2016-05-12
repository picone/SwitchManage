<?php
namespace Home\Controller;
class ListController extends PublicController{
    public function index(){
        $data=D('DeviceView')->fetchAll();
        $res=array();
        foreach($data as &$val){
            $res[$val['position_name']][]=['ip'=>$val['ip'],'name'=>$val['device_name'],'status'=>$val['val']];
        }
        unset($data);
        $this->assign('data',$res);
        $this->display();
    }

    public function detail($ip){
        $this->display();
    }

    public function getDetail($ip=0){
        $ip=intval($ip);
        if($ip==0){
            $this->ajaxReturn(6);
        }else{
            $data=D('History')->fetchIp($ip,1800);
            if(isset($data[0])){
                $this->ajaxReturn(0,$data);
            }else{
                $this->ajaxReturn(6);
            }
        }
    }
    
    public function getAvailability($ip,$mod=1){
        
    }
}