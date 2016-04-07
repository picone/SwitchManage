<?php
/*
 * 交换机操作模块,结合服务
 */
namespace Cli\Event;
use Cli\Model\TelnetModel;

class TelnetEvent{
    
    private static $instance;
    /**
     * 获取交换机操作服务
     * @param int $ip 整型交换机IP
     * @return null
     */
    public static function getService($ip){
        if(isset(self::$instance[$ip])){
            return self::$instance[$ip];
        }else{
            $switch=new TelnetModel($ip,C('TELNET_PASSWORD'));
            $version=D('Device')->getVersionId($ip);
            $service='\Cli\Service\Switch_'.$version.'_Service';
            if(class_exists($service)){
                self::$instance[$ip]=new $service($switch);
            }else{
                self::$instance[$ip]=null;
            }
            return self::$instance[$ip];
        }
    }
}