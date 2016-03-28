<?php
/*
 * Quidway S3026E操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_6_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=6;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }
}