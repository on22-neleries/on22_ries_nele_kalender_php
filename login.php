<?php
// Include der Datenbankverbindungsdatei
include('config.php');

// Überprüfen, ob das Formular gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $benutzername = $_POST["benutzername"];
    $passwort = $_POST["passwort"];

    // SQL-Abfrage zur Überprüfung der Anmeldedaten
    $sql = "SELECT id FROM login WHERE benutzername = '$benutzername' AND passwort = '$passwort'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Anmeldung erfolgreich
        session_start();
        $_SESSION['uid'] = $result->fetch_assoc()['id'];
        header("Location: index.php");
        exit();
    } else {
        // Anmeldung fehlgeschlagen
        echo "<p>Anmeldung fehlgeschlagen. Überprüfen Sie Benutzername und Passwort.</p>";
    }
}

?>