<?php
namespace Common\Library\Vendor;
/**
 * Created by PhpStorm.
 * User: chien
 * Date: 16-5-20
 * Time: 上午1:57
 */
class Ping{
    public static function ping($ip){
        exec('ping -c1 -w1000 '.long2ip($ip),$result,$status);//转换一次整型,防止恶意攻击
        $result=implode("\n",$result);
        if(preg_match('/time\=([\d\.]+) ms/',$result,$match)){
            return $match[1];
        }else{
            return -1;
        }
    }
}