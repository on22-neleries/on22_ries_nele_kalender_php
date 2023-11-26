<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'Kalender.php';

$calendar = new Calendar(new CurrentDate(), new CalendarDate());

// Include der Datenbankverbindungsdatei
include('config.php');

//Inlcude der Login-Funktion

// Überprüfen, ob ein bestimmter Monat ausgewählt wurde
if (isset($_GET['selectedMonth'])) {
    $selectedMonth = intval($_GET['selectedMonth']);
    $calendar->setMonth($selectedMonth);
}

// Verarbeite das Formular, wenn es gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $terminName = $_POST["terminName"];
    $terminDatum = $_POST["terminDatum"];
    $terminUhrzeit = $_POST["terminUhrzeit"];

    // SQL-Abfrage zum Einfügen der Daten in die Tabelle "termine" (ersetze die Tabelle und Spalten mit deinen Daten)
    $sql = "INSERT INTO termine (termin_name, termin_datum, termin_uhrzeit) VALUES ('$terminName', '$terminDatum', '$terminUhrzeit')";
    if ($con->query($sql) === TRUE) {
        echo "<p>Termin erfolgreich in der Datenbank hinzugefügt!</p>";
    } else {
        echo "<div class='error'>Fehler beim Hinzufügen des Termins: " . $database->error . "</div>";
    }
}

$calendar->setMondayFirst(true);

$calendar->create();

session_start();
// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['uid'])) {
    // Benutzer ist nicht angemeldet, zeige das Anmeldeformular
    include('login.php');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalender</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="container mt-4">
            <h1>Anmeldung</h1>
            <form method="post" action="">
                <label for="benutzername" class="form-label">Benutzername:</label>
                <input type="text" name="benutzername" class="form-control" required>
                <label for="passwort" class="form-label">Passwort:</label>
                <input type="password" name="passwort" class="form-control" required>
                <button type="submit" class="btn btn-primary">Anmelden</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

$calendar->fetchAppointmentsFromDatabase($con);
?>