<?php
    $PATH_TO_REDIRECT = "../html/complete/sign-up.shtml";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "POST"){
        header("HTTP/1.1 400 Invalid Request");
        die("ERROR 400: Invalid request - This service accepts only POST requests.");
	}
	
    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        redirectWithError($PATH_TO_REDIRECT, "server_down");
    }

    //clan input
    if(!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['nascita']) || !isset($_POST['genere'])){
        redirectWithError($PATH_TO_REDIRECT,"variables_not_set");
	}
	
	if(!isDate($_POST['nascita'])){
        redirectWithError($PATH_TO_REDIRECT,"wrong_date_format");
	}

	if($_POST['genere'] !== "m"
		&& $_POST['genere'] !== "f"){
            redirectWithError($PATH_TO_REDIRECT,"wrong_gender");
	}

    //compose query
    $email = $db->quote($_POST['email']);
    $user_exists_query = "
    SELECT * FROM utenti WHERE email = $email;
    ";

    try{
        $result_user_exists_query = $db->query($user_exists_query);
    }catch(Exception $e){
        redirectWithError($PATH_TO_REDIRECT,"query_failed");
    }

    if($result_user_exists_query->rowCount()>0){
        //email is in database
        redirectWithError($PATH_TO_REDIRECT, "email_registered");
    }

    $hashed_password = password_hash($db->quote($_POST['password']), PASSWORD_DEFAULT);
	$nascita = $_POST['nascita'];
	$genere = $_POST['genere'];

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