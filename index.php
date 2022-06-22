<?php



$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
if (empty($action)) {
    $action = "error";
}


require('app/control/control.php');
$control = new \control\Control();
$control->$action();