<?php

function dbconnect() {
	$db = new PDO('mysql:dbname=imdb;host=localhost:3306', "root", "");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}

function isDate($dateString){
	return DateTime::createFromFormat('Y-m-d', $dateString) !== false;
	//allora l'oggetto viene creato correttamente e di conseguenza la stringa è nel formato giusto
}

?>