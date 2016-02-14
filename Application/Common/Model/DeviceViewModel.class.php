<?php
namespace Common\Model;
class DeviceModel extends \Think\Model{
    protected $tableName='device_view';
    protected $fields=array(
        'ip','position_id','position_name','device_name','val','update_time'
    );
    protected $pk='ip';
}