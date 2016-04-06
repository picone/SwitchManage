<?php
namespace Home\Controller;
class IndexController extends PublicController{
    public function index(){
        echo D('DeviceView')->fetchSql(true)->field('ip,position_name,device_name,update_time')->where('val<0')->select();
        $down_list=D('DeviceView')->getDownList();
        $this->assign('down_list',$down_list);
        $this->assign('up_num',D('Device')->getUpCount());
        $this->assign('down_num',count($down_list));
        $this->display();
    }
}