<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "GET"){
        header("HTTP/1.1 400 Invalid Request");
        die("ERROR 400: Invalid request - This service accepts only GET requests.");
	}
	
    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        echo 'error pdo failed';
        exit;
    }

    if(!isset($_GET['email']) || !isset($_GET['password']) || !isset($_GET['nascita']) || !isset($_GET['genere'])){
        echo("error variables not set");
        exit;
	}
	
	if(!isDate($_GET['nascita'])){
		echo("error date format is not valid");
        exit;
	}

	if($_GET['genere'] !== "m"
		&& $_GET['genere'] !== "f"){
			echo("error there are only 2 genders");
			exit;
	}

    $email = $db->quote($_GET['email']);
    $hashed_password = password_hash($db->quote($_GET['password']));
	$nascita = $_GET['nascita'];
	$genere = $_GET['genere'];

	$insert_query ="
	"
?>