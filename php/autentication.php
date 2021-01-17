<?php
if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
    exit("ERROR 400: Invalid request - This service accepts only POST requests.");
}

session_start();
$jsonReturn = array("email"=>"");
if(isset($_SESSION['email'])){
    $jsonReturn["email"] = $_SESSION['email'];
}else{
    $jsonReturn["email"] = null;
}
echo(json_encode($jsonReturn));
exit;
?>