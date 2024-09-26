<?php
session_start(); // Start de sessie

// Verbind met de database (gebruik de juiste gegevens)
$conn = new mysqli('localhost', 'pr_djm', '_7vd3nC37', 'techniekdjm_');

// Controleer of de verbinding is geslaagd
if ($conn->connect_error) {
    die("Verbindingsfout: " . $conn->connect_error);
}

// Controleer of het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg de ingevoerde gegevens
    $gebruikersnaam = $conn->real_escape_string($_POST['gebruikersnaam']);
    $wachtwoord = $_POST['wachtwoord'];

    // Bereid een SQL-statement voor om SQL-injectie te voorkomen
    $stmt = $conn->prepare("SELECT id, wachtwoord FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $gebruikersnaam);
    $stmt->execute();
    $stmt->store_result();

    // Controleer of de gebruiker bestaat
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        // Verifieer het ingevoerde wachtwoord met het gehashte wachtwoord in de database
        if (password_verify($wachtwoord, $hashed_password)) {
            $_SESSION['gebruikersnaam'] = $gebruikersnaam;
            echo "Succesvol ingelogd! Welkom, " . $gebruikersnaam;
        } else {
            echo "Ongeldig wachtwoord.";
        }
    } else {
        echo "Gebruiker niet gevonden.";
    }
    $stmt->close();
}
$conn->close();
?>
