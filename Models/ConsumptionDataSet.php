<?php
require_once ('ConsumptionData.php');
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 09/01/2019
 * Time: 14:13
 */

class ConsumptionDataSet
{
    private $consumptionArray;

    public function __construct($string)
    {
        // decodes the json string gotten by the constructor and turns it into an associative array
        $tempArray  = json_decode($string, true);
        // the useful data is stored as an array called data in an array called data
        $tempArray = $tempArray['data']['data'];
        foreach ($tempArray as $headArray) {
            // time stamp is at position 0
            // consumption is at position 1
                $this->consumptionArray[] = new ConsumptionData($headArray[0], $headArray[1]);
        }
    }
// This function gets the total Electricity Cost based on day and night tariffs
    public function getElecCost(){
        //sets 2 variables for the day and night consumptions
        $dayConsumption=0;
        $nightConsumption=0;
        // Go Through all the data stored in the array
        foreach ($this->consumptionArray as $value){
            //Create a new variable called H which will store the specified value 'hour'
            $H=intval($value->getDate()->format('H'));
            //Check if the Hour is a nigh one(00:00 till 07:00) or a day one(07:01 till 23:59)
            if($H < 7){
                // Add all the night consumption to the varaible inizialised at the beginning
                $nightConsumption += $value->getConsumption();
            }else{
                // Add all the day consumption to the varaible inizialised at the beginning
                $dayConsumption += $value->getConsumption();
            }
        }
        // Get total day consumption's cost in pences
        $dayCost= $dayConsumption*17.3;
        // Get total night consumption's cost in pences
        $nightCost=$nightConsumption*11.51;
        // Get total Electricity consumption price in pences
        $costInPences = $dayCost+$nightCost;
        // Return the total Electricity consumption price in £, considering only the first 2 decimals
        return $totalElecCost = round($costInPences/100,2);
    }

    // THis function gets the total Gas Cost
    public function getGasCost(){
        // Sets a new variable
        $totalGasConsumption=0;
        // Go Through all the data store in the array
        foreach ($this->consumptionArray as $value){
            // Add all the data's gas consumption to the variable initialised at the beginning
            $totalGasConsumption += $value->getConsumption();
        }
        // Get the total price in pences
        $costInPences = $totalGasConsumption*3.93;
        // Return the total Gas consumption price in £, cosnidering only the first 2 decimals
        return $totalGasCost = round($costInPences/100,2);

    }

    // This function gets the total consumption for either Gas or Electricity in kilowatt
    public function getTotalConsumption(){
        // sets a new variable
        $totalConsumption=0;
        // Go through all the data store in the array
        foreach($this->consumptionArray as $value) {
            // Add all the data's consumption to the variable initialised at the beginning
            $totalConsumption+= $value->getConsumption();
        }
        // Return the total Consumption(Gas or Electricity, based on the type of data store in the array);
        return $totalConsumption;
    }
    /**
     * @return mixed
     */
    public function getConsumptionArray()
    {
        return $this->consumptionArray;
    }

    /**
     * @param mixed $consumptionArray
     */
    public function setConsumptionArray($consumptionArray)
    {
        $this->consumptionArray = $consumptionArray;
    }

}