<?php
/**
 * Created by PhpStorm.
 * User: ChienHo
 * Date: 16/12/27
 * Time: 下午8:48
 */

namespace Admin\Controller;


use Common\Library\Vendor\Page;

class DeviceController extends PublicController{

    public function index(){
        $where=array();
        if(I('GET.position/d')>0){
            $where['position_id']=I('GET.position/d');
        }
        $page=new Page(D('Device')->where($where)->count());
        $data = D('DeviceView')->where($where)->limit($page->firstRow,$page->listRows)->order('ip')->select();
        $this->assign('data',$data);
        $this->assign('page',$page->show());
        $this->assign('position',D('Position')->scope('select')->select());
        $this->display();
    }

    public function edit($id=0){
        if(IS_POST){
            $data=D('Device')->create();
            if(!$data){
                $this->error(D('Device')->getError());
            }
            if($id==0){
                D('Device')->add($data);
            }else{
                D('Device')->save($data);
            }
            $this->redirect('/'.__APP__.'/Device');
        }
        $this->assign('position',D('Position')->scope('select')->select());
        $this->assign('version',D('DeviceVersion')->scope('select')->select());
        if($id>0){
            $this->assign('data',D('Device')->find($id));
        }
        $this->display();
    }

    public function delete($id){
        D('History')->where(array('ip'=>$id))->delete();
        D('Device')->where(array('ip'=>$id))->delete();
        $this->success('删除成功',__APP__.'/Device');
    }
}
