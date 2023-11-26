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