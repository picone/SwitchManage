<?php
namespace Common\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel{
    protected $tableName='user';
    protected $fields=array(
        'id','username','password','real_name','last_login_time','can_login'
    );
    protected $pk='id';
    protected $autoinc=true;

    protected $_auto=array(
        array('password','calculate_password',self::MODEL_BOTH,'callback')
    );

    protected $_validate=array(
        array('username','','用户名已存在',self::MUST_VALIDATE,'unique',self::MODEL_INSERT)
    );

    public function checkPassword($username,$password){
        $data=$this->field('id,password,real_name')->where(array('username'=>$username))->find();
        if(isset($data['password'])&&$data['password']==$this->calculate_password($password)){
            session('real_name',$data['real_name']);
            return $data['id'];
        }else{
            return 0;
        }
    }

    public function changePassword($id,$password){
        return $this->where('id=%d',$id)->setField('password',$this->calculate_password($password));
    }

    public function updateLoginTime($id){
        return $this->where('id=%d',$id)->save(array(
            'last_login_time'=>array('exp','CURRENT_TIME()')
        ));
    }

    public function calculate_password($password){
        return md5(C('MD5_KEY').md5($password));
    }
}