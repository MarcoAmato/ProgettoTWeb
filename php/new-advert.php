<?php
    $PATH_TO_INDEX = "../html/complete/index.shtml";
    $PATH_TO_NEW_ADVERT = "../html/complete/new-advert.shtml";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        header("HTTP/1.1 400 Invalid Request");
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    session_start();
    if(!isset($_SESSION['email'])){
        redirectWithError($PATH_TO_INDEX, "access_denied");
    }

    $email = $_SESSION['email'];

    try {
        $db = dbconnect();
    } catch (PDOException $e) {
        redirectWithError($PATH_TO_NEW_ADVERT, "server_down");
    }

    //image check
    $imagePath = null;
    if(is_uploaded_file($_FILES['immagine']['tmp-name'])){
        //Image is inserted, we need to check it and save it
        $imageSaveResult = checkAndSaveImg($_FILES['immagine']);
        if($imageSaveResult['error'] != null){
            //Error in saving image occured, we give feedback to caller
            redirectWithError($PATH_TO_NEW_ADVERT, $imageSaveResult['error']);
        }else if(isset($imageSaveResult['success'])){
            //Image was saved correctly, we save the name in the database
            $imagePath = $imageSaveResult['success'];
        }else{
            //If the computation arrives here something strange happened
            redirectWithError($PATH_TO_NEW_ADVERT, "unexpected_image_error");
        }
    }

    //check if input is inserted
    if(!isset($_POST['piattaforma']) || !isset($_POST['titolo'])){
        redirectWithError($PATH_TO_NEW_ADVERT,"variables_not_set");
    }
    $piattaforma = $db->quote($_POST['piattaforma']);   
    $titolo = $db->quote($_POST['titolo']);
    $testo = $db->quote($_POST['testo']);

    //check if input too long
    if(strlen($_POST['titolo'])>50 || strlen($_POST['testo'])>300 || strlen($titolo) > 100 || strlen($testo) > 500){
        redirectWithError($PATH_TO_NEW_ADVERT, "text_fields_too_long")
    }

    //Check piattaforma
        $queryPiattaforme = "
        SELECT *
        FROM piattaforme
        ORDER BY nome
        ";

        try{
            $resultQueryPiattaforme = $db->query($queryPiattaforme);
        }catch(Exception $e){
            redirectWithError($PATH_TO_NEW_ADVERT, 'query_failed');
        }

        if(!$resultQueryPiattaforme){
            redirectWithError($PATH_TO_NEW_ADVERT, 'query_failed');
        }
        
        $piattaformeDisponibili = $resultQueryPiattaforme->fetchColumn();

        if(!in_array($piattaforma, $piattaformeDisponibili)){
            redirectWithError($PATH_TO_NEW_ADVERT, "piattaforma_unexpected");
        }
    
    $queryNewAdvert = 
    "INSERT INTO `annunci` VALUES
    (null, '$email', $piattaforma, $titolo, $testo, '$imagePath');
    "
    try{
        $result = $db->query($insert_query);
    }catch(Exception $e){
        redirectWithError($PATH_TO_NEW_ADVERT,"query_failed");
    }

    if(!$result){
        //result is null = error in processing query
        redirectWithError($PATH_TO_NEW_ADVERT, "query_failed");
    }else{
        header("Location: $PATH_TO_NEW_ADVERT?success=true");
        exit;
    }

?>