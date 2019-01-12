<?php

require_once('Models/ConnectionConsumption.php');
$view = new stdClass();
$view->pageTitle = 'Consumption Comparison';

function getConsumption($type,$time){
    //This gets the total consumption of a type (GAS or ELEC) of one day
    $connectionConsumption = new ConnectionConsumption($type,'P1D', $time,'now');
    $consumptionDataSet = $connectionConsumption->getData();             //This hold a ConsumptionDataSet object
    $totalConsumption = $consumptionDataSet->getTotalConsumption();      //This is the total consumption of electricity in an hour
    return $totalConsumption;
}

$gasYesterday = getConsumption('GAS', 'yesterday');
$elecYesterday = getConsumption('ELEC', 'yesterday');
$view->yesterdayConsumption = $gasYesterday+$elecYesterday;

$gasToday = getConsumption('GAS', 'today');
$elecToday = getConsumption('ELEC', 'today');
$view->todayConsumption = $gasToday+$elecToday;

require_once ('Views/comparison.phtml');
?>