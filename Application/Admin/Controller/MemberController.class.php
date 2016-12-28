<?php
namespace Admin\Controller;

use Common\Library\Vendor\Page;

class MemberController extends PublicController{

    public function index(){
        $page=new Page(D('User')->count(),2);
        $member=D('UserView')->limit($page->firstRow,$page->listRows)->select();
        $this->assign('member',$member);
        $this->assign('page',$page->show());
        $this->display();
    }

    public function device($id){
        if(IS_POST){
            $ids=I('post.position');
            D('Permission')->updatePermission($id,$ids,$GLOBALS['user_id']);
        }
        $this->assign('position',D('Position')->select());
        $permission=array();
        foreach (D('Permission')->field('position_id')->where('user_id=%d',$id)->select() as $val){
            array_push($permission,$val['position_id']);
        }
        $this->assign('permission',$permission);
        $this->display();
    }

    public function edit($id=0){
        if(IS_POST){
            $data=D('User')->create();
            if(!$data){
                $this->error(D('User')->getError());
            }
            if(!empty(I('post.password'))){
                $data['password']=D('User')->calculate_password(I('post.password'));
            }
            $data->save();
            $this->success('保存成功','index');
        }else{
            if($id>0){
                $this->assign('data',D('User')->relation(true)->find($id));
            }
            $this->assign('role',D('UserRole')->select());
            $this->display();
        }
    }
}
