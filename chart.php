<?php
require_once('Models/ConnectionConsumption.php');

$view = new stdClass();
$view->pageTitle = 'Electricity';


$view->type='BOTH';

//var_dump($view->kwhElec);
//var_dump($view->kwhGas);
//die();

require_once('Views/chart.phtml');