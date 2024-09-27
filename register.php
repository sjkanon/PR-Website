<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $email = $_POST['email'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_BCRYPT);
    $rol = 'gebruiker'; // Standaardrol

    $sql = "INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $gebruikersnaam, $email, $wachtwoord, $rol);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registratie succesvol!";
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Er is iets misgegaan, probeer het opnieuw.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Registreren</h2>
        <form action="register.php" method="POST">
            <label for="gebruikersnaam">Gebruikersnaam:</label>
            <input type="text" name="gebruikersnaam" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" required>

            <label for="wachtwoord">Wachtwoord:</label>
            <input type="password" name="wachtwoord" required>

            <button type="submit">Registreren</button>
        </form>
    </div>
</body>
</html>
