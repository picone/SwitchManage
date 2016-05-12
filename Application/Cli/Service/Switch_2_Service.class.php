<?php
/*
 * H3C E152B操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_2_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=2;
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
        if(strpos($str,'save current configuration')!==false){
            $this->switch->command('Y');
            $str='';
            $c=30;
            do{
                $str.=$this->switch->getBuffer();
            }while(strpos($str,'[Y/N]')!==false&&$c-->0);
            echo $str,PHP_EOL;
        }
        if($c>0){
            $this->switch->command('Y');
            return ['code'=>1];
        }else{
            return ['code'=>2];
        }
    }

    /**
     * 获取交换机启动时间,负载,在线用户量
     * @return array
     */
    public function getInfo(){
        $result=array();
        if(preg_match('/uptime is (.*?)\\r\\n/',$this->switch->exec('display version'),$match)){
            $result['uptime']=$match[1];
        }
        if(preg_match('/(\d+)% in last 5 seconds/',$this->switch->exec('display cpu'),$match)){
            $result['cpu']=intval($match[1]);
        }
        $data=$this->switch->exec('display connection');
        if(preg_match('/Total (\d+) connection/',$data,$match)){
            $result['online_list_count']=intval($match[1]);
        }
        if(preg_match_all('/(\w+)@system\\r\\n.*?((\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})|(N\/A))[\w\W]*?(\w{4}\-\w{4}\-\w{4})/',$data,$match)){
            $result['online_list']=array(
                $match[1],
                $match[5],
                $match[2]
            );
        }
        $result['version']=D('Device')->getVersion(ip2long($this->switch->getIp()));
        return $result;
    }

    /**
     * 获取端口概况
     * @return mixed
     */
    public function getBrief(){
        $data=$this->switch->exec('display interface brief');
        if(preg_match_all('/((GE|Eth)\d\/0\/\d{1,2})\s+(UP|DOWN).*?(A|T)/',$data,$result)){
            array_shift($result);
            array_splice($result,1,1);
            F('Interface_'.$this->switch->getIp(),$result[0]);
            return ['no'=>1,'res'=>$result];
        }else{
            return ['no'=>2];
        }
    }
}