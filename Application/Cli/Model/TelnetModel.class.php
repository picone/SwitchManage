<?php
/**
 * Created by PhpStorm.
 * User: chien
 * Date: 16-2-15
 * Time: 上午00:07
 */
namespace Cli\Model;
class TelnetModel{
    private $ip;
    private $username;
    private $password;
    private $socket;

    public function __construct($ip,$username,$password){
        $this->ip=$ip;
        $this->username=$username;
        $this->password=$password;
    }

    public function __destruct(){

    }

    public function checkConnect(){

    }

    public function connect(){

    }

    public function disconnect(){

    }

    public function login(){

    }

    public function execute(){

    }
}