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

    public function __construct($string, $type)
    {
        // decodes the json string gotten by the constructor and turns it into an associative array
        $this->setWeatherArray(json_decode($string, true), $type);


    }

    /**
     * @return mixed
     */
    public function getWeatherArray()
    {
        return $this->weatherArray;
    }

    public function setWeatherArray($tempArray, $type) {
        if ($type == 'weather') {
            //forecast is stored in $tempArray['weather'][0]['main']
            // description of the forecast is stored in $tempArray['weather'][0]['description']
            // temperature of the day is in $tempArray['main']['temp']
            // maximum temperature for the day is in $tempArray['main']['temp_max']
            // minimum temperature for the day is in $tempArray['main']['temp_min']
            // country code is in $tempArray['sys']['country']
            // time is in $tempArray['dt'] which is a timestamp converted to a date
            //
            $this->weatherArray[] = new WeatherData($tempArray['weather'][0]['main'], $tempArray['weather'][0]['description'],
                $tempArray['main']['temp'], $tempArray['main']['temp_max'],
                $tempArray['main']['temp_min'], $tempArray['sys']['country'],
                intval((new DateTime())->setTimestamp($tempArray['dt'])->format('H')));
        }
        elseif ($type == 'forecast') {
            $city = $tempArray['city']['name'];
            $tempArray = $tempArray['list'];
            //forecast is stored in $value['weather'][0]['main']
            // description of the forecast is stored in $value['weather'][0]['description']
            // temperature of the day is in $value['main']['temp']
            // maximum temperature for the day is in $value['main']['temp_max']
            // minimum temperature for the day is in $value['main']['temp_min']
            // city is stored in a variable called $city
            // time is in $value['dt'] which is a timestamp converted to a date
            foreach ($tempArray as $value) {
                $this->weatherArray[] = new WeatherData($value['weather'][0]['main'], $value['weather'][0]['description'],
                    $value['main']['temp'], $value['main']['temp_max'],
                    $value['main']['temp_min'], $city,
                    intval((new DateTime($value['dt_txt']))->format('H')));
            }
        }
    }
}