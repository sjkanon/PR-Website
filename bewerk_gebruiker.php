<?php
session_start();
include 'db.php';

// Controleer of de gebruiker is ingelogd en adminrechten heeft
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Controleer of het ID van de gebruiker is opgegeven
if (!isset($_GET['id'])) {
    header("Location: gebruikers.php");
    exit();
}

$id = $_GET['id'];

// Verkrijg de gebruikersgegevens
$sql = "SELECT * FROM gebruikers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $sql = "UPDATE gebruikers SET gebruikersnaam = ?, email = ?, rol = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $gebruikersnaam, $email, $rol, $id);

    if ($stmt->execute()) {
        header("Location: gebruikers.php");
        exit();
    } else {
        echo "Fout bij het bijwerken van de gebruiker: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerk Gebruiker</title>
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
            <h1>Bewerk Gebruiker</h1>
            <form method="POST" action="">
                <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" value="<?php echo $user['gebruikersnaam']; ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" required>
                <select name="rol" required>
                    <option value="gebruiker" <?php if ($user['rol'] == 'gebruiker') echo 'selected'; ?>>Gebruiker</option>
                    <option value="admin" <?php if ($user['rol'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
                <button type="submit">Bijwerken</button>
            </form>
        </div>
    </div>
</body>
</html>
