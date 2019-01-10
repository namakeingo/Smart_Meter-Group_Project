<?php
require_once('Models/ConnectionConsumption.php');
require_once ('Models/ConnectionWeather');
$view = new stdClass();
$view->pageTitle = 'Homepage';

$elecConsum = new ConnectionConsumption('ELEC','PT1H');
$elecDataSet = $elecConsum->getData();

$gasConsum = new ConnectionConsumption('GAS','PT1H');
$gasDataSet = $gasConsum->getData();

$view->totalElec = $elecDataSet->getElecCost();
$view->totalGas = $gasDataSet->getGasCost();

//changes the url for the weather api
$url = new ConnectionWeather('forecast');
// weather for 5 days in the future
$view->weatherPredictionSet = $url->getData('forecast');
$url = new ConnectionWeather('weather');
// weather for the current time of access to the app
$view->weatherNow = $url->getData('weather');

require_once ('Views/index.phtml');