<?php
require_once('Models/ConnectionConsumption.php');
//require_once ('Models/Prediction.php');
//require_once('Models/ConnectionWeather.php');
//require_once ('Models/ConnectionLocation.php');
require_once ('Models/BudgetDb.php');

session_start();
$view = new stdClass();
$view->pageTitle = 'Homepage';
$view->type='BOTH';

$elecConsum = new ConnectionConsumption('ELEC','PT1H', 'first day of this month 00:00:00', 'now');
$elecDataSet = $elecConsum->getData();

$gasConsum = new ConnectionConsumption('GAS','PT1H', 'first day of this month 00:00:00','now');
$gasDataSet = $gasConsum->getData();

$view->totalElec = $elecDataSet->getElecCost();
$view->totalGas = $gasDataSet->getGasCost();

////!!!! WEATHER MOVED TO   /Models/Request.php
//$url = new ConnectionLocation();
//$location = $url->getData();
////changes the url for the weather api
//$url = new ConnectionWeather('forecast', 'Salford');
//// weather for 5 days in the future
//$view->weatherPredictionSet = ($url->getData('forecast'))->getPredictedWeather();
//// predicted weather converted to a usable array for k nn
//$testWeatherArray = ($url->getData('forecast'))->createTestWeatherArray();
//$url = new ConnectionWeather('weather','Salford');
//// weather for the current time of access to the app
//$view->weatherNow = ($url->getData('weather'))->getWeatherArray()[0];
//
//// classifier initialised
//$prediction = new Prediction(7);
//// classifier trained on the training data set
//$prediction->train('Elec');
//// predicted usage for the 5 days saved for the view
//$view->predictedUsageElec = [];
//$count = 0;
//foreach ($testWeatherArray as $value) {
//    $view->predictedUsageElec[] = new ConsumptionData($view->weatherPredictionSet[$count]->getTime()->getTimeStamp(),
//        floatval($prediction->predict(array($value[0], $value[1], $value[2]))));
//}
//$prediction->train('Gas');
//// predicted usage for the 5 days saved for the view
//$view->predictedUsageGas = [];
//$count = 0;
//foreach ($testWeatherArray as $value) {
//    $view->predictedUsageGas[] = new ConsumptionData($view->weatherPredictionSet[$count]->getTime()->getTimeStamp(),
//        floatval($prediction->predict(array($value[0], $value[1], $value[2]))));
//    $count++;
//}


// Doughnut Chart and colour classes



$budgetConnection = new BudgetDb();
//$view->setBudget;

if ($budgetConnection->getBudget('group2@hotmail.com')['electricityPrice'] > 0){
    $view->elecBudget = $budgetConnection->getBudget('group2@hotmail.com')['electricityPrice'];
}
if ($budgetConnection->getBudget('group2@hotmail.com')['gasPrice'] > 0) {
    $view->gasBudget = $budgetConnection->getBudget('group2@hotmail.com')['gasPrice'];
}

$colour= [];

if(isset($view->elecBudget)){
    if($view->totalElec < $view->elecBudget/2) {
        $colour[0] = 'green';
        $view->colourElectric = 'color-green';
    }elseif($view->totalElec < ($view->elecBudget*4)/5){
        $colour[0] = 'gold';
        $view->colourElectric = 'color-gold';
    }else {
        $colour[0] = 'red';
        $view->colourElectric = 'color-red';
    }
}
if(isset($view->elecBudget)) {
    if ($view->totalGas < $view->gasBudget / 2) {
        $colour[1] = 'green';
        $view->colourGas = 'color-green';

    } elseif ($view->totalGas < ($view->gasBudget * 4) / 5) {
        $colour[1] = 'gold';
        $view->colourGas = 'color-gold';

    } else {
        $colour[1] = 'red';
        $view->colourGas = 'color-red';
    }
}
require_once ('Views/index.phtml');
