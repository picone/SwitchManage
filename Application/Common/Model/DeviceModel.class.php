<?php
namespace Common\Model;
class DeviceModel extends \Think\Model{
    protected $tableName='device';
    protected $fields=array(
        'ip','position_id','version_id','name','val','update_time'
    );
    protected $pk='ip';
    protected $_auto = array(
        array('ip','ip2long',self::MODEL_BOTH,'function'),
    );
    protected $_validate = array(
        array('ip','require','请填写正确的IP地址'),
        array('position_id','require','请选择位置'),
        array('version_id','require','请选择型号'),
        array('name','require','请输入名字')
    );

    public function fetchAll(){
        return $this->field('ip')->select();
    }

    public function setVal($ip,$val,$time=0){
        $ip=ip2long($ip);
        if($val<0){//宕机
            $data=$this->field('val')->where('ip=%d',$ip)->find();
            if($data['val']<0)return true;
        }
        return $this->where('ip=%d',$ip)->save(array(
            'val'=>$val,
            'update_time'=>$time==0?NOW_TIME:$time
        ));
    }

    public function getUpCount(){
        return $this->where('val>=0')->count();
    }

    public function updateVersion($ip,$version_id){
        return $this->where('ip=%d',$ip)->setField('version_id',$version_id);
    }

    public function getVersion($ip){
        return $this->cache(true)->field('device_version.version')->where('ip=%d',$ip)->join('device_version ON device_version.id=device.version_id')->find()['version'];
    }
    
    public function getVersionId($ip){
        return $this->cache(true)->field('version_id')->where('ip=%d',$ip)->find()['version_id'];
    }
    
    public function get($ip){
        return $this->field('val')->where('ip=%d',$ip)->find();
    }
}