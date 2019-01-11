<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 11/01/2019
 * Time: 10:25
 */

class BudgetDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = DatabaseConnection::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchAllStudents() {
        $sqlQuery = 'SELECT * FROM budget';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new BudgetData($row);
        }
        return $dataSet;
    }

}