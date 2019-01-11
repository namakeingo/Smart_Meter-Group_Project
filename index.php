<?php
require_once('Models/ConnectionConsumption.php');
require_once('Models/ConnectionWeather.php');
require_once ('Models/ConnectionLocation.php');
$view = new stdClass();
$view->pageTitle = 'Homepage';
$servername = "group2.edu.csesalford.com";
$username = "group2";
$password = "SWLWmTppbPUQpzX";
$db = "group2";

$conn = new PDO("mysql:host=$servername;dbname=$db",$username,$password);
$data = $conn->prepare("SELECT * FROM budget");
$data->execute();


$elecConsum = new ConnectionConsumption('ELEC','PT1H');
$elecDataSet = $elecConsum->getData();

$gasConsum = new ConnectionConsumption('GAS','PT1H');
$gasDataSet = $gasConsum->getData();

$view->totalElec = $elecDataSet->getElecCost();
$view->totalGas = $gasDataSet->getGasCost();

$url = new ConnectionLocation();
$location = $url->getData();
//changes the url for the weather api
$url = new ConnectionWeather('forecast', $location->getCountry());
// weather for 5 days in the future
$view->weatherPredictionSet = $url->getData('forecast');
$url = new ConnectionWeather('weather',$location->getCountry());
// weather for the current time of access to the app
$view->weatherNow = ($url->getData('weather'))->getWeatherArray()[0];

require_once ('Views/index.phtml');
