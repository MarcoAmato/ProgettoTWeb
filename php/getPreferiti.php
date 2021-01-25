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

    $id_annunci_int = [];

    foreach($_POST['id_annunci'] as $id_string){
        if(!is_numeric($id_string)){
            exit("id_annunci_contains_unexpected_values");
        }
        $id_int = intval($id_string);
        array_push($id_annunci_int, $id_int);
    }

    $id_annunci_string = "(".implode(",", $id_annunci_int).")";
    $email = $db->quote($_SESSION['email']);

    $select_annunci_preferiti = 
    "SELECT id_annuncio
    FROM preferiti
    WHERE preferiti.email_utente=$email
    AND id_annuncio IN $id_annunci_string;
    ";

    $result = $db->query($select_annunci_preferiti);
    $annunci_preferiti = $result->fetchAll(PDO::FETCH_COLUMN);

    $is_preferito_array = array();

    foreach($id_annunci_int as $id_annuncio){
        if(in_array($id_annuncio, $annunci_preferiti)){
            array_push($is_preferito_array, true);
        }else{
            array_push($is_preferito_array, false);
        }
    }

    echo json_encode($is_preferito_array);
?>