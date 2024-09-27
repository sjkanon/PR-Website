<?php
session_start();

// Controleer of de gebruiker is ingelogd en een admin is
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Database connectie
$host = 'localhost'; // Of je database host
$dbname = 'techniekdjm_';
$username = 'pr_djm';
$password = '_7vd3nC37';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database verbinding mislukt: " . $e->getMessage();
    exit();
}

// Haal het totaal aantal gebruikers op
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totaalGebruikers = $stmt->fetchColumn();
} catch (Exception $e) {
    $totaalGebruikers = 0; // Foutafhandeling
}

// Haal het totaal aantal boekingen op
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM bookings");
    $totaalBoekingen = $stmt->fetchColumn();
} catch (Exception $e) {
    $totaalBoekingen = 0; // Foutafhandeling
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
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
                <p><?php echo $totaalGebruikers; ?></p>
            </div>
            <div class="stat-box">
                <h3>Totaal Aantal Boekingen</h3>
                <p><?php echo $totaalBoekingen; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
