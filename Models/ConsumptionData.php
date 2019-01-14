<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 09/01/2019
 * Time: 14:11
 */

class ConsumptionData
{
    private $date;
    private $consumption;

    public function __construct($timeStamp, $consumption)
    {
        // converts the timestamp to a date
        $this->convertUnixToDate($timeStamp);
        $this->consumption = $consumption;
    }

    private function convertUnixToDate($timeStamp) {
        $date = new DateTime();
        $this->date = $date->setTimestamp($timeStamp);
    }

    /**
     * @return mixed
     */
    public function getConsumption()
    {
        return $this->consumption;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function toString() {
        //converts the consumption object to a string with the date concatenated with the consumption
        return $this->getDate()->format('Y-m-d H:i:s') . ' ' . $this->getConsumption();
    }

    public function getInstantCost($type) {
        $totalCost = 0;
        if ($type == 'Elec') {
            $day = $this->getConsumption() * 0.6;
            $night = $this->getConsumption() * 0.4;
            $totalCost = ($day * (17.3/100)) + ($night * (11.51/100));
        }
        elseif ($type == 'Gas') {
            $totalCost = ($this->getConsumption() * 1);
        }
        return $totalCost;
    }

}