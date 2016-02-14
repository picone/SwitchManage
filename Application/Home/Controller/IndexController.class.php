<?php
namespace Home\Controller;
class IndexController extends PublicController{
    public function index(){
        $down_list=D('DeviceView')->getDownList();
        $this->assign('down_list',$down_list);
        $this->assign('up_num',D('Device')->getUpCount());
        $this->assign('down_num',count($down_list));
        $this->display();
    }
}