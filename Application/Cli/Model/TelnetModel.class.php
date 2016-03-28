<?php
/**
 * Created by PhpStorm.
 * User: chien
 * Date: 16-2-15
 * Time: 上午00:07
 */
namespace Cli\Model;

define('TEL_SE',chr(0xF0));
define('TEL_SB',chr(0xFA));
define('TEL_WILL',chr(0xFB));
define('TEL_DO',chr(0xFD));
define('TEL_IAC',chr(0xFF));

define('TELOPT_ECHO',chr(0x01));
define('TELOPT_GO_AHEAD',chr(0x03));
define('TELOPT_STATUS',chr(0x05));
define('TELOPT_TERMINAL_TYPE',chr(0x18));
define('TELOPT_NAWS',chr(0x1F));

class TelnetModel{
    const SLEEP_TIME=10000;

    private $ip;
    private $password;
    private $socket=null;
    private $err_no;
    private $err_msg;
    public $device_name;
    public $cur_view=null;

    public function __construct($ip,$password){
        if(strpos($ip,'.')>0){
            $this->ip=$ip;
        }else{
            $this->ip=long2ip($ip);
        }
        $this->password=$password;
        $this->connect();
    }

    public function isConnect(){
        if($this->socket==null)return false;
        if($this->cur_view!=null){
            fputs($this->socket,"\r");
            if(strpos($this->getBuffer(),$this->device_name)===false){
                return false;
            }
        }
        return true;
    }

    /**
     * 连接并登录交换机
     * @return int 0:成功,1:密码错误,2:登录超时,3:未知错误
     */
    public function connect(){
        $retry=0;
        while(!$this->isConnect()&&$retry<=3){
            $this->socket=fsockopen($this->ip,23,$this->err_no,$this->err_msg,20);
            fputs($this->socket,
                TEL_IAC.TEL_DO.TELOPT_GO_AHEAD/*.
                TEL_IAC.TEL_WILL.TELOPT_NAWS//协商窗口大小,交换机请求不要协商,就不去协商了*/
            );//发送连接命令
            $counter=0;
            do{
                $data=$this->getBuffer();
                $cmd='';
                if(strpos($data,TEL_IAC.TEL_WILL.TELOPT_ECHO)!==false){//接收输出
                    fputs($this->socket,TEL_IAC.TEL_DO.TELOPT_ECHO);
                }else if(strpos($data,TEL_IAC.TEL_DO.TELOPT_TERMINAL_TYPE)!==false){//协商终端类型
                    $cmd.=TEL_IAC.TEL_SB.TELOPT_TERMINAL_TYPE.
                        //chr(0x00).chr(0x78).chr(0x74).chr(0x65).chr(0x72).chr(0x6D).chr(0x2D).chr(0x32).chr(0x35).chr(0x36).chr(0x63).chr(0x6F).chr(0x6C).chr(0x6F).chr(0x72).//xterm-256color
                        chr(0x01).//连接后交换机会请求terminal_type设置为01,干脆协商就为01好了
                        TEL_IAC.TEL_SE;
                }else if(strpos($data,TEL_IAC.TEL_DO.TELOPT_NAWS)!==false){//协商窗口大小
                    $cmd.=TEL_IAC.TEL_SB.TELOPT_NAWS.
                        chr(0x00).chr(0x50).chr(0x00).chr(0x18).//大小80*24
                        TEL_IAC.TEL_SE;
                }else if(strpos($data,'Password:')!==false){//需要密码
                    $cmd=$this->password."\r";
                }else if(strpos($data,'<')!==false){
                    if(preg_match('/<(.+?)>/',$data,$match)){
                        $this->device_name=$match[1];
                    }
                    $this->cur_view='comm';
                    return 0;
                }else if(strpos($data,'%Wrong password!')!==false){
                    return 1;
                }else if(strpos($data,'timeout expired')!==false){
                    return 2;
                }
                if($cmd!='')fputs($this->socket,$cmd);
                $counter++;
            }while($data!=''&&$counter<20);
        }
        return 3;
    }

    public function getBuffer(){
        $result='';
        $c=0;
        do{
            $result.=fread($this->socket,1024);
            $status=socket_get_status($this->socket);
            $c++;
        }while($status['unread_bytes']>0&&$c<256);
        return $result;
    }

    public function exec($cmd){
        $result='';
        $c=0;
        fputs($this->socket,$cmd."\r");
        do{
            $data=$this->getBuffer();
            $result.=$data;
            if(strpos($data,'---- More ----')!==false){
                fputs($this->socket,chr(32));
            }else if(strpos($data,'<'.$this->device_name.'>')!==false||strpos($data,'['.$this->device_name.']')!==false){
                break;
            }
            $c++;
        }while($c<300);
        return $result;
    }

    public function __destruct(){
        fclose($this->socket);
    }
}