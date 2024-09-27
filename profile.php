<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruikersnaam'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel</title>
    <link rel="stylesheet" href="css/maakjstyles.css">
</head>
<body>
    <!-- Navigatiebalk -->
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">MijnWebsite</a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.html">Over Ons</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php">Profiel</a></li>
            <li><a href="logout.php">Uitloggen</a></li>
        </ul>
    </nav>

    <!-- Profielpagina Inhoud -->
    <div class="content">
        <h1>Welkom op je profiel, <?php echo $_SESSION['gebruikersnaam']; ?>!</h1>
        <p>Dit is je persoonlijke profielpagina.</p>
    </div>
</body>
</html>
