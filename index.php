<?php
require_once ('Models/Connection.php');
$view = new stdClass();
$view->pageTitle = 'Homepage';
//require_once('Views/index.phtml');
echo '<form action="index.php" method="post">';
echo '<input type="submit" name="submit" value="Submit">';
echo '</form>';

if (isset($_POST['submit'])) {
    $data = array('type' => 'ELEC',
        'period' => 'PT1M');
    $url = new Connection($data);
    $dataSet = $url->getData();
    foreach($dataSet->getConsumptionArray() as $value) {
        echo $value->toString();
    }
}
