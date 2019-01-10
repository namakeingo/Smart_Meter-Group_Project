<?php
require_once ('Models/Connection.php');
$view = new stdClass();
$view->pageTitle = 'Homepage';
//require_once('Views/index.phtml');
echo '<form action="index.php" method="post">';
echo '<input type="submit" name="ELEC" value="ELEC">';
echo '<input type="submit" name="GAS" value="GAS">';
echo '</form>';

if (isset($_POST)) {
    $data = array('period' => 'PT1H');
    if (isset($_POST['ELEC'])){
        $data['type'] = 'ELEC';
    }else{
        $data['type'] = 'GAS';
    }
    $url = new Connection($data);
    $dataSet = $url->getData();

    if (isset($_POST['ELEC'])){
        echo 'Total Electricity Usage:' . $dataSet->getElecCost(). '£';    echo "<br/>";
        echo 'Total Electricity Consumption this month: '.$dataSet->getTotalConsumption().' kWh';    echo "<br/>";
    }else{
        echo 'Total Gas Usage:' . $dataSet->getGasCost(). '£';    echo "<br/>";
        echo 'Total Gas Consumption this month: '.$dataSet->getTotalConsumption().' kWh';    echo "<br/>";
    }
    echo "<br/>";
}
