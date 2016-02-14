<?php
/**
 * Created by PhpStorm.
 * User: chien
 * Date: 16-2-14
 * Time: 上午10:02
 */
namespace Cli\Model;
class FpingModel{
    /**
     * 生成IP列表
     * @param array $list IP列表
     * @param string $path 生成路径
     */
    public function generateIp($list,$path){
        $fp=fopen($path,'w');
        foreach($list as &$val){
            fwrite($fp,long2ip($val).PHP_EOL);
        }
        fclose($fp);
    }

    /**
     * 调用fping
     * @param string $path ip列表
     * @return array|int
     */
    public function fping($path){
        if(!file_exists($path))return -1;
        exec('fping -ef '.$path,$result,$status);
        if($status==0){
            $res=array();
            foreach($result as &$val){
                if(preg_match('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}) is (alive|unreachable)(.*?(\d{1,3}\.\d{1,2}))?/',$val,$tmp)){
                    if($tmp[2]=='unreachable'){
                        $res[$tmp[1]]=-1;
                    }else if($tmp[2]=='alive'){
                        $res[$tmp[1]]=floatval($tmp[4]);
                    }
                }
            }
            return $res;
        }else{
            return $status;
        }
    }
}