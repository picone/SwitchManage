<?php
namespace Cli\Service;
use Cli\Model\TelnetModel;
abstract class SwitchBaseService{
    
    protected $version_id;//交换机型号ID
    protected $interface;//接口列表
    protected $switch;

    public function __construct(TelnetModel $switch){
        $this->switch=$switch;
        $this->switch->connect();
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
        if(preg_match_all('/(\w+)@system\\r\\n.*?(\w{4}\-\w{4}\-\w{4}).*?(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/',$data,$match)){
            array_shift($match);
            $result['online_list']=$match;
        }
        return $result;
    }

    /**
     * 重启交换机
     */
    public abstract function reboot();

    /**
     * 获取端口详细信息
     * @param $interface
     */
    public function getInt($interface){
        if($interface==null){
            $data=$this->exec('display interface');
        }else{
            $data=$this->exec('display interface '.$interface);
        }
    }

    /**
     * 取消端口关闭
     * @param $interface
     */
    public function unShutdown($interface){
        $this->enterView($interface);
        $data=$this->switch->exec('undo shutdown');
    }

    /**
     * 为端口增加dot1x认证
     * @param $interface
     */
    public function dot1x($interface){
        $this->enterView($interface);
        $data=$this->switch->exec('dot1x');
    }

    /**
     * 执行命令
     * @param $command_id
     * @return mixed
     */
    public function exec($command_id){
        $this->switch->connect();
        $func=D('Command')->getKey($command_id);
        if(method_exists($this,$func)){
            return $this->$func();
        }else{
            return null;
        }
    }

    /**
     * 切换视图
     * @param string $view 视图名称
     */
    protected function enterView($view){
        //$this->switch->
        if($view!==$this->switch->cur_view){
            switch($view){
                case 'comm':{//进入普通视图
                    $this->switch->exec('quit');
                    if($this->switch->cur_view!='sys'){
                        $this->switch->exec('quit');
                    }
                    break;
                }
                case 'sys':{//进入系统视图
                    if($this->switch->cur_view=='comm'){
                        $this->switch->exec('system-view');
                    }else{
                        $this->switch->exec('quit');
                    }
                    break;
                }
                default:{//进入特定端口视图
                    if($this->switch->cur_view=='comm'){
                        $this->switch->exec('system-view');
                    }
                    $this->switch->exec('interface '.$view);
                }
            }
        }
    }
}