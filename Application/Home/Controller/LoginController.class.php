<?php
namespace Home\Controller;
class LoginController extends PublicController{
    public function index(){
        $this->display();
    }

    public function login(){
        if(!IS_POST)$this->ajaxReturn(2);
        $username=I('post.username');
        $password=I('post.password');
        if($username==''||$password=='')$this->ajaxReturn(3);
        $id=D('User')->checkPassword($username,$password);
        if($id==0){
            S('');
            $this->ajaxReturn(3);
        }
        session('user_id',$id);
        session('username',$username);
        $this->ajaxReturn(1,array('url'=>__APP__));
    }
}