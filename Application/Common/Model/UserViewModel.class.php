<?php
namespace Common\Model;


use Think\Model\ViewModel;

class UserViewModel extends ViewModel{
    public $viewFields=array(
        'User'=>array('id','username','password','real_name','last_login_time','can_login'),
        'UserRoleUser'=>array('role_id','_on'=>'User.id=UserRoleUser.user_id'),
        'UserRole'=>array('name'=>'role','_on'=>'UserRole.id=UserRoleUser.role_id')
    );
}
