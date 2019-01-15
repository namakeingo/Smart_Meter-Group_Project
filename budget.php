<?php

require_once("Models/BudgetDb.php");
session_start();

if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

if (isset($_POST["submit"])) {
    $emailValidation = $_POST['email'];
    $electrictyValidation = $_POST['electricityPrice'];
    $gasValidation = $_POST['gasPrice'];
    if ($emailValidation == 'group2@hotmail.com') {
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
        require_once("Views/seeBudget.phtml");
    } else {
        $error = 'Email is incorrect';
        require_once("Views/budget.phtml");
    }

} else {
    $budgetDatabase = new BudgetDb();
    $budget2 = $budgetDatabase->getBudget("group2@hotmail.com");
    $_SESSION["electricityBudget"] = $budget2["electricityPrice"];
    $_SESSION["gasBudget"] = $budget2["gasPrice"];
    require_once("Views/budget.phtml");
    echo 'not working';
}
?>
