<?php

require_once("Models/BudgetDb.php");
$errors = array();
$success = false;
$budgetDatabase = new BudgetDb();

if (isset($_POST["submit"])) {
    $emailValidation = $_POST['email'];
    $electrictyValidation = $_POST['electricityPrice'];
    $gasValidation = $_POST['gasPrice'];

    if ($emailValidation != 'group2@hotmail.com') {
        array_push($errors, "Email is incorrect.");
    } else {
        $data = array("electricityPrice" => $_POST["electricityPrice"],
            "gasPrice" => $_POST["gasPrice"],
            "email" => $_POST["email"],
            "date" => $_POST["day"] . "-" . $_POST["month"] . "-" . $_POST["year"]);
        $budgetDatabase->insert($data);
        $success = true;
    }
}
if (isset($_POST['cancel']) || isset($_POST['return'])) {
    header("location: index.php");
}

$budget2 = $budgetDatabase->getBudget("group2@hotmail.com");
$electricPrice = $budget2["electricityPrice"];
$gasPrice = $budget2["gasPrice"];
$date = $budget2['date'];
if ($success) {
    require_once("Views/seeBudget.phtml");
} else {
    require_once("Views/budget.phtml");
}