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
                (new DateTime())->setTimestamp($tempArray['dt']));
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
                    new DateTime($value['dt_txt']));
            }
        }
    }

    // gets the predicted weather for the next 5 days
    public function getPredictedWeather() {
        if(count($this->weatherArray) > 1) {
            // creates a new weather array
            // All values needed for a weather data object are initialised here
            $predictedWeatherArray = [];
            $tempMin = 0;
            $tempMax = 0;
            $temp = 0;
            $time = $this->weatherArray[0]->getTime();
            $forecast = $this->weatherArray[0]->getForecast();
            $description = $this->weatherArray[0]->getDescription();
            $count = 0;
            $today = intval((new DateTime('now', new DateTimeZone('Europe/London')))->format('d'));
            $day = intval($this->weatherArray[0]->getTime()->format('d'));
            foreach ($this->weatherArray as $value) {
                if (!($day == $today)) {
                    // day comparision is made to see if the day for the weather is the same
                    if ($day == intval($value->getTime()->format('d'))) {
                        $tempMin += $value->getTempMin();
                        $tempMax += $value->getTempMax();
                        $temp += $value->getTemp();
                        $count++;
                    } else {
                        // if day changes average is calculated and the array is updated with a new weather object
                        $temp = $temp / $count;
                        $tempMin = $tempMin / $count;
                        $tempMax = $tempMax / $count;
                        $predictedWeatherArray[] = new WeatherData($forecast, $description, ((($temp - 32) * 5 / 9) + 273.15),
                            ((($tempMax - 32) * 5 / 9) + 273.15), ((($tempMin - 32) * 5 / 9) + 273.15), '', $time);
                        // all values are reset and updated for the next day
                        $forecast = $value->getForecast();
                        $description = $value->getDescription();
                        $time = $value->getTime();
                        $temp = $tempMin = $tempMax = $count = 0;
                        $tempMin += $value->getTempMin();
                        $tempMax += $value->getTempMax();
                        $temp += $value->getTemp();
                        $count++;
                    }
                }
                // day is updated for the next item in the array
                $day = intval($value->getTime()->format('d'));
                $time = $value->getTime();
            }
            return $predictedWeatherArray;
        }
    }

    // used to create a usable array for k nn to make a prediction
    // k nn array format is as follows:
    // position 0 Max temperature for the day
    // position 1 Avg temperature for the day
    // position 2 Min temperature for the day
    public function createTestWeatherArray() {
        $predictedWeather = $this->getPredictedWeather();
        $testWeatherArray = [];
        foreach ($predictedWeather as $value) {
            $testWeatherArray[] = [$value->getTempMax(), $value->getTemp(), $value->getTempMin()];
        }
        return $testWeatherArray;
    }
}