<?php
namespace Common\Model;
class DeviceViewModel extends \Think\Model{
    protected $tableName='device_view';
    protected $fields=array(
        'ip','position_id','version_id','position_name','device_name','version_name','val','update_time'
    );
    protected $pk='ip';

    public function getDownList(){
        return $this->cache(true,30)->field('ip,position_name,device_name,update_time')->where('val<0')->select();
    }

    public function fetchAll(){
        return $this->field('ip,position_name,device_name,val')->select();
    }
}