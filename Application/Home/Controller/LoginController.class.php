<?php
namespace Home\Controller;
class LoginController extends PublicController{
    public function index(){
        $this->display();
    }

    public function login(){
        if(!IS_POST)$this->ajaxReturn(2);
        $ip=get_client_ip();
        $ban_times=S('ban_'.$ip);
        if($ban_times>5)$this->ajaxReturn(4);
        $username=I('post.username');
        $password=I('post.password');
        if($username==''||$password=='')$this->ajaxReturn(3);
        $verify=new \Think\Verify();
        if(!$verify->check(I('post.verify_code')))$this->ajaxReturn(5);
        $id=D('User')->checkPassword($username,$password);
        if($id==0){
            S('ban_'.$GLOBALS['ip'],$ban_times+1,900);
            $this->ajaxReturn(3);
        }
        session('user_id',$id);
        session('username',$username);
        $this->ajaxReturn(1,array('url'=>__APP__));
    }

    public function vcode(){
        $verify=new \Think\Verify();
        $verify->entry();
    }
}