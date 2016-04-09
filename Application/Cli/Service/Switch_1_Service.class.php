<?php
/*
 * Quidway S3050操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_1_Service extends SwitchBaseService{

    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=1;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }

    /**
     * 获取端口概况
     * @return mixed
     */
    public function getBrief(){
        $data=$this->switch->exec('display brief interface');
        if(preg_match_all('/([EG]\d\/\d{1,2})\s+(UP|DOWN).*?(access|trunk).*?00BASE-T/',$data,$result)){
            array_shift($result);
            foreach($result[2] as &$val){
                if($val=='access')$val='A';
                else if($val=='trunk')$val='T';
            }
            F('Interface_'.$this->switch->getIp(),$result[0]);
            return ['no'=>1,'res'=>$result];
        }else{
            return ['no'=>2];
        }
    }

    /**
     * 获取日志
     * @return array
     */
    public function getLog(){
        $data=$this->switch->exec('display logbuffer');
        $res=array();
        //除去more,端口之间的换行
        $data=str_replace("\r\n  ---- More ----\x1b[42D                                          \x1b[42D",'',$data);
        $data=str_replace("\n\r",'',$data);
        preg_match_all('/%(.*?)[\\r\n|%]/',$data,$res['log']);
        $res['log']=$res['log'][1];
        return $res;
    }
}