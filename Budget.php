<?php

require_once("Models/Database.php");
require_once("Models/BudgetDb.php");

if(session_id() == null){
    session_start();
}
class Budget{

    public function __construct()
    {


        $this->setBudget();
    }

    /*
     * Main method
     * */
    public function setBudget(){
         if(isset($_POST["submit"])) {

             if ($this->emailValidation() && $this->priceValidation($_POST["gasPrice"]) && $this->priceValidation($_POST["electricityPrice"])) {
                 $_SESSION["success"] = true;

                 $data = array(
                   "electricityPrice" => $_POST["electricityPrice"],
                   "gasPrice" => $_POST["gasPrice"],
                   "email" => $_POST["email"],
                   "date" => $_POST["day"] . "-" . $_POST["month"] . "-" . $_POST["year"]
                );
                  $budgetDatabase = new BudgetDb();
                  $budgetDatabase->insert($data);
                  $budget = $budgetDatabase->getBudget("group2@hotmail.com");
                  $_SESSION["electricityBudget"] = $budget["electricityPrice"];
                  $_SESSION["gasBudget"] = $budget["gasPrice"];
                  $this->redirect("Views/seeBudget.phtml");
                  echo "success";

             } else {

                 $_SESSION["error"] = "Email or price are not valid";
                 $this->redirect("Views/budget.phtml");

             }
         } else {
               $budgetDatabase = new BudgetDb();
               $budget2 = $budgetDatabase->getBudget("group2@hotmail.com");
               $_SESSION["electricityBudget"] = $budget2["electricityPrice"];
               $_SESSION["gasBudget"] = $budget2["gasPrice"];
               $this->redirect("Views/budget.phtml");
             }
         }
   

    public function checkIfBudgetIsSet($email){

    }



    /*
     * This method validates the email input in the budget.phtml file
     * returns true if the email is validated
     * */
    public function emailValidation(){
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }


    /*
     * This method validates the budget'price' input in the budget.phtml file
     * returns true if the price if validated
     * */
    public function priceValidation($price){
        if (filter_var($price, FILTER_VALIDATE_INT)) {
            return true;
        }
        return false;
    }


    public function redirect($path){
        require_once($path);
    }





}


?>
