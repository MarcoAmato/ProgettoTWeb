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

    if(isset($_POST['personal'])){
        $query = getPersonalQuery($db);
    }else{
        //piattaforma, nome
        if(!isset($_POST['piattaforma']) || !isset($_POST['nome'])){
            exit('variables_not_set');
        }
        if(strlen($_POST['piattaforma']) > 50 || strlen('nome') > 50){
            exit('variables_too_long');
        }

        $piattaforma = $db->quote($_POST['piattaforma']);
        $nome = $db->quote("%".$_POST['nome']."%");

        if($_POST['piattaforma']=="all"){
            $query = getAnnunciPiattaformaAllQuery($nome);
        }else{
            $query = getGeneralAnnunciQuery($nome, $piattaforma);
        }
    }

    try{
        $resultQuery = $db->query($query);
    }catch(PDOException $e){
        exit("query_failed");
    }

    exit(json_encode($resultQuery->fetchAll(PDO::FETCH_ASSOC)));

function getPersonalQuery($db){
    if($_POST['personal']!=="true"){
        exit('personal_variable_not_true');
    }

    session_start();
    if(!isset($_SESSION['email'])){
        exit('access_denied');
    }

    if(!filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)){
        exit('bad_email_format');
    }

    $email = $db->quote($_SESSION['email']);

    $select_my_annunci_query = 
    "SELECT *
    FROM annunci
    WHERE email_utente=$email
    ";

    return $select_my_annunci_query;
}

function getAnnunciPiattaformaAllQuery($nome){
    $annunciPiattaformaAllQuery = 
    "SELECT *
    FROM annunci
    WHERE (
        titolo LIKE $nome 
        OR testo LIKE $nome
    );";

    return $annunciPiattaformaAllQuery;
}

function getGeneralAnnunciQuery($nome, $piattaforma){
    $getAnnunciQuery = 
    "SELECT *
    FROM annunci
    WHERE piattaforma=$piattaforma
    AND (
        titolo LIKE $nome 
        OR testo LIKE $nome
    );";

    return $getAnnunciQuery;
}
?>