<?php

//require_once('Models/ConnectionConsumption.php');
$view = new stdClass();
$view->pageTitle = 'Comparison';

////!!!! COMPARISON DATA MOVED TO /Request.php
//function getConsumption($type,$time, $to){
//    //This gets the total consumption of a type (GAS or ELEC) of one day
//    $connectionConsumption = new ConnectionConsumption($type,'P1D', $time,$to);
//    $consumptionDataSet = $connectionConsumption->getData();             //This hold a ConsumptionDataSet object
//    $totalConsumption = $consumptionDataSet->getTotalConsumption();      //This is the total consumption of electricity in an hour
//    return $totalConsumption;
//}
//
//
//$view->gasYesterday = getConsumption('GAS', 'yesterday', 'today');
//$view->elecYesterday = getConsumption('ELEC', 'yesterday', 'today ') - getConsumption('ELEC', 'today', 'today');
//$view->yesterdayConsumption = $view->gasYesterday+$view->elecYesterday;
//
//$view->gasToday = getConsumption('GAS', 'today', 'now');
//$view->elecToday = getConsumption('ELEC', 'today', 'now');
//$view->todayConsumption = $view->gasToday+$view->elecToday;
//
//$view->gasDifference = $view->gasYesterday - $view->gasToday;
//$view->electricDifference = $view->elecYesterday - $view->elecToday;
//$view->consumptionDifference = $view->yesterdayConsumption - $view->todayConsumption;
//
//if ($view->consumptionDifference > 0) {
//    $view->todayIsBetter = true;
//} else {
//    $view->todayIsBetter = false;
//}

$view->type='BOTH';
require_once ('Views/comparison.phtml');
?>