<?php

function dbconnect() {
        $db = new PDO('mysql:dbname=imdb;host=localhost:3306', "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
}

?>