<?php
namespace Common\Model;
class CommandModel extends \Think\Model{
    protected $tableName='command';
    protected $fields=array(
        'id','display_order','key_','name','command','arg_type','version_id','description','permission'
    );
    protected $pk='id';
    protected $autoinc=true;
    protected $_scope = array(
        'select'=>array(
            'field'=>'id,name',
            'order'=>'display_order DESC'
        )
    );

    public function getKey($id){
        return $this->cache(true)->field('key_')->where('id=%d',$id)->find()['key_'];
    }

    public function fetchCommand($user_id,$ip){
        $model=$this->field('id,name,description,arg_type');
        if(!D('Permission')->checkPermission($user_id,$ip)){
            $model=$model->where('permission=0');
        }
        return $model->order('display_order DESC,id')->select();

    }
    
    public function getCommand($id){
        return $this->cache(true)->field('name,arg_type,description,permission')->where('id=%d',$id)->find();
    }
}