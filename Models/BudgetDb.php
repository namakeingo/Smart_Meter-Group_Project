<?php

require_once("DatabaseConnection.php");

class BudgetDb{
  private $conn;

  public function __construct(){
    global $conn;
    $this->conn = $conn;
  }

  public function insert($data){
    if(!$this->checkIfBudgetExists($data["email"])){
    $query = $this->conn->prepare("INSERT INTO budget(gasPrice,electricityPrice,email,date) VALUES(?,?,?,?)");
    $query->bindParam(1,$data["gasPrice"]);
    $query->bindParam(2,$data["electricityPrice"]);
    $query->bindParam(3,$data["email"]);
    $query->bindParam(4,$data["date"]);
    $query->execute();
  } else {
    $this->updateBudget($data);
  }
  }

  public function getBudget($email){
    $query = $this->conn->prepare("SELECT * FROM budget WHERE email = ?");
    $query->bindParam(1,$email);
		$query->execute();
			while($row = $query->fetch()){
		      return $row;
			}
  }

  public function checkIfBudgetExists($email){
    $query = $this->conn->prepare("SELECT * FROM budget WHERE email = ?");
    $query->bindParam(1,$email);
    $query->execute();
    $row = $query->fetch();

    if($row["date"] != null){
      return true;
    }
    return false;
  }

  public function updateBudget($data){
    $query = $this->conn->prepare("UPDATE budget SET electricityPrice = ? , gasPrice = ? , date = ? WHERE email = ? ");
    $query->bindParam(1,$data["electricityPrice"]);
    $query->bindParam(2,$data["gasPrice"]);
    $query->bindParam(3,$data["date"]);
    $query->bindParam(4,$data["email"]);
    $query->execute();
  }

}
