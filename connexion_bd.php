<?php

function connectionBD(){

    $host = "mysql-clubbasket.alwaysdata.net";
    $dbname = "clubbasket_auth";
    $username = "388209";
    $password = "Agaboubou65$";

    /*
    $host = "localhost";
    $dbname = "clubbasket_auth";
    $username = "root";
    $password = "root";
*/


///Connexion au serveur MySQL
    try {
        $linkpdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    } catch (Exception $e) {
        die('Erreur  de connexion à la bd: ' . $e->getMessage());
    }
    return $linkpdo;

}
try {
    $pdo = connectionBD();
} catch (Exception $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}

?>

