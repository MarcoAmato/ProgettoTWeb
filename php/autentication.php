<?php
if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
    exit("ERROR 400: Invalid request - This service accepts only POST requests.");
}

session_start();
$jsonReturn = array("sessionOkay"=>"");
if(isset($_SESSION['username'])){
    $jsonReturn["username"] = $_SESSION['username'];
}else{
    $jsonReturn["username"] = null;
}
session_destroy();
echo(json_encode($jsonReturn));
exit;
?>