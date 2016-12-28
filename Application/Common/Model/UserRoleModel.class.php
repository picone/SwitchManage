<?php
namespace Common\Model;


use Think\Model;

class UserRoleModel extends Model{
    protected $tableName='user_role';
    protected $fields=array(
        'id','name','pid','status','remark'
    );
    protected $autoinc=true;
}
