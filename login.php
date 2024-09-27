<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    if (empty($gebruikersnaam) || empty($wachtwoord)) {
        $error = "Vul alstublieft beide velden in.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
        $stmt->execute([$gebruikersnaam]);
        $gebruiker = $stmt->fetch();

        if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            $_SESSION['gebruikersnaam'] = $gebruikersnaam;
            header("Location: index.php");
            exit();
        } else {
            $error = "Ongeldige gebruikersnaam of wachtwoord.";
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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <a href="index.php">MijnWebsite</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="register.php">Registreren</a></li>
            <li><a class="active" href="login.php">Inloggen</a></li>
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-box modern">
            <h2>Inloggen</h2>
            <form action="login_handler.php" method="POST">
                <input type="text" name="username" placeholder="Gebruikersnaam" required>
                <input type="password" name="password" placeholder="Wachtwoord" required>
                <button type="submit">Log in</button>
            </form>
            <p>Nog geen account? <a href="register.php">Registreer hier</a></p>
        </div>
    </div>

</body>
</html>
