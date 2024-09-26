<?php
session_start();
require 'db.php'; // Zorg ervoor dat je de db.php bestande hebt

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];
    $gehashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT); // Hash het wachtwoord

    // Controleer of de gebruikersnaam al bestaat
    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->execute([$gebruikersnaam]);
    if ($stmt->rowCount() > 0) {
        echo "Deze gebruikersnaam is al in gebruik.";
    } else {
        // Voeg de nieuwe gebruiker toe aan de database
        $stmt = $pdo->prepare("INSERT INTO gebruikers (gebruikersnaam, wachtwoord) VALUES (?, ?)");
        if ($stmt->execute([$gebruikersnaam, $gehashed_wachtwoord])) {
            echo "Registratie succesvol! Je kunt nu inloggen.";
            header("Location: login.php"); // Redirect naar login pagina
            exit();
        } else {
            echo "Er is een fout opgetreden tijdens de registratie.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Registreren</h2>
        <form action="register.php" method="POST">
            <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            <button type="submit">Registreren</button>
        </form>
        <p><a href="login.php">Al geregistreerd? Inloggen</a></p>
    </div>
</body>
</html>
