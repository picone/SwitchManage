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
            'dateline'=>array('gt','unix_timestamp(curdate()-INTERVAL 7 DAY)'),
            'availability'=>array('lt',1)
        ))->group('dateline')->select();
    }
}