<?php
require_once('ConnectionConsumption.php');
require_once ('Prediction.php');
require_once('ConnectionWeather.php');
require_once ('ConnectionLocation.php');
error_reporting(0);
$type = $_GET['type'];
$delay = $_GET['delay'];
$period = $_GET['period'];
$dateFrom = $_GET['dateFrom'];
$dateTo = $_GET['dateTo'];
$timeFormat = $_GET['timeFormat'];
$dateToJoinModifier = $_GET['dateToJoinModifier'];
$compareFrom = $_GET['compareFrom'];
$compareTo = $_GET['compareTo'];
$unit = $_GET{'unit'};
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
    else if (isset($delay) &&$delay!='') {
        //$date = new DateTime('now -2minutes', new DateTimeZone('Europe/London'));
        $Consum = new ConnectionConsumption($type, 'PT1M', 'now '.$delay, 'now '.$delay);
        //var_dump($Consum);
        $DataSet = $Consum->getData();
        //var_dump($DataSet);
        echo floatval($DataSet->getConsumptionArray()[0]->getConsumption());
    }
}
else if(isset($unit)&&$unit!=''){
    $url = new ConnectionLocation();
    $location = $url->getData();
    //changes the url for the weather api
    $url = new ConnectionWeather('forecast', 'Salford');
    // weather for 5 days in the future
    $view->weatherPredictionSet = ($url->getData('forecast'))->getPredictedWeather();
    // predicted weather converted to a usable array for k nn
    $testWeatherArray = ($url->getData('forecast'))->createTestWeatherArray();
    $url = new ConnectionWeather('weather','Salford');
    // weather for the current time of access to the app
    $view->weatherNow = ($url->getData('weather'))->getWeatherArray()[0];

    // classifier initialised
    $prediction = new Prediction(7);
    // classifier trained on the training data set
    $prediction->train('Elec');
    // predicted usage for the 5 days saved for the view
    $view->predictedUsageElec = [];
    $count = 0;
    foreach ($testWeatherArray as $value) {
        $view->predictedUsageElec[] = new ConsumptionData($view->weatherPredictionSet[$count]->getTime()->getTimeStamp(),
            floatval($prediction->predict(array($value[0], $value[1], $value[2]))));
    }
    $prediction->train('Gas');
    // predicted usage for the 5 days saved for the view
    $view->predictedUsageGas = [];
    $count = 0;
    foreach ($testWeatherArray as $value) {
        $view->predictedUsageGas[] = new ConsumptionData($view->weatherPredictionSet[$count]->getTime()->getTimeStamp(),
            floatval($prediction->predict(array($value[0], $value[1], $value[2]))));
        $count++;
    }
    $output;
    if (strtolower($unit)=='pounds'){
        for ($i=0; $i<sizeof($view->predictedUsageElec); $i++){
            $view->weatherPredictionSet[$i]->convertToCelcius();
            $output[$i][0]=$view->weatherPredictionSet[$i]->getTime()->format('D d').' ('
                .intval($view->weatherPredictionSet[$i]->getTemp()) .'℃)';
            $output[$i][1]=$view->predictedUsageElec[$i]->getPredictedCost('Elec');
            $output[$i][2]=$view->predictedUsageGas[$i]->getPredictedCost('Gas');
        }
        echo json_encode($output);
    }
    else if (strtolower($unit)=='kwh'){
        for ($i=0; $i<sizeof($view->predictedUsageElec); $i++){
            $view->weatherPredictionSet[$i]->convertToCelcius();
            $output[$i][0]=$view->weatherPredictionSet[$i]->getTime()->format('D d').' ('
                .intval($view->weatherPredictionSet[$i]->getTemp()) .'℃)';
            $output[$i][1]=$view->predictedUsageElec[$i]->getConsumption();
            $output[$i][2]=$view->predictedUsageGas[$i]->getConsumption();
        }
        echo json_encode($output);
    }
    else {
        echo json_encode(['please set unit as Pounds or kWh']);
    }
}
else{
    echo 'please set type (ELEC or GAS or BOTH) and a delay (they cannot be null or \'\')';
}
