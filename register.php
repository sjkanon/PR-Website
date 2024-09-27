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
    <link rel="stylesheet" href="css\styles.css">
</head>
<body>

<nav class="navbar">
        <div class="logo">
            <a href="index.php">PR Deventer Jeugd Musical</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.html">Over Ons</a></li>
            <li><a href="contact.html">Contact</a></li>

            <?php if (isset($_SESSION['gebruikersnaam'])): ?>
                <li><a href="kaarten.php">Kaarten</a></li>
                <li><a href="profile.php">Profiel</a></li>
                <li><a href="logout.php">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="register.php">Registreren</a></li>
                <li><a href="login.php">Inloggen</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="form-container">
        <div class="form-box modern">
            <h2>Registreren</h2>
            <form action="register_handler.php" method="POST">
                <input type="text" name="username" placeholder="Gebruikersnaam" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="password" placeholder="Wachtwoord" required>
                <input type="password" name="confirm_password" placeholder="Bevestig wachtwoord" required>
                <button type="submit">Registreer</button>
            </form>
            <p>Al een account? <a href="login.php">Log hier in</a></p>
        </div>
    </div>

</body>
</html>
