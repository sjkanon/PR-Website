<?php
$servername = "localhost";
$username = "pr_djm";
$password = "_7vd3nC37";
$dbname = "techniekdjm";

// Verbind met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>
