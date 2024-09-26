<?php
session_start();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepagina</title>
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

            <?php if (isset($_SESSION['gebruikersnaam'])): ?>
                <li><a href="profile.php">Profiel</a></li>
                <li><a href="logout.php">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="register.html">Registreren</a></li>
                <li><a href="login.php">Inloggen</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Content -->
    <div class="content">
        <h1>Welkom op MijnWebsite!</h1>
        <p>Hier kun je alles vinden over ons en onze diensten.</p>
        <?php if (isset($_SESSION['gebruikersnaam'])): ?>
            <p>Je bent ingelogd als <?php echo $_SESSION['gebruikersnaam']; ?>. Welkom terug!</p>
        <?php else: ?>
            <p>Log in of registreer om toegang te krijgen tot extra functies.</p>
        <?php endif; ?>
    </div>
</body>
</html>
