<?php
/**
 * Created by PhpStorm.
 * User: ChienHo
 * Date: 17/1/5
 * Time: 上午1:03
 */

namespace Admin\Controller;


use Common\Library\Vendor\Page;

class LogController extends PublicController{

    public function index(){
        $where=array();
        if(I('GET.username')){
            $where['username']=I('GET.username');
        }
        if(I('GET.ip')){
            $where['ip']=ip2long(I('GET.ip'));
        }
        if(I('GET.command/d')>0){
            $where['command_id']=I('GET.command');
        }
        $page=new Page(D('LogView')->where($where)->count());
        $data=D('LogView')->where($where)->limit($page->firstRow,$page->listRows)->select();
        $this->assign('command',D('Command')->scope('select')->select());
        $this->assign('page',$page->show());
        $this->assign('data',$data);
        $this->display();
    }
}
