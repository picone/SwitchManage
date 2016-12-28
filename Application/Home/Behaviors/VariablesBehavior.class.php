<?php
namespace Home\Behaviors;
class VariablesBehavior extends \Think\Behavior{
    private $list=array(
        'Login'=>array(
            'index',
            'login',
            'vcode'
        ),
    );

    public function run(&$param){
        $GLOBALS['user_id']=intval($_SESSION[C('USER_AUTH_KEY')]);
        if($GLOBALS['user_id']>0){
            $GLOBALS['username']=$_SESSION['username'];
            $GLOBALS['real_name']=$_SESSION['real_name'];
        }else{//未登录
            if(!$this->checkAccess()){
                redirect(C('USER_AUTH_GATEWAY'));
            }
        }
    }

    private function checkAccess(){
        foreach($this->list as $key=>&$val){
            if(is_array($val)&&$key==CONTROLLER_NAME){//精确到方法
                return in_array(ACTION_NAME,$val);
            }else{//精确到控制器
                if(CONTROLLER_NAME==$val)return true;
            }
        }
        return false;
    }
}