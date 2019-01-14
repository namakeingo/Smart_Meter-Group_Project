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
    private $time;
    // day night variable stores the day night status of a weather data object
    private $dayNight;

    public function __construct($forecast, $description, $temp, $tempMax, $tempMin, $country, $time)
    {
        // basic fields of the class are set to their values
        $this->forecast = $forecast;
        $this->description = $description;
        $this->temp = (($temp - 273.15) * 9/5)+32;
        $this->tempMax = (($tempMax - 273.15) * 9/5) + 32;
        $this->tempMin = (($tempMin - 273.15) * 9/5) + 32;
        $this->country = $country;
        $this->time = $time;
        $this->setNightOrDay($time);
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

    // Assumes midnight to 7 am is night and onwards to the rest of the day is the day
    public function setNightOrDay($time) {
        if ($time->format('H') < 7) {
           $this->dayNight = 'Night';
        }
        else {
           $this->dayNight = 'Day';
        }
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    public function convertToCelcius() {
        $this->temp = (($this->temp - 32) * 5/9);
        $this->tempMax = (($this->tempMax - 32) * 5/9);
        $this->tempMin = (($this->tempMin - 32) * 5/9);
    }
}