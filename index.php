<?php
require_once('Models/ConnectionConsumption.php');
require_once ('Models/ConnectionWeather');
$view = new stdClass();
$view->pageTitle = 'Homepage';
// for backend testing
//echo '<form action="index.php" method="post">';
//echo '<input type="submit" name="ELEC" value="ELEC">';
//echo '<input type="submit" name="GAS" value="GAS">';
//echo '</form>';
//
//if (isset($_POST)) {
//    $data = array('period' => 'PT1H');
//    if (isset($_POST['ELEC'])) {
//        $data['type'] = 'ELEC';
//    } else {
//        $data['type'] = 'GAS';
//    }


$elecConsum = new ConnectionConsumption('ELEC','PT1H');
$elecDataSet = $elecConsum->getData();

$gasConsum = new ConnectionConsumption('GAS','PT1H');
$gasDataSet = $gasConsum->getData();

$view->totalElec = $elecDataSet->getElecCost();
//var_dump($view->totalElec);
//die();
$view->totalGas = $gasDataSet->getGasCost();
require_once('Views/index.phtml');
//    if (isset($_POST['ELEC'])) {
//        echo 'Total Electricity Usage:' . $dataSet->getElecCost() . '£';
//        echo "<br/>";
//        echo 'Total Electricity Consumption this month: ' . $dataSet->getTotalConsumption() . ' kWh';
//        echo "<br/>";
//    } else {
//        echo 'Total Gas Usage:' . $dataSet->getGasCost() . '£';
//        echo "<br/>";
//        echo 'Total Gas Consumption this month: ' . $dataSet->getTotalConsumption() . ' kWh';
//        echo "<br/>";
//    }



// changes the url for the weather api
//    $url = new ConnectionWeather();
//    $dataSet = $url->getData();
//    echo '<p> Forecast ' . $dataSet->getWeatherArray()[0]->getForecast() . '</p>';


//}
