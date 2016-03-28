<?php
/*
 * H3C E352操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_7_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=7;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }
}