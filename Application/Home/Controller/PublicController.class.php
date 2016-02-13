<?php
namespace Home\Controller;
class PublicController extends \Think\Controller{
    /**
     * 返回Ajax请求
     * @param integer $code
     * @param string $msg
     * @param array $data
     */
    protected function ajaxReturn($code,$data=null,$msg=null){
        $result['code']=$code;
        if($msg==null){
            $return_msg=C('RETURN_MESSAGE');
            if(isset($return_msg[$code])){
                $result['msg']=$return_msg[$code];
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