<?php
    $PATH_TO_REDIRECT = "../html/complete/new-advert.shtml";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        header("HTTP/1.1 400 Invalid Request");
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        redirectWithError($PATH_TO_REDIRECT, "server_down");
    }

    //clan input
    if(!isset($_GET['email']) || !isset($_GET['password']) || !isset($_GET['nascita']) || !isset($_GET['genere'])){
        redirectWithError($PATH_TO_REDIRECT,"variables_not_set");
	}
	
	if(!isDate($_GET['nascita'])){
        redirectWithError($PATH_TO_REDIRECT,"wrong_date_format");
	}

	if($_GET['genere'] !== "m"
		&& $_GET['genere'] !== "f"){
            redirectWithError($PATH_TO_REDIRECT,"wrong_gender");
	}

?>