<?php
session_start();
include 'db.php';

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikers Beheren</title>
    <link rel="stylesheet" href="css/admin.css">
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
            <h1>Gebruikersbeheer</h1>
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
                    <?php
                    $sql = "SELECT * FROM gebruikers";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['gebruikersnaam']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['rol']}</td>
                                <td>
                                    <a href='bewerk_gebruiker.php?id={$row['id']}'>Bewerk</a> | 
                                    <a href='verwijder_gebruiker.php?id={$row['id']}'>Verwijder</a>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
