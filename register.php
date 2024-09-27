<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];
    $gehashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->execute([$gebruikersnaam]);

    if ($stmt->rowCount() > 0) {
        $error = "Deze gebruikersnaam is al in gebruik.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO gebruikers (gebruikersnaam, wachtwoord) VALUES (?, ?)");
        if ($stmt->execute([$gebruikersnaam, $gehashed_wachtwoord])) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Er is een fout opgetreden tijdens de registratie.";
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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <div class="form-box">
            <h2>Registreren</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="register.php" method="POST">
                <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit">Registreren</button>
            </form>
            <p>Al een account? <a href="login.php">Inloggen</a></p>
        </div>
    </div>
</body>
</html>
