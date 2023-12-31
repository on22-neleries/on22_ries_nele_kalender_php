<?php
// create.php - Datei für die Funktion zum Hinzufügen von Terminen

// Include der Datenbankverbindungsdatei
include('config.php');

// Verarbeite das Formular, wenn es gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $terminName = $_POST["terminName"];
    $terminDatum = $_POST["terminDatum"];
    $terminUhrzeit = $_POST["terminUhrzeit"]; // Neu hinzugefügt

    // Kombiniere Datum und Uhrzeit zu einem DateTime-Objekt
    $terminDateTime = new DateTime($terminDatum . ' ' . $terminUhrzeit);

    // Formatieren des DateTime-Objekts für die Datenbank
    $formattedDateTime = $terminDateTime->format('Y-m-d H:i:s');

    // SQL-Abfrage zum Einfügen der Daten in die Tabelle "termine" (ersetze die Tabelle und Spalten mit deinen Daten)
    $sql = "INSERT INTO termine (termin_name, termin_datum, termin_uhrzeit, user_id) VALUES ('$terminName', '$formattedDateTime', '$terminUhrzeit',". $_SESSION["uid"].")";

    if ($con->query($sql) === TRUE) {
        echo "<p>Termin erfolgreich in der Datenbank hinzugefügt!</p>";
        header("Location: index.php");
    } else {
        echo "Fehler beim Hinzufügen des Termins: " . $con->error;
    }
}

// Verbindung zur Datenbank schließen (optional, da PHP die Verbindung am Ende des Skripts automatisch schließt)
$con->close();
?>

<!-- HTML-Formular für die Eingabe der Termindaten -->
<form method="post" action="./create.php" class="date-box">
    </br><label for="terminName">Terminname:</label>
    <input type="text" id="terminName" name="terminName" required class="date-input" style="width: 150px;"></br>

    <br><label for="terminDatum">Datum:</label>
    <input type="date" id="terminDatum" name="terminDatum" required class="date-input" style="width: auto;"></br>

    <br><label for="terminUhrzeit">Uhrzeit:</label>
    <input type="time" id="terminUhrzeit" name="terminUhrzeit" required class="date-input" style="width: auto;"></br>

    <input type="submit" value="Termin hinzufügen" class="date-button">
</form>