<?php
    $PATH_TO_INDEX = "../html/complete/index.shtml";
    $PATH_TO_NEW_ADVERT = "../html/complete/new-advert.shtml";
    $PATH_TO_IMG_FOLDER = "../img/advert-img";

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

    //check if input is inserted
    if(!isset($_POST['piattaforma']) || !isset($_POST['titolo'])){
        redirectWithError($PATH_TO_NEW_ADVERT,"variables_not_set");
    }
    $piattaforma = $db->quote($_POST['piattaforma']);   
    $titolo = $db->quote($_POST['titolo']);
    $testo = $db->quote($_POST['testo']);

    //check if input too long
    if(strlen($_POST['titolo'])>50 || strlen($_POST['testo'])>300 || strlen($titolo) > 100 || strlen($testo) > 500){
        redirectWithError($PATH_TO_NEW_ADVERT, "text_fields_too_long");
    }

    //Check piattaforma
    $queryPiattaforme = "
    SELECT nome
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
    
    $piattaformeDisponibili = $resultQueryPiattaforme->fetchAll(PDO::FETCH_COLUMN);

    $piattaformaValid = false;
    $i = 0;
    while(!$piattaformaValid && $i < count($piattaformeDisponibili)){
        $p = $piattaformeDisponibili[$i];
        if($db->quote($p) == $piattaforma){
            $piattaformaValid = true;
        }
        $i++;
    }

    if(!$piattaformaValid){
        redirectWithError($PATH_TO_NEW_ADVERT, "piattaforma_unexpected");
    }

    //image check
    $imagePath = null;
    if(isset($_FILES["immagine"]["tmp_name"]) && is_uploaded_file($_FILES['immagine']['tmp_name'])){
        //Image is inserted, we need to check it and save it
        $imageSaveResult = checkAndSaveImg($_FILES['immagine'], $PATH_TO_IMG_FOLDER);
        if($imageSaveResult['error'] != null){
            //Error in saving image occured, we give feedback to caller
            redirectWithError($PATH_TO_NEW_ADVERT, $imageSaveResult['error']);
        }else if(($imageSaveResult['success'])!=null){
            //Image was saved correctly, we save the name in the database
            $imagePath = "'".$imageSaveResult['success']."'";
        }else{
            //If the computation arrives here something strange happened
            redirectWithError($PATH_TO_NEW_ADVERT, "unexpected_image_error");
        }
    }

   
    $queryNewAdvert = 
    "INSERT INTO `annunci` VALUES
    (null, '$email', $piattaforma, $titolo, $testo, $imagePath);
    ";

    try{
        $result = $db->query($queryNewAdvert);
    }catch(Exception $e){
        unlink($PATH_TO_IMG_FOLDER.'/'.$imageSaveResult['success']);
        redirectWithError($PATH_TO_NEW_ADVERT,"query_failed");
    }

    header("Location: $PATH_TO_NEW_ADVERT?success=true");
    exit;

?>