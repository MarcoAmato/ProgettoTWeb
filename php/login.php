<?php
    $PATH_TO_REDIRECT = "../html/complete/index.html";

    if(!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"]!="POST"){
        exit("ERROR 400: Invalid request - This service accepts only POST requests.");
    }

    include './common.php';

    session_start();
    if(isset($_SESSION['username'])){
        header("Location: $PATH_TO_REDIRECT");
    }

    try{
        $db = dbconnect();
    }catch(PDOException $e){
        redirectWithError($PATH_TO_REDIRECT, "server down");
    }

    $email = $db->quote($_GET['email']);
    $hashed_password = password_hash($db->quote($_GET['password']), PASSWORD_DEFAULT);

    $select_user_from_password = "
    SELECT * 
    FROM utenti 
    WHERE utenti.password = '$hashed_password'"

    $result = $db->query($select_user_from_password);

    if(!$result){
        redirectWithError($PATH_TO_REDIRECT, "query failed");
    }

    $user = $result->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        redirectWithError($PATH_TO_REDIRECT, "user not found");
    }

    //fai tornare alla pagina precedente con messaggio di successo nel caso in cui l'esecuzione del codice php arriva fino a qui
    //IDEA si può usare javascript per fare una richiesta asincrona quando viene cliccato il bottone accedi. Nel caso di utente non trovato si da errore e si aggiorna l'header tramite js, altrimenti, sempre con js si aggiorna l'header inserendo il login
?>