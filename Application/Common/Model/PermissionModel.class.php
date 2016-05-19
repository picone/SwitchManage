<?php
/**
 * Created by PhpStorm.
 * User: chien
 * Date: 16-5-14
 * Time: 下午2:03
 */

namespace Common\Model;


class PermissionModel extends \Think\Model{
    protected $tableName='permission';
    protected $fields=array(
        'user_id','position_id','creator','create_time'
    );
    protected $pk=array('user_id','position_id');
    
    public function checkPermission($user_id,$ip){
        return $this->join('device_view USING (position_id)')->where('user_id=%d AND ip=%d',$user_id,$ip)->count()==1?true:false;
    }
}