<?php
namespace Common\Model;


use Think\Model;

class UserRoleUserModel extends Model{
    protected $tableName='user_role_user';
    protected $fields=array(
        'role_id','user_id'
    );
}