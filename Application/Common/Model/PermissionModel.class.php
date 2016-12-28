<?php
/**
 * Created by PhpStorm.
 * User: chien
 * Date: 16-5-14
 * Time: 下午2:03
 */

namespace Common\Model;

use Think\Model\RelationModel;

class PermissionModel extends RelationModel {
    protected $tableName='permission';
    protected $fields=array(
        'user_id','position_id','creator','create_time'
    );
    protected $pk=array('user_id','position_id');
    protected $_link=array(
        'Position'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'foreign_key'=>'position_id',
        )
    );
    
    public function checkPermission($user_id,$ip){
        return $this->join('device_view USING (position_id)')->where('user_id=%d AND ip=%d',$user_id,$ip)->count()==1?true:false;
    }

    public function updatePermission($user_id,$position,$creator){
        $data=array();
        foreach ($position as $val){
            $data[]=array(
                'user_id'=>$user_id,
                'position_id'=>intval($val),
                'creator'=>$creator,
                'create_time'=>NOW_TIME
            );
        }
        $this->where('user_id=%d',$user_id)->delete();
        return $this->addAll($data);
    }
}