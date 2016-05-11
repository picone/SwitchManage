<?php
namespace Home\Model;
class AvailabilityModel extends \Think\Model{
    protected $tableName='availability';
    protected $fields=array(
        'ip','dateline','availability'
    );
    protected $pk=array('ip','dateline');

    public function availability(){
        return $this->field('COUNT(ip) AS num,dateline')->where(array(
            'dateline'=>array('exp','>unix_timestamp(curdate()-INTERVAL 7 DAY)'),
            'availability'=>array('lt',1)
        ))->group('dateline')->order('dateline')->select();
    }

    public function fetchDownList($time){
        return $this->field('device_view.position_name,device_view.ip,device_name,availability')->join('device_view ON device_view.ip=availability.ip')->where('dateline=%d AND availability<1',$time)->order('availability')->select();
    }
}