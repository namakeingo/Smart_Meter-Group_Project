<?php
require_once('Models/ConnectionConsumption.php');
require_once ('Models/ConnectionWeather');
$view = new stdClass();
$view->pageTitle = 'Homepage';
//require_once('Views/index.phtml');
echo '<form action="index.php" method="post">';
echo '<input type="submit" name="submit" value="Submit">';
echo '</form>';

if (isset($_POST['submit'])) {
    $data = array('type' => 'ELEC',
        'period' => 'PT1M');
    $url = new ConnectionConsumption($data);
    $dataSet = $url->getData();
    foreach($dataSet->getConsumptionArray() as $value) {
        echo $value->toString();
    }
    // changes the url for the weather api
    $url = new ConnectionWeather();
    $dataSet = $url->getData();
    echo '<p> Forecast '.$dataSet->getWeatherArray()[0]->getForecast().'</p>';


}




