<?php
require_once 'Kalender.php';
include('config.php');

// Überprüfen, ob das Formular gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentId = $_POST["appointmentId"];
    $terminName = $_POST["terminName"];
    $terminDatum = $_POST["terminDatum"];
    $terminUhrzeit = $_POST["terminUhrzeit"];

    // Kombinieren Sie Datum und Uhrzeit zu einem DateTime-Objekt
    $terminDateTime = new DateTime($terminDatum . ' ' . $terminUhrzeit);

    // Formatieren des DateTime-Objekts für die Datenbank
    $formattedDateTime = $terminDateTime->format('Y-m-d H:i:s');

    // SQL-Abfrage zum Aktualisieren der Daten in der Tabelle "termine"
    $sql = "UPDATE termine SET termin_name='$terminName', termin_datum='$formattedDateTime', termin_uhrzeit='$terminUhrzeit' WHERE Id=$appointmentId";

    if ($con->query($sql) === TRUE) {
        echo "<p>Termin erfolgreich aktualisiert!</p>";
        header("Location: index.php");exit();
        $con->close();
    } else {
        echo "Fehler beim Aktualisieren des Termins: " . $con->error;
    }
}
?>