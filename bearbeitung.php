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