<?php
////!!!! CONSUMPTION DATA MOVED TO /Request.php
//require_once('Models/ConnectionConsumption.php');

$view = new stdClass();
$view->pageTitle = 'Electricity';


$view->type='ELEC';



require_once('Views/chart.phtml');