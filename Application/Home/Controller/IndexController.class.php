<?php
namespace Home\Controller;
class IndexController extends PublicController{
    public function index(){
        $down_list=D('DeviceView')->getDownList();
        $this->assign('down_list',$down_list);
        $this->assign('up_num',D('Device')->getUpCount());
        $this->assign('down_num',count($down_list));
        $this->display();
    }
    
    public function getAvailability(){
        $data=D('Availability')->availability();
        $tmp=['日','一','二','三','四','五','六'];
        foreach($data as &$val){
            $val['dateline']='周'.$tmp[date('w',$val['dateline'])];
        }
        $this->ajaxReturn(1,$data);
    }

    public function detail($day){
        $tmp=[
            '日'=>'Sun',
            '一'=>'Mon',
            '二'=>'Tue',
            '三'=>'Wed',
            '四'=>'Thur',
            '五'=>'Fri',
            '六'=>'Sat'
        ];
        $time=strtotime('last '.$tmp[$day]);
        $time=strtotime('00:00:00',$time);
        $this->assign('data',D('Availability')->fetchDownList($time));
        $this->assign('time',$time);
        $this->display();
    }
}