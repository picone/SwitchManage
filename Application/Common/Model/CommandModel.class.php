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
        return $this->cache(true)->field('id,name,description,arg_type')->select();
    }
    
    public function getCommand($id){
        return $this->cache(true)->field('name,arg_type,description')->where('id=%d',$id)->find();
    }
}