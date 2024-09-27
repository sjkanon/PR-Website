<?php
session_start();
include 'db.php';

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $email = $_POST['email'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $sql = "INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $gebruikersnaam, $email, $wachtwoord, $rol);

    if ($stmt->execute()) {
        header("Location: gebruikers.php");
        exit();
    } else {
        echo "Fout bij het toevoegen van de gebruiker: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Gebruiker</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">PR Deventer Jeugd Musical</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="logout.php">Uitloggen</a></li>
        </ul>
    </nav>

    <div class="admin-container">
        <div class="sidebar">
            <h2>Admin Menu</h2>
            <a href="admin.php">Dashboard</a>
            <a href="gebruikers.php">Gebruikers Beheren</a>
            <a href="voeg_gebruiker_toe.php">Nieuwe Gebruiker</a>
        </div>

        <div class="content">
            <h1>Nieuwe Gebruiker Toevoegen</h1>
            <form method="POST" action="">
                <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
                <select name="rol" required>
                    <option value="gebruiker">Gebruiker</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit">Toevoegen</button>
            </form>
        </div>
    </div>
</body>
</html>
