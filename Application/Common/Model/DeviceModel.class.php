<?php
namespace Common\Model;
class DeviceModel extends \Think\Model{
    protected $tableName='device';
    protected $fields=array(
        'ip','position_id','version_id','name','val','update_time'
    );
    protected $pk='ip';

    public function fetchAll(){
        return $this->field('ip')->select();
    }

    public function setVal($ip,$val,$time=0){
        if($val<0){//宕机
            $data=$this->field('val')->where('ip=%d',$ip)->find();
            if($data['val']<0)return true;
        }
        return $this->where('ip=%d',ip2long($ip))->save(array(
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
        return $this->cache(true)->field('device_version.version')->where('ip=%d',$ip)->join('device_version ON device_version.id=device.version_id')->find();
    }
}