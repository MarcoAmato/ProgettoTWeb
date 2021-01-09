<?php
if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
    exit("ERROR 400: Invalid request - This service accepts only POST requests.");
}

session_start();
if(isset($_SESSION['username'])){
    return $_SESSION['username'];
}else{
    return null;
}
?>