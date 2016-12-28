<?php
namespace Common\Model;

use Think\Model;

class PositionModel extends Model{
    protected $tableName='position';
    protected $fields=array(
        'id','name'
    );
    protected $pk='id';
    protected $autoinc=true;
}
