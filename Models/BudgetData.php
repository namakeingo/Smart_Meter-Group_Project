<?php
/**
 * Created by PhpStorm.
 * User: abdi
 * Date: 11/01/2019
 * Time: 10:38
 */

class BudgetData
{
    private $budgetLimit;
    private $startDate;
    private $email;
    private $HID;

    public function __construct($dbRow)
    {
        $this->budgetLimit = $dbRow['budget_limit'];
        $this->startDate = $dbRow['start_date'];
        $this->email = $dbRow['email'];
        $this->HID = $dbRow['hid'];
    }


    /**
     * @return mixed
     */
    public function getBudgetLimit()
    {
        return $this->budgetLimit;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getHID()
    {
        return $this->HID;
    }
}