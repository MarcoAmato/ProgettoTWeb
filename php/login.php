<?php
    $PATH_TO_REDIRECT = "../html/complete/index.html";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    session_start();
    if(isset($_SESSION['email'])){
        echo 'already logged';
        exit;
    }

    try{
        $db = dbconnect();
    }catch(PDOException $e){
        echo "server down";
        exit;
    }

    if(isset($_POST['email'])){
        $email = $db->quote($_POST['email']); //errore qui
    }else{
        echo 'email missing';
        exit;
    }

    if(isset($_POST['password'])){
        $password = $db->quote($_POST['password']);
    }else{
        echo "password missing";
        exit;
    }
    
    $select_user_from_password = "
    SELECT * 
    FROM utenti 
    WHERE utenti.email = $email";

    $result = null;
    try{
        $result = $db->query($select_user_from_password);
    }catch(Exception $e){
        echo "query failed";
        exit;
    }

    $user = $result->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        echo "user not found";
        exit;
    }else{
        if(!password_verify($password, $user['password'])){
            echo "wrong password for user";
            exit;
        }
    }

    //If we arrive here everything is okay
    //Setting session variables
    $_SESSION['email'] = $user['email'];

    $userToAssArray = array("email"=>$user['email']);

    $userJSON = json_encode($userToAssArray);

    echo $userJSON;
    //fai tornare alla pagina precedente con messaggio di successo nel caso in cui l'esecuzione del codice php arriva fino a qui
    //IDEA si può usare javascript per fare una richiesta asincrona quando viene cliccato il bottone accedi. Nel caso di utente non trovato si da errore e si aggiorna l'header tramite js, altrimenti, sempre con js si aggiorna l'header inserendo il login
    exit;
?>