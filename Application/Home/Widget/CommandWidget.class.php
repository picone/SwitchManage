<?php
namespace Home\Widget;
class CommandWidget extends \Think\Controller{
    public function showCommand(){
        $data=D('Command')->fetchCommand($GLOBALS['user_id'],I('get.ip',''));
        $this->assign('data',$data);
        $this->display('Command:showCommand');
    }
    
    public function render($command,$data){
        $this->assign($data);
        $this->display('Command:'.$command);
    }
}