<?php
include('Kalender.php');
include('config.php');

// Überprüfen, ob die ID im URL-Parameter vorhanden ist

if(isset($_GET['id'])){
    session_start();
    if(!isset($_SESSION['uid']))
        header("Location: index.php");
    $appointmentData = Calendar::fetchAppointmentById($_GET['id'], $con);
} else {
    die("Keine ID angegeben!");
}

if (empty($appointmentData))
    die("Der gesuchte Termin konnte nicht gefunden werden!");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Bearbeiten</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Handlee&family=Roboto+Condensed&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <form method="post" action="update.php" class="bearbeitungs-box">
                <!-- Verstecktes Feld für die Termin-ID -->
                <input type="hidden" name="appointmentId" value="<?php echo $_GET['id']; ?>">

                <h1>Bearbeite deinen Termin</h1>

                <br><label for="terminName">Terminname:</label>
                <input type="text" id="terminName" name="terminName" value="<?php echo $appointmentData['termin_name']; ?>" required class="date-input" style="width: 150px;"></br>

                <br><label for="terminDatum">Datum:</label>
                <input type="date" id="terminDatum" name="terminDatum" value="<?php echo $appointmentData['termin_datum']; ?>" required class="date-input" style="width: auto;"></br>

                <br><label for="terminUhrzeit">Uhrzeit:</label>
                <input type="time" id="terminUhrzeit" name="terminUhrzeit" value="<?php echo $appointmentData['termin_uhrzeit']; ?>" required class="date-input" style="width: auto;"></br>

                <br><input type="submit" value="Termin bearbeiten" class="bearbeiten-button"></br>
            </form>
        </div>
    </body>
</html>