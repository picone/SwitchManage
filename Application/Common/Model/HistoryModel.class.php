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

    public function fetchIp($ip){
        return $this->field('dateline,val')->where('ip=%d',$ip)->select();
    }
}