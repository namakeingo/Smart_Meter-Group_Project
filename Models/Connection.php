<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 10/01/2019
 * Time: 13:50
 */

class Connection
{
    //the base url for the api
    private $urlBase;
    // the curl client
    private static $curl;

    public function __construct($urlBase)
    {
        $this->urlBase = $urlBase;
        // sets the curl client only once
        if (self::$curl == null) {
            self::setCurl();
        }
    }

    /**
     * @param mixed $curl
     */
    public static function setCurl()
    {
        // initialises the curl client
        self::$curl = curl_init();
    }

    public function executeCurl() {
        // returns the output of the curl request
        return curl_exec(self::$curl);
    }

    public function setOptCurl(array $options) {
        // sets the operations of the curl client
        curl_setopt(self::$curl, CURLOPT_URL, $this->urlBase);
        foreach ($options as $key=>$value) {
            curl_setopt(self::$curl, $key, $value);
        }
    }
}