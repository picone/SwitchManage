<?php
namespace Home\Widget;
class CommandWidget extends \Think\Controller{
    public function showCommand(){
        $data=D('Command')->fetchCommand();
        $this->assign('data',$data);
        $this->display('Command:showCommand');
    }
    
    public function render($command,$data){
        $this->assign($data);
        $this->display('Command:'.$command);
    }
}