<?php
namespace Common\Model;
class HistoryModel extends \Think\Model{
    protected $tableName='history';
    protected $fields=array(
        'ip','dateline','val'
    );

    public function insert($ip,$val,$time=0){
        return $this->add(array(
            'ip'=>ip2long($ip),
            'val'=>$val,
            'dateline'=>$time==0?NOW_TIME:$time
        ));
    }

    public function fetchIp($ip,$time=0){
        $where['ip']=$ip;
        if($time>0){
            $where['dateline']=array('gt',NOW_TIME-$time);
        }
        return $this->field('dateline,val')->where($where)->select();
    }
}