<?php
// Verbinding maken met de database
$conn = new mysqli('localhost', 'root', '', 'gebruikersDB');

// Controleren of de verbinding is geslaagd
if ($conn->connect_error) {
    die("Verbindingsfout: " . $conn->connect_error);
}

// Verkrijg de ingevoerde gegevens van het formulier
$gebruikersnaam = $_POST['gebruikersnaam'];
$wachtwoord = $_POST['wachtwoord'];

// Het wachtwoord veilig hashen
$hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

// Voeg de nieuwe gebruiker toe aan de database
$sql = "INSERT INTO gebruikers (gebruikersnaam, wachtwoord) VALUES ('$gebruikersnaam', '$hashed_wachtwoord')";

if ($conn->query($sql) === TRUE) {
    echo "Nieuwe gebruiker succesvol geregistreerd.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
