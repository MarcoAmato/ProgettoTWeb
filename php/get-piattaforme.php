<?php
    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "POST"){
        header("HTTP/1.1 400 Invalid Request");
        die("ERROR 400: Invalid request - This service accepts only GET requests.");
    }
    
    include './common.php';

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        exit('server down');
    }

    $queryPiattaforme = "
    SELECT *
    FROM piattaforme
    ORDER BY nome
    ";

    try{
        $result = $db->query($queryPiattaforme);
    }catch(Exception $e){
        exit('query failed');
    }

    if(!$result){
        exit('query failed');
    }
    
    echo (json_encode($result->fetchAll(PDO::FETCH_ASSOC)));
    exit;
?>