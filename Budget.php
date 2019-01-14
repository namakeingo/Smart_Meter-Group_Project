<?php

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
             if ($this->emailValidation() && $this->priceValidation()) {
                 $_SESSION["success"] = true;

                 //insert budget and email in database

                  $this->redirect("Views/budget.phtml");
                  echo "success";

             } else {

                 $_SESSION["error"] = "Email or price are not valid";
                 $this->redirect("Views/budget.phtml");

             }
         } else {

             $this->redirect("Views/budget.phtml");
         }
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
    public function priceValidation(){
        if (filter_var($_POST["price"], FILTER_VALIDATE_INT)) {
            return true;
        }
        return false;
    }


    public function redirect($path){
        require_once($path);
    }





}


?>
