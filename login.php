<?php
session_start();
require 'db.php'; // Zorg ervoor dat je de db.php bestande hebt

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // Controleer of gebruikersnaam en wachtwoord zijn ingevoerd
    if (empty($gebruikersnaam) || empty($wachtwoord)) {
        echo "Vul alstublieft beide velden in.";
    } else {
        // Verkrijg de gegevens van de gebruiker
        $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
        $stmt->execute([$gebruikersnaam]);
        $gebruiker = $stmt->fetch();

        // Controleer of de gebruiker bestaat en het wachtwoord klopt
        if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            $_SESSION['gebruikersnaam'] = $gebruikersnaam;
            header("Location: index.php"); // Verwijs naar de homepage
            exit();
        } else {
            echo "Ongeldige gebruikersnaam of wachtwoord.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Inloggen</h2>
        <form action="login.php" method="POST">
            <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            <button type="submit">Inloggen</button>
        </form>
        <p><a href="register.html">Registreren</a></p>
    </div>
</body>
</html>
