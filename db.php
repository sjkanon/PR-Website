<?php
$servername = "localhost";
$username = "pr_djm"; // Je database gebruikersnaam
$password = "_7vd3nC37"; // Je database wachtwoord
$dbname = "techniekdjm_"; // Je database naam

// Verbinding maken
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>
