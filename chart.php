<?php
require_once('Models/ConnectionConsumption.php');

$view = new stdClass();
$view->pageTitle = 'Chart';


$elecConsum = new ConnectionConsumption('ELEC','PT1H', 'today midnight');
$elecDataSet = $elecConsum->getData();

$gasConsum = new ConnectionConsumption('GAS','PT1H', 'today midnight');
$gasDataSet = $gasConsum->getData();

$view->kwhElec = $elecDataSet->getConsumptionArray();
$view->kwhGas = $gasDataSet->getConsumptionArray();
//var_dump($view->kwhElec);
//var_dump($view->kwhGas);
//die();

require_once('Views/chart.phtml');