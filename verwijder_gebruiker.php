<?php
session_start();
include 'db.php';

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Controleer of het ID van de gebruiker is opgegeven
if (!isset($_GET['id'])) {
    header("Location: gebruikers.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM gebruikers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: gebruikers.php");
    exit();
} else {
    echo "Fout bij het verwijderen van de gebruiker: " . $stmt->error;
}
?>
