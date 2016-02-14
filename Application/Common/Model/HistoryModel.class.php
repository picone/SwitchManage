<?php
namespace Common\Model;
class HistoryModel extends \Think\Model{
    protected $tableName='history';
    protected $fields=array(
        'ip','dateline','val'
    );

    public function insert($ip,$val,$time=0){
        $this->add(array(
            'ip'=>ip2long($ip),
            'val'=>$val,
            'dateline'=>$time==0?NOW_TIME:$time
        ));
    }
}