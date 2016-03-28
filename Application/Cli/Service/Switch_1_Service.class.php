<?php
/*
 * Quidway S3050操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_1_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=1;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }
}