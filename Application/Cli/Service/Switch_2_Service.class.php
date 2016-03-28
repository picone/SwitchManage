<?php
/*
 * H3C E152B操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_2_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=2;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }
}