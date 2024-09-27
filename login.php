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
    <div class="form-container">
        <div class="form-box">
            <h2>Inloggen</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="login.php" method="POST">
                <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
            <p>Nog geen account? <a href="register.php">Registreren</a></p>
        </div>
    </div>
</body>
</html>
