<?php

    $server = "localhost";
    $database = "podsql4";
    $user = "podsql4";
    $pwd = "Passwort01.!";

    //verbindung als try und catch damit programmfehler sauber abgefangen werden
    try {
        $connection = new PDO("mysql:host=$server;dbname=$database", $user, $pwd);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo "<p>Es konnte keine Verbindung zur Datenbank hergestellt werden: " . $e->getMessage() . "</p>";
    }

?>