<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        exit('server down');
    }

    //piattaforma, nome
    if(!isset($_POST['piattaforma']) || !isset($_POST['nome'])){
        exit('variables_not_set');
    }
    if(strlen($_POST['piattaforma']) > 50 || strlen('nome') > 50){
        exit('variables_too_long');
    }

    $piattaforma = $db->quote($_POST['piattaforma']);
    $nome = $db->quote("%".$_POST['nome']."%");

    $getAnnunciQuery = 
    "SELECT *
    FROM annunci
    WHERE piattaforma=$piattaforma
    AND (
        titolo LIKE $nome 
        OR testo LIKE $nome
    )";

    $resultGetAnnunciQuery = null;
    try{
        $resultGetAnnunciQuery = $db->query($getAnnunciQuery);
    }catch(PDOException $e){
        exit("query_failed");
    }

    exit(json_encode($resultGetAnnunciQuery->fetchAll(PDO::FETCH_ASSOC)));
?>