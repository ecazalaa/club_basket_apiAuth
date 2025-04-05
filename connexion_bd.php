<?php

function connectionBD(){

    $host = "mysql-apiauthclubbasket.alwaysdata.net";
    $dbname = "apiauthclubbasket_bd";
    $username = "403722";
    $password = "Agaboubou65$";




///Connexion au serveur MySQL
    try {
        $linkpdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    } catch (Exception $e) {
        die('Erreur  de connexion à la bd: ' . $e->getMessage());
    }
    return $linkpdo;

}

?>

