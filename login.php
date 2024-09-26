<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$conn = new mysqli('localhost', 'pr_djm', '_7vd3nC37' , 'techniekdjm_');

if ($conn->connect_error){
    die("Verbindingsfout: " . $conn->connect_error);
}

$gebruikersnaam = $_POST['gebruikersnaam'];
$wachtwoord = $_POST['wachtwoord']

$gebruikersnaam = $conn->real_escape_string($gebruikersnaam);
$sql = "SELECT * FROM gebruikers WHERE gebruikersnaam = '$gebruikersnaam'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Controleer het ingevoerde wachtwoord met het gehashte wachtwoord in de database
    if (password_verify($wachtwoord, $row['wachtwoord'])) {
        $_SESSION['gebruikersnaam'] = $gebruikersnaam; // Zet de sessie
        echo "Succesvol ingelogd! Welkom, " . $gebruikersnaam;
        // Redirect naar dashboard of andere pagina
    } else {
        echo "Ongeldig wachtwoord.";
    }
} else {
    echo "Gebruiker niet gevonden.";
}

$conn->close();
?>