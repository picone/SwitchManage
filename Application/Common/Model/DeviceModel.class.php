<?php
namespace Common\Model;
class DeviceModel extends \Think\Model{
    protected $tableName='device';
    protected $fields=array(
        'ip','position_id','name'
    );
    protected $pk='ip';

    public function fetchAll(){
        return $this->field('ip')->select();
    }
}