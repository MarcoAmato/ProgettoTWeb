<?php
    $PATH_TO_REDIRECT = "../html/complete/sign-up.shtml";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "GET"){
        header("HTTP/1.1 400 Invalid Request");
        die("ERROR 400: Invalid request - This service accepts only GET requests.");
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

    //compose query
    $email = $db->quote($_GET['email']);
    $hashed_password = password_hash($db->quote($_GET['password']), PASSWORD_DEFAULT);
	$nascita = $_GET['nascita'];
	$genere = $_GET['genere'];

	$insert_query ="
		INSERT INTO utenti (`email`,`password`,`data_di_nascita`,`genere`)
		VALUES ($email,'$hashed_password','$nascita','$genere');
    ";

    try{
        $result = $db->query($insert_query);
    }catch(Exception $e){
        redirectWithError($PATH_TO_REDIRECT,"query_failed");
    }
    //send it to database
    if(!$result){
        //result is null = error in processing query
        redirectWithError($PATH_TO_REDIRECT, "query_failed");
    }else{
        header("Location: $PATH_TO_REDIRECT?success=true");
        exit;
    }
?>