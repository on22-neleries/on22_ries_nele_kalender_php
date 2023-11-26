<?php

    //Datenbankverbindung herstellen
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "kalender";

    $con = mysqli_connect($host, $username, $password, $database);

    //Überprüfen, ob die Verbindung erfolgreich hergestellt wurde
    if (!$con) {
        die("Verbindung zur Datenbank ist fehlgeschlagen: " . mysqli_connect_error());
    }

?>