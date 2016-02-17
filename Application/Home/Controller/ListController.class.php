<?php
namespace Home\Controller;
class ListController extends PublicController{
    public function index(){
        $data=D('DeviceView')->fetchAll();
        $res=array();
        foreach($data as &$val){
            $res[$val['position_name']][]=array('ip'=>$val['ip'],'name'=>$val['device_name'],'status'=>$val['val']>=0);
        }
        unset($data);
        $this->assign('data',$res);
        $this->display();
    }

    public function getDetail($ip=0){
        if($ip==0){
            $this->ajaxReturn(6);
        }else{
            $data=D('History')->fetchIp($ip);
            $this->ajaxReturn(0,$data);
        }
    }
}