<?php
require_once('Models/ConnectionConsumption.php');
require_once ('Models/Prediction.php');
require_once('Models/ConnectionWeather.php');
require_once ('Models/ConnectionLocation.php');
session_start();
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
$view->weatherPredictionSet = ($url->getData('forecast'))->getPredictedWeather();
// predicted weather converted to a usable array for k nn
$testWeatherArray = ($url->getData('forecast'))->createTestWeatherArray();
$url = new ConnectionWeather('weather','London');
// weather for the current time of access to the app
$view->weatherNow = ($url->getData('weather'))->getWeatherArray()[0];

// classifier initialised
$prediction = new Prediction(2);
// classifier trained on the training data set
$prediction->train('Elec');
// predicted usage for the 5 days saved for the view
$view->predictedUsageElec = [];
$count = 0;
foreach ($testWeatherArray as $value) {
    $view->predictedUsageElec[] = new ConsumptionData($view->weatherPredictionSet[$count]->getTime()->getTimeStamp(),
        floatval($prediction->predict(array($value[0], $value[1], $value[2]))));
}
$prediction->train('Gas');
// predicted usage for the 5 days saved for the view
$view->predictedUsageGas = [];
$count = 0;
foreach ($testWeatherArray as $value) {
    $view->predictedUsageGas[] = new ConsumptionData($view->weatherPredictionSet[$count]->getTime()->getTimeStamp(),
        floatval($prediction->predict(array($value[0], $value[1], $value[2]))));
    $count++;
}


// Doughnut Chart

$colour= [];

if($view->totalElec < 40/2) {
    $colour[0] = 'green';
}elseif($view->totalElec < (40*4)/5){
    $colour[0] = 'gold';
}else {
    $colour[0] = 'red';
}

if($view->totalGas < 40/2) {
    $colour[1]= 'green';

}elseif($view->totalGas < (40*4)/5){
    $colour[1]= 'gold';

}else{
    $colour[1]= 'red';
}
require_once ('Views/index.phtml');
