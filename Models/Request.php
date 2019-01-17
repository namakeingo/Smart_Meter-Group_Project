<?php
require_once('ConnectionConsumption.php');
error_reporting(0);
$type = $_GET['type'];
$period = $_GET['period'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];
$timeFormat = $_GET['timeFormat'];
$dateToJoinModifier = $_GET['dateToJoinModifier'];
$compareFrom = $_GET['compareFrom'];
$compareTo = $_GET['compareTo'];
if (isset ($type)&&$type!=''){
    if (isset($period, $dateFrom, $dateTo, $timeFormat)
    &&$period!='' &&$dateFrom!='' &&$dateTo!='' &&$timeFormat!=''){
        $output;
        if (isset($compareFrom, $compareTo)&&$compareTo!='' &&$compareTo!='') {
            $elecConsum = new ConnectionConsumption('ELEC', $period, $dateFrom, $dateTo);
            $elecDataSet = $elecConsum->getData();
            $elecdata1 = $elecDataSet->getConsumptionArray();
            $gasConsum = new ConnectionConsumption('GAS', $period, $dateFrom, $dateTo);
            $gasDataSet = $gasConsum->getData();
            $gasdata1 = $gasDataSet->getConsumptionArray();
            $elecConsum = new ConnectionConsumption('ELEC', $period, $compareFrom, $compareTo);
            $elecDataSet = $elecConsum->getData();
            $elecdata2 = $elecDataSet->getConsumptionArray();
            $gasConsum = new ConnectionConsumption('GAS', $period, $compareFrom, $compareTo);
            $gasDataSet = $gasConsum->getData();
            $gasdata2 = $gasDataSet->getConsumptionArray();
            if(isset($dateToJoinModifier)&&$dateToJoinModifier!=''){
                $output[0][0] = $elecdata2[0]->getDate()->format($timeFormat).'/'
                    .$elecdata2[0]->getDate()->modify($dateToJoinModifier)->format($timeFormat);
            }
            else {
                $output[0][0] = $elecdata2[0]->getDate()->format($timeFormat);
            }
            $output[0][1] = floatval($elecdata2[0]->getConsumption());
            $output[0][2] = floatval($gasdata2[0]->getConsumption());

            if (isset($dateToJoinModifier) && $dateToJoinModifier != '') {
                $output[1][0] = $elecdata1[0]->getDate()->format($timeFormat) . '/'
                    . $elecdata1[0]->getDate()->modify($dateToJoinModifier)->format($timeFormat);
            } else {
                $output[1][0] = $elecdata1[0]->getDate()->format($timeFormat);
            }
            $output[1][1] = floatval($elecdata1[0]->getConsumption());
            $output[1][2] = floatval($gasdata1[0]->getConsumption());
        }
        else{
            $Consum = new ConnectionConsumption($type,$period, $dateFrom, $dateTo);
            $DataSet = $Consum->getData();
            $data=$DataSet->getConsumptionArray();
            for ($i=0; $i < sizeof($data); $i++){
                if(isset($dateToJoinModifier)&&$dateToJoinModifier!=''){
                    $output[$i][0] = $data[$i]->getDate()->format($timeFormat).'/'
                        .$data[$i]->getDate()->modify($dateToJoinModifier)->format($timeFormat);
                }
                else {
                    $output[$i][0] = $data[$i]->getDate()->format($timeFormat);
                }
                $output[$i][1] = floatval($data[$i]->getConsumption());
            }
        }
        echo json_encode($output);
        exit();
    }
    //$date = new DateTime('now -2minutes', new DateTimeZone('Europe/London'));
    $Consum = new ConnectionConsumption($type,'PT1M', 'now -2minutes -30seconds', 'now -2minutes -30seconds');
    //var_dump($Consum);
    $DataSet = $Consum->getData();
    //var_dump($DataSet);
    echo floatval($DataSet->getConsumptionArray()[0]->getConsumption());
}
else{
    echo 'please set type (ELEC or GAS or BOTH)';
}
