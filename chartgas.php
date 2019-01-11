<?php
require_once('Models/ConnectionConsumption.php');

$view = new stdClass();
$view->pageTitle = 'Gas';


$view->type='GAS';

//var_dump($view->kwhElec);
//var_dump($view->kwhGas);
//die();

require_once('Views/chartgas.phtml');