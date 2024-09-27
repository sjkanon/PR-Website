<?php
session_start();

// Controleer of de gebruiker is ingelogd en een admin is
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navigatiebalk -->
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">PR Deventer Jeugd Musical</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.html">Over Ons</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="kaarten.php">Kaarten</a></li>
            <li><a href="profile.php">Profiel</a></li>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="logout.php">Uitloggen</a></li>
        </ul>
    </nav>

    <!-- Admin Dashboard Content -->
    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        <p>Welkom, <?php echo $_SESSION['gebruikersnaam']; ?>!</p>
        
        <h2>Beheer Gebruikers</h2>
        <ul>
            <li><a href="new_user.php">Nieuwe Gebruiker Toevoegen</a></li>
            <li><a href="view_users.php">Bekijk Alle Gebruikers</a></li>
        </ul>
        
        <h2>Statistieken</h2>
        <div class="stats">
            <div class="stat-box">
                <h3>Totaal Aantal Gebruikers</h3>
                <p><!-- Voeg hier de totale gebruikerscode in --></p>
            </div>
            <div class="stat-box">
                <h3>Totaal Aantal Boekingen</h3>
                <p><!-- Voeg hier het totale boekingen code in --></p>
            </div>
        </div>
    </div>
</body>
</html>
