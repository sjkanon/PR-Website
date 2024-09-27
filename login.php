<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    $sql = "SELECT id, gebruikersnaam, wachtwoord, rol FROM gebruikers WHERE gebruikersnaam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $gebruikersnaam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($wachtwoord, $row['wachtwoord'])) {
            $_SESSION['gebruikersnaam'] = $row['gebruikersnaam'];
            $_SESSION['rol'] = $row['rol'];
            if ($_SESSION['rol'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
        } else {
            echo "Ongeldige inloggegevens.";
        }
    } else {
        echo "Gebruiker niet gevonden.";
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
        <h2>Inloggen</h2>
        <form action="login.php" method="POST">
            <label for="gebruikersnaam">Gebruikersnaam:</label>
            <input type="text" name="gebruikersnaam" required>

            <label for="wachtwoord">Wachtwoord:</label>
            <input type="password" name="wachtwoord" required>

            <button type="submit">Inloggen</button>
        </form>
    </div>
</body>
</html>
