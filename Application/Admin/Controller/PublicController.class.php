<?php
namespace Admin\Controller;

use Org\Util\Rbac;

class PublicController extends \Think\Controller{

    public function _initialize(){
        $GLOBALS['user_id']=intval($_SESSION[C('USER_AUTH_KEY')]);
        if($GLOBALS['user_id']>0){
            import('ORG.Util.RBAC');
            if(RBAC::AccessDecision()){
                $GLOBALS['username']=$_SESSION['username'];
                $GLOBALS['real_name']=$_SESSION['real_name'];
            }else{
                $this->error('没有权限');
            }
        }else{//未登录
            $this->error('请先登录',__ROOT__.'/index.php/Login');
        }
    }
}
