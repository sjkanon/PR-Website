<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $email = $_POST['email'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $rol = 'gebruiker'; // Standaard rol

    $sql = "INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $gebruikersnaam, $email, $wachtwoord, $rol);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Fout bij registreren: " . $stmt->error;
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
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">PR Deventer Jeugd Musical</a>
        </div>
    </nav>

    <div class="content">
        <h1>Registreren</h1>
        <form method="POST" action="">
            <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            <button type="submit">Registreren</button>
        </form>
    </div>
</body>
</html>
