<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 10/01/2019
 * Time: 13:33
 */

class WeatherData {

    private $forecast;
    private $description;
    private $temp;
    private $tempMin;
    private $tempMax;
    private $country;

    public function __construct($forecast, $description, $temp, $tempMax, $tempMin, $country)
    {
        // basic fields of the class are set to their values
        $this->forecast = $forecast;
        $this->description = $description;
        $this->temp = $temp - 273.15;
        $this->tempMax = $tempMax - 273.15;
        $this->tempMin = $tempMin - 273.15;
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getForecast()
    {
        return $this->forecast;
    }

    /**
     * @return mixed
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @return mixed
     */
    public function getTempMax()
    {
        return $this->tempMax;
    }

    /**
     * @return mixed
     */
    public function getTempMin()
    {
        return $this->tempMin;
    }
}