<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        header("HTTP/1.1 400 Invalid Request");
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        exit('server_down');
    }

    if(!isset($_POST['id'])){
        exit("id_not_set");
    }
    $id = $_POST['id'];
    if(!is_numeric($id)){
        exit("id_is_not_int");
    }
    $id = intval($id);

    $query_get_annuncio = 
    "SELECT * FROM annunci WHERE id=$id";

    try{
        $result_query_get_annuncio = $db->query($query_get_annuncio);
    }catch(PDOException $e){
        exit("query_failed");
    }

    if($result_query_get_annuncio->rowCount() == 1){
        exit(json_encode($result_query_get_annuncio->fetchAll()));
    }else{
        exit("annuncio_not_found");
    }
?>