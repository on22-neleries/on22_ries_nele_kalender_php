<?php
    // Include der Datenbankverbindungsdatei
    include('config.php');

    // Verarbeite das Formular zum Löschen, wenn es gesendet wurde
    if(!isset($_SESSION['uid']) || !isset($_GET['id']))
    header("Location: index.php");

    $deleteID = intval($_GET["id"]);
    // Hier die SQL-Abfrage zum Löschen des Termins
    $sql = "DELETE FROM termine WHERE id = $deleteID";

    if ($con->query($sql) === TRUE) {
        echo "<p>Termin erfolgreich gelöscht!</p>";
        header("Location: index.php");
    } else {
        echo "<div class='error'>Fehler beim Löschen des Termins: " . $con->error . "</div>";
    }
    
?>