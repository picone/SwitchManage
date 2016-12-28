<?php
namespace Admin\Controller;

class IndexController extends PublicController{

    public function index(){
        $this->assign('device_num',D('Device')->count());
        $this->assign('user_num',D('User')->count());
        $this->display();
    }

}