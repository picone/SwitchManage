<?php
namespace Common\Model;
class DeviceModel extends \Think\Model{
    protected $tableName='device';
    protected $fields=array(
        'ip','position_id','name','val','update_time'
    );
    protected $pk='ip';

    public function fetchAll(){
        return $this->field('ip')->select();
    }

    public function setVal($ip,$val,$time=''){
        return $this->where('ip=%d',ip2long($ip))->save(array(
            'val'=>$val,
            'update_time'=>$time==''?NOW_TIME:$time
        ));
    }
}