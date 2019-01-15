<?php
require_once('ConnectionConsumption.php');

$type = $_GET['type'];
if (isset ($type)){
    $date = new DateTime('now -3minutes', new DateTimeZone('Europe/London'));
    $elecConsum = new ConnectionConsumption($type,'PT1M', $date->format('Y-m-d\TH:i').':00', $date->format('Y-m-d\TH:i').':59');
    //var_dump($elecConsum);
    $elecDataSet = $elecConsum->getData();
    //var_dump($elecDataSet);
    echo floatval($elecDataSet->getConsumptionArray()[0]->getConsumption());
}
else{
    echo 'please set type (ELEC or GAS)';
}
