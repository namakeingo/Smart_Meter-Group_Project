<?php
require_once('Models/ConnectionConsumption.php');
require_once ('Models/Prediction.php');
require_once('Models/ConnectionWeather.php');
require_once ('Models/ConnectionLocation.php');
error_reporting(0);
// get all possible variable in the link
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
// check $type isset - InstantConsumption or GasChart or ElectricityChart or ComparisonChart
if (isset ($type)&&$type!=''){
    // check $period and $dateFrom and $dateTo and $timeFormat are isset - GasChart or ElectricityChart or ComparisonChart
    if (isset($period, $dateFrom, $dateTo, $timeFormat)
    &&$period!='' &&$dateFrom!='' &&$dateTo!='' &&$timeFormat!=''){
        //generate empty output
        $output;
        // check $compareFrom and $compareTo are isset
        // ComparisonChart
        if (isset($compareFrom, $compareTo)&&$compareTo!='' &&$compareTo!='') {
            // generate elements needed for comparison chart
            // first period data
            $elecConsum = new ConnectionConsumption('ELEC', $period, $dateFrom, $dateTo);
            $elecDataSet = $elecConsum->getData();
            $elecdata1 = $elecDataSet->getConsumptionArray();
            $gasConsum = new ConnectionConsumption('GAS', $period, $dateFrom, $dateTo);
            $gasDataSet = $gasConsum->getData();
            $gasdata1 = $gasDataSet->getConsumptionArray();
            // second period data
            $elecConsum = new ConnectionConsumption('ELEC', $period, $compareFrom, $compareTo);
            $elecDataSet = $elecConsum->getData();
            $elecdata2 = $elecDataSet->getConsumptionArray();
            $gasConsum = new ConnectionConsumption('GAS', $period, $compareFrom, $compareTo);
            $gasDataSet = $gasConsum->getData();
            $gasdata2 = $gasDataSet->getConsumptionArray();
            // generate output array for CompareChart - second period data
            // (if dateToJoinModifier isset then set date ad concat time/time)
            if(isset($dateToJoinModifier)&&$dateToJoinModifier!=''){
                $output[0][0] = $elecdata2[0]->getDate()->format($timeFormat).'/'
                    .$elecdata2[0]->getDate()->modify($dateToJoinModifier)->format($timeFormat);
            }
            else {
                $output[0][0] = $elecdata2[0]->getDate()->format($timeFormat);
            }
            $output[0][1] = floatval($elecdata2[0]->getConsumption());
            $output[0][2] = floatval($gasdata2[0]->getConsumption());
            // generate output array  for CompareChart - first period data
            // (if $dateToJoinModifier isset then set date ad concat time/time)
            if (isset($dateToJoinModifier) && $dateToJoinModifier != '') {
                $output[1][0] = $elecdata1[0]->getDate()->format($timeFormat) . '/'
                    . $elecdata1[0]->getDate()->modify($dateToJoinModifier)->format($timeFormat);
            } else {
                $output[1][0] = $elecdata1[0]->getDate()->format($timeFormat);
            }
            $output[1][1] = floatval($elecdata1[0]->getConsumption());
            $output[1][2] = floatval($gasdata1[0]->getConsumption());
        }
        // GasChart or ElectricityChart
        else{
            // generate elements needed for Gas and Electricity charts
            $Consum = new ConnectionConsumption($type,$period, $dateFrom, $dateTo);
            $DataSet = $Consum->getData();
            $data=$DataSet->getConsumptionArray();
            // generate output array  for Gas and Electricity Charts
            for ($i=0; $i < sizeof($data); $i++){
                // (if $dateToJoinModifier isset then set date ad concat time/time)
                if(isset($dateToJoinModifier)&&$dateToJoinModifier!=''){
                    $output[$i][0] = $data[$i]->getDate()->format($timeFormat).' - '
                        .$data[$i]->getDate()->modify($dateToJoinModifier)->format($timeFormat);
                }
                else {
                    $output[$i][0] = $data[$i]->getDate()->format($timeFormat);
                }
                $output[$i][1] = floatval($data[$i]->getConsumption());
            }
        }
        // output array for GasChart or ElectricityChart or ComparisonChart
        echo json_encode($output);
        // end of stream
        exit();
    }
    // Instant Consumption
    else if (isset($delay) &&$delay!='') {
        // generate data for Instant Consumption
        $Consum = new ConnectionConsumption($type, 'PT1M', 'now '.$delay, 'now '.$delay);
        $DataSet = $Consum->getData();
        // output float value for Instant Consumption
        echo floatval($DataSet->getConsumptionArray()[0]->getConsumption());
    }
    else{
        // output error
        json_encode(['please check inserted variable are valid']);
    }
}
// check if $unit isset
// PredictionChart
else if(isset($unit)&&$unit!=''){
    // generate PredictionChart data
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
    // create empty $output
    $output;
    // if $unit is pounds then convert consumption to cost
    if (strtolower($unit)=='pounds'){
        // generate output array  for PredictionChart
        for ($i=0; $i<sizeof($view->predictedUsageElec); $i++){
            $view->weatherPredictionSet[$i]->convertToCelcius();
            $output[$i][0]=$view->weatherPredictionSet[$i]->getTime()->format('D d').' ('
                .intval($view->weatherPredictionSet[$i]->getTemp()) .'℃)';
            $output[$i][1]=$view->predictedUsageElec[$i]->getPredictedCost('Elec');
            $output[$i][2]=$view->predictedUsageGas[$i]->getPredictedCost('Gas');
        }
        // output array for PredictionCost
        echo json_encode($output);
    }
    // if $unit is kwh then keep consumption in kwh
    else if (strtolower($unit)=='kwh'){
        for ($i=0; $i<sizeof($view->predictedUsageElec); $i++){
            // generate output array  for PredictionChart
            $view->weatherPredictionSet[$i]->convertToCelcius();
            $output[$i][0]=$view->weatherPredictionSet[$i]->getTime()->format('D d').' ('
                .intval($view->weatherPredictionSet[$i]->getTemp()) .'℃)';
            $output[$i][1]=$view->predictedUsageElec[$i]->getConsumption();
            $output[$i][2]=$view->predictedUsageGas[$i]->getConsumption();
        }
        // output array for PredictionCost
        echo json_encode($output);
    }
    else {
        // output error
        echo json_encode(['please set unit as Pounds or kWh']);
    }
}
else{
    // output error
    echo 'please set type (ELEC or GAS or BOTH) and a delay (they cannot be null or \'\')';
}
