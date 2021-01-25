<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        header("HTTP/1.1 400 Invalid Request");
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    session_start();
    if(!isset($_SESSION['email'])){
        exit("access_denied");
    }

    $email = $_SESSION['email'];

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        exit("server_down");
    }

    //check if input is inserted
    if(!isset($_POST['operation']) || !isset($_POST['id_annuncio'])){
        exit("variables_not_set");
    }

    if(!is_numeric($_POST['id_annuncio'])){
        echo $_POST['id_annuncio'];
        exit("id_annuncio_not_int");
    }

    $id_annuncio = intval($_POST["id_annuncio"]);
    $operation = $_POST['operation'];

    $query;

    switch($operation){
        case "add":
            $query = 
            "INSERT INTO preferiti(email_utente, id_annuncio)
            VALUES ('$email', $id_annuncio);";
            break;
        case "remove":
            $query = 
            "DELETE FROM preferiti
            WHERE id_annuncio=$id_annuncio
            AND email_utente='$email';";
            break;
        default:
            exit("upexpected_operation");
    }

    $result = $db->query($query);

    exit("success");
?>