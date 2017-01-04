<?php
/**
 * Created by PhpStorm.
 * User: ChienHo
 * Date: 17/1/5
 * Time: 上午1:14
 */

namespace Common\Model;


use Think\Model\ViewModel;

class LogViewModel extends ViewModel{
    public $viewFields=array(
        'Log'=>array('ip','user_id','command_id','interface','arg','create_time'),
        'Command'=>array('name'=>'command_name','_on'=>'Command.id=Log.command_id'),
        'User'=>array('username','_on'=>'Log.user_id=User.id'),
        'Device'=>array('name'=>'device_name','_on'=>'Device.ip=Log.ip')
    );
}