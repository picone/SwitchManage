<?php
namespace Common\Model;
class CommandModel extends \Think\Model{
    protected $tableName='command';
    protected $fields=array(
        'id','key_','name','command','arg_type','version_id','description'
    );
    protected $pk='id';
    protected $autoinc=true;

    public function getKey($id){
        return $this->cache(true)->field('key_')->where('id=%d',$id)->find()['key_'];
    }

    public function fetchCommand(){
        return $this->field('id,name')->select();
    }
}