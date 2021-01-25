<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        exit('server_down');
    }

    session_start();
    if(!isset($_SESSION['email'])){
        exit("access_denied");
    }

    if(!isset($_POST['id_annunci'])){
        exit("id_annunci_missing");
    }

    if(!is_array($_POST['id_annunci'])){
        exit("id_annunci_not_array");
    }

    foreach($_POST['id_annunci'] as $annuncio){
        if(!is_int($annuncio)){
            exit("id_annunci_contains_unexpected_values");
        }
    }

    $select_annunci_preferiti = 
    "SELECT id_annuncio
    FROM preferiti
    WHERE preferiti.email_utente='d@a.i'
    AND id_annuncio IN (6,7,8,9);
    ";
?>