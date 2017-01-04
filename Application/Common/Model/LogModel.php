<?php
/**
 * Created by PhpStorm.
 * User: ChienHo
 * Date: 17/1/5
 * Time: 上午1:11
 */

namespace Common\Model;


use Think\Model;

class LogModel extends Model{
    protected $tableName='log';
    protected $fields=array(
        'ip','user_id','command_id','interface','arg','create_time'
    );
    protected $pk=false;
}
