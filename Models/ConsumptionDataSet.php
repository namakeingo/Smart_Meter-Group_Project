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
        // first loop is to get to the arrays containing the consumption data
        foreach ($tempArray as $headArray) {
            // second loop is to get to the actual data
            // time stamp is at position 0
            // consumption is at position 1
                $this->consumptionArray[] = new ConsumptionData($headArray[0], $headArray[1]);
        }
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