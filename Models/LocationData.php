<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 10/01/2019
 * Time: 23:03
 */
class LocationData
{
    private $country;
    private $city;

    public function __construct($string)
    {
        $tempArray = json_decode($string, true);
        $this->country = $tempArray['country_name'];
        $this->city = $tempArray['city'];
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

}