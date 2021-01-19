<?php

function dbconnect() {
	$db = new PDO('mysql:dbname=gamehub;host=localhost:3306', "root", "");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}

function isDate($dateString){
	return DateTime::createFromFormat('Y-m-d', $dateString) !== false;
	//allora l'oggetto viene creato correttamente e di conseguenza la stringa è nel formato giusto
}

function redirectWithError($path, $error){
	header("Location: $path?error=$error");
	exit;
}

function checkAndSaveImg($img, $path_to_save){
	/**
	 * -$img			File of the image to be checked
	 * -$path_to_save	Relative path to the folder where the image is supposed to be saved
	 * 
	 * This function returns an associative array where success is set to the name generated
	 * for the file in case everything went correctly. This name is unique in order to avoid 
	 * name conflicts in the folder.
	 * If something unexpected have happened then error is set to the error type 
	 */

	$result = array("success"=>null, "error"=>null);

	$fileName = $img['name'];
	$fileTmpName = $img['tmp_name'];
	$fileSize = $img['size'];
	$fileError = $img['error'];
	$fileType = $img['type'];

	$fileExt = explode(".", $fileName);
	$fileActualExt = strtolower(end($fileExt));
	$allowed = array("jpg", 'jpeg', 'png', 'gif');

	if(in_array($fileActualExt, $allowed)){
		//Controllo che il formato sia permesso.
		if($fileError === 0){
			//Controllo che non ci siano stati errori nel caricamento dell'immagine
			$fileNameNew = uniqid('true')."." . $fileActualExt;
			$fileDestination = $path_to_save . $fileNameNew;
			move_uploaded_file($fileTmpName, $fileDestination);

			$result['success'] = $fileNameNew;
			return $result;
		}
		else{
			//Errore nel caricamento dell\' immagine
			$result['error'] = "fileUploadError";
			return $result;
		}
	}

	else{
		//L\' immagine non è in un formato corretto.<br>I formati permessi sono: jpg, jpeg, png, gif.
		$result['error'] = "fileExtentionNotAllowed";
		return $result;
	}
}

?>