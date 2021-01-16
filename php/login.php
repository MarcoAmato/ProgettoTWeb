<?php
    $PATH_TO_REDIRECT = "../html/complete/index.html";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    session_start();
    if(isset($_SESSION['username'])){
        header("Location: $PATH_TO_REDIRECT");
    }

    try{
        $db = dbconnect();
    }catch(PDOException $e){
        redirectWithError($PATH_TO_REDIRECT, "server down");
    }

    $email = $db->quote($_GET['email']);
    $hashed_password = $db->hash('md5', $db->quote($_GET['password']));

    $select_user = ""

?>