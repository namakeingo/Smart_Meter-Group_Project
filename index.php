<?php
require_once('Models/ConnectionConsumption.php');
require_once('Models/ConnectionWeather.php');
require_once ('Models/ConnectionLocation.php');
require_once __DIR__ . '/vendor/autoload.php';
use \Phpml\Classification\KNearestNeighbors;
$view = new stdClass();
$view->pageTitle = 'Homepage';

$elecConsum = new ConnectionConsumption('ELEC','PT1H', 'first day of this month 00:00:00', 'now');
$elecDataSet = $elecConsum->getData();

$gasConsum = new ConnectionConsumption('GAS','PT1H', 'first day of this month 00:00:00','now');
$gasDataSet = $gasConsum->getData();

$view->totalElec = $elecDataSet->getElecCost();
$view->totalGas = $gasDataSet->getGasCost();

$url = new ConnectionLocation();
$location = $url->getData();
//changes the url for the weather api
$url = new ConnectionWeather('forecast', 'London');
// weather for 5 days in the future
$view->weatherPredictionSet = ($url->getData('forecast'))->getWeatherArray();
$url = new ConnectionWeather('weather','London');
// weather for the current time of access to the app
$view->weatherNow = ($url->getData('weather'))->getWeatherArray()[0];

$classifier = new KNearestNeighbors();
$samples = [];
$labels = [];
$lines = file('weatherData.txt');
foreach ($lines as $line) {
    $data = preg_split("/[, | \n]/",$line);
    $samples[] = [intval($data[0]),intval($data[1]), intval($data[2])];
}
$lines = file('consumption.txt');
foreach ($lines as $line) {
    $line = trim($line);
    $labels[] = $line;
}
$classifier->train($samples, $labels);
$classifier->predict(array($view->weatherPredictionSet[0]->getTempMax(),$view->weatherPredictionSet[0]->getTemp(),
    $view->weatherPredictionSet[0]->getTempMin()));
require_once ('Views/index.phtml');
