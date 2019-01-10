<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 10/01/2019
 * Time: 22:57
 */
require_once ('Connection.php');
require_once ('LocationData.php');
class ConnectionLocation extends Connection
{
    private $ipAddress;

    public function __construct()
    {
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
        $this->ipAddress = '134.201.250.155';
        parent::__construct('http://api.ipstack.com/'. $this->ipAddress
            . '?access_key=d4161d78d676a0dfff2d0f071efaba3c');
        parent::setOptCurl(array(CURLOPT_RETURNTRANSFER => 1));
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function getData() {
        return new LocationData(parent::executeCurl());
    }


}