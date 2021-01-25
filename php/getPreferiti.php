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

    $email = $db->quote($_SESSION['email']);
    $id_annunci = $_POST['id_annunci'];

    $select_annunci_preferiti = 
    "SELECT id_annuncio
    FROM preferiti
    WHERE preferiti.email_utente=$email
    AND id_annuncio IN $id_annunci;
    ";

    $result = $db->query($select_annunci_preferiti);
    $annunci_preferiti = $result->fetchAll();

    $is_preferito_array = array();
    foreach($id_annunci as $id_annuncio){
        if(in_array($id_annuncio, $annunci_preferiti)){
            array_push($is_preferito_array, true);
        }else{
            array_push($is_preferito_array, false);
        }
    }

    return json_encode($is_preferito_array);
?>