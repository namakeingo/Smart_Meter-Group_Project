<?php
////!!!! CONSUMPTION DATA MOVED TO /Request.php
//require_once('Models/ConnectionConsumption.php');

$view = new stdClass();
$view->pageTitle = 'Gas';


$view->type='GAS';


require_once('Views/chartgas.phtml');