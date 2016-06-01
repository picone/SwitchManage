<?php
namespace Home\Model;
class UserModel extends \Think\Model{
    protected $tableName='user';
    protected $fields=array(
        'id','username','password','real_name'
    );
    protected $pk='id';
    protected $autoinc=true;

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

    private function calculate_password($password){
        return md5(C('MD5_KEY').md5($password));
    }
}