<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    session_start();
    if(isset($_SESSION['email'])){
        unset($_SESSION['email']);
        exit('okay');
    }else{
        exit('email_not_set');
    }
?>