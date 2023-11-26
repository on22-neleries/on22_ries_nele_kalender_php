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

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calender</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <?php 
        if(isset($_GET['id'])){
            session_start();
            if (!isset($_SESSION['uid'])){
                header("Location: index.php");
            }
            $appointmentData = Calendar::fetchAppointmentById($_GET['id'], $con);
        }else{
            die("Keine ID angegeben!");
        }
        if (empty($appointmentData))
            die("Der gesuchte Termin konnte nicht gefunden werden!");
        ?>

        <h1>Dein Termin</h1>
        <h2>Name: <?php echo $appointmentData['termin_name'] ?></h2>
        <h2>Datum: <?php echo $appointmentData['termin_datum'] ?></h2>
        <h2>Uhrzeit: <?php echo $appointmentData['termin_uhrzeit'] ?></h2>

        <a href="bearbeitung.php?id=<?php echo $_GET['id']; ?>" class="update-btn">Termin bearbeiten</a>

        <a href="delete.php?id=<?php echo $_GET['id']; ?>" class="delete-btn">Termin löschen</a>
    </body>
</html>