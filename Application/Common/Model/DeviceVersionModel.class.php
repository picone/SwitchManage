<?php
namespace Common\Model;
class DeviceVersionModel extends \Think\Model{
    protected $tableName='device_version';
    protected $fields=array(
        'id','version'
    );
    protected $pk='id';
    protected $autoinc='id';

    protected $_scope = array(
        'select'=>array(
            'order'=>'id'
        )
    );

    public function insert($version){
        return $this->add(array(
            'version'=>$version
        ));
    }

    public function fetchId($version){
        return $this->field('id')->where('version=\'%s\'',$version)->find();
    }
}