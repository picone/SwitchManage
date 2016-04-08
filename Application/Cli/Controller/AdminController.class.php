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

    public function getVersion(){
        $fp=fopen(DATA_PATH.'ip_list.txt','r');
        if(!$fp)exit('请先生成IP列表'.PHP_EOL);
        while(!feof($fp)){
            $ip=str_replace("\n",'',fgets($fp));
            $telnet=new TelnetModel($ip,C('TELNET_PASSWORD'));
            $telnet->connect();
            $res=$telnet->exec('dis version');
            if(preg_match('/\\r\\n(.*?) uptime/',$res,$match)){
                try{
                    $data=D('DeviceVersion')->fetchId($match[1]);
                    if($data){
                        $id=$data['id'];
                    }else{
                        $id=D('DeviceVersion')->insert($match[1]);
                    }
                    D('Device')->updateVersion(ip2long($ip),$id);
                }catch(\Exception $e){
                    echo $e->getMessage(),PHP_EOL;
                }
                echo $ip,':',$match[1],PHP_EOL;
            }else{//取出失败
                echo '获取',$ip,'型号失败',PHP_EOL;
            }
        }
    }

    public function addIp(){
        $a=14;
        $b=24;
        for($i=1;$i<=$b;$i++){
            // 'ip','position_id','version_id','name','val','update_time'
            $ip='172.16.1'.(20+$a).'.'.$i;
            D('Device')->add(array(
                'ip'=>ip2long($ip),
                'position_id'=>$a+14,
                'version_id'=>0,
                'name'=>$ip
            ));
        }
    }
}