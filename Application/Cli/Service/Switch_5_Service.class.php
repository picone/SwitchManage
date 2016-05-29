<?php
/*
 * H3C S3100-52P操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_5_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=5;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        $this->switch->command('reboot');
        $str='';
        $c=30;
        do{
            $str.=$this->switch->getBuffer();
        }while(strpos($str,'[Y/N]')!==false&&$c-->0);
        if($c>0){
            $this->switch->command('Y');
            return ['code'=>1];
        }else{
            return ['code'=>2];
        }
    }

    /**
     * 获取端口概况
     * @return mixed
     */
    public function getBrief(){
        $data=$this->switch->exec('display brief interface');
        if(preg_match_all('/((GE|Eth)\d\/\d\/\d{1,2})\s+(UP|DOWN).*?(trunk|access)/',$data,$result)){
            array_shift($result);
            array_splice($result,1,1);
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
}