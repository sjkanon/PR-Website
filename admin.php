<?php
session_start();
require 'config.php';

if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Optioneel: Haal gebruikersinformatie op om in het dashboard weer te geven
$sql = "SELECT id, gebruikersnaam, email, rol FROM gebruikers";
$result = $conn->query($sql);
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
            <li><a href="logout.php">Uitloggen</a></li>
        </ul>
    </nav>

    <!-- Admin Dashboard Content -->
    <div class="admin-container">
        <h1>Welkom, Admin</h1>
        <p>Beheer gebruikers en statistieken.</p>

        <!-- Gebruikersbeheer -->
        <div class="table-container">
            <h2>Gebruikersbeheer</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gebruikersnaam</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['gebruikersnaam']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['rol']; ?></td>
                        <td><a href="#">Bewerk</a> | <a href="#">Verwijder</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
