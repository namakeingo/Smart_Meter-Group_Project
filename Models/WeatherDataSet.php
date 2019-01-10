<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 10/01/2019
 * Time: 13:33
 */
require_once ('WeatherData.php');
class WeatherDataSet {

    private $weatherArray;

    public function __construct($string)
    {
        // decodes the json string gotten by the constructor and turns it into an associative array
        $tempArray  = json_decode($string, true);
        //forecast is stored in $tempArray['weather'][0]['main']
        // description of the forecast is stored in $tempArray['weather'][0]['description']
        // temperature of the day is in $tempArray['main']['temp']
        // maximum temperature for the day is in $tempArray['main']['temp_max']
        // minimum temperature for the day is in $tempArray['main']['temp_min']
        // country code is in $tempArray['sys']['country']
        $this->weatherArray[] = new WeatherData($tempArray['weather'][0]['main'],$tempArray['weather'][0]['description'],
            $tempArray['main']['temp'],$tempArray['main']['temp_max'],
            $tempArray['main']['temp_min'], $tempArray['sys']['country']);
    }

    /**
     * @return mixed
     */
    public function getWeatherArray()
    {
        return $this->weatherArray;
    }
}