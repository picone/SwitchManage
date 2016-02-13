<?php
namespace Cli\Controller;
class AdminController extends \Think\Controller{
    public function generate_ip(){
        $data=D('Device')->fetchAll();
        $filename=DATA_PATH.'ip_list.txt';
        $fp=fopen($filename,'w');
        foreach($data as &$val){
            fwrite($fp,long2ip($val['ip']).PHP_EOL);
        }
        fclose($fp);
        echo readfile($filename);
    }

    public function fping(){
        $filename=DATA_PATH.'ip_list.txt';
        if(file_exists($filename)){
            exec('/usr/bin/fping -eaqf '.$filename,$result,$status);
            if($status==0){
                print_r($result);
            }else{
                echo '错误:',$status,PHP_EOL;
            }
        }else{
            echo '请先生成IP列表',PHP_EOL;
        }
    }
}