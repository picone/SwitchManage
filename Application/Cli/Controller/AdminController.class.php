<?php
namespace Cli\Controller;
use Cli\Model\TelnetModel;

class AdminController extends \Think\Controller{
    public function generate_ip(){
        $data=D('Device')->fetchAll();
        $filename=DATA_PATH.'ip_list.txt';
        array_walk($data,function(&$val){
            $val=$val['ip'];
        });
        D('Fping')->generateIp($data,$filename);
        echo readfile($filename);
    }

    public function fping(){
        $res=D('Fping')->fping(DATA_PATH.'ip_list.txt');
        if(is_array($res)){
            D('Device')->startTrans();
            foreach($res as $key=>&$val){
                if($val>=0){
                    echo $key,':',$val,'ms',PHP_EOL;
                }else{
                    echo $key,':unreachable',PHP_EOL;
                }
                D('Device')->setVal($key,$val);
            }
            D('Device')->commit();
        }else if($res==-1){
            echo '请先生成IP列表',PHP_EOL;
        }else{
            echo '错误:',$res,PHP_EOL;
        }
    }

    public function test(){
        $t=new TelnetModel('172.16.121.1','');
        $t->connect();
        $t->send('dis cur'."\r");
        $res='';
        $c=0;
        do{
            $data=$t->getBuffer();
            echo $data;
            $c++;
        }while($data!=''&&$c<100);
        echo $res,PHP_EOL;
    }
}