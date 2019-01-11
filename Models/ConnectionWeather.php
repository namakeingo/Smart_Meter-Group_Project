<?php
require_once('Connection.php');
require_once ('WeatherDataSet.php');
class ConnectionWeather extends Connection {

    // the country the smart meter is
    private $country;

    public function __construct($type, $country)
    {
        // sets the city id
        $this->country = $country;
        // sets the url base to the url needed
        // type can hold two values either weather(now) or forecast(5 days ahead)
        parent::__construct('https://api.openweathermap.org/data/2.5/'. $type
            . '?q='. $this->country .'&appid=50fcfcdd4d1147cbec838e629fe6b3bf');
        // sets the operations for the curl client
        parent::setOptCurl(array());
    }

    // type is to differentiate between a forecast for 5 days and a forecast for the current moment
    public function getData($type) {
        // gets the output of the curl request as a weather data set
        return new WeatherDataSet(parent::executeCurl(), $type);
    }

}
