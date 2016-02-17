<?php
namespace Home\Controller;
class PublicController extends \Think\Controller{

    private $ajax_msg=array(
        1=>'成功',
        2=>'请求方式不正确',
        3=>'用户名或密码错误',
        4=>'超过限制次数,请15分钟后重试',
        5=>'验证码错误',
        6=>'请求IP有误'
    );

    /**
     * 返回Ajax请求
     * @param integer $code
     * @param string $msg
     * @param array $data
     */
    protected function ajaxReturn($code,$data=null,$msg=null){
        $result['code']=$code;
        if($msg==null){
            if(isset($this->ajax_msg[$code])){
                $result['msg']=$this->ajax_msg[$code];
            }
        }else{
            $result['msg']=$msg;
        }
        if($data!=null){
            $result['data']=$data;
        }
        parent::ajaxReturn($result);
    }
}