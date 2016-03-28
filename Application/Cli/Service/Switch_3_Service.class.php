<?php
/*
 * Quidway E050操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_3_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=3;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }
}