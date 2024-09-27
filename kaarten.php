<?php
session_start();
require_once 'db.php';

// Fetch data for the graph
$sql = "SELECT datum, zaterdag, zondag, totaal FROM kaartentellen_2023 ORDER BY datum";
$result = $conn->query($sql);

$dates = [];
$zaterdag = [];
$zondag = [];
$totaal = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dates[] = $row["datum"];
        $zaterdag[] = $row["zaterdag"];
        $zondag[] = $row["zondag"];
        $totaal[] = $row["totaal"];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaartentellen 2023 Grafiek</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <li><a href="kaarten.php">Kaarten</a></li>
                <li><a href="profile.php">Profiel</a></li>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="register.php">Registreren</a></li>
                <li><a href="login.php">Inloggen</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Content -->
    <div class="content">
        <h1>Kaartentellen 2023 Grafiek</h1>
        <p>Hieronder vindt u een grafiek van de kaartverkoop voor 2023.</p>
        
        <?php if (isset($_SESSION['gebruikersnaam'])): ?>
            <p>Je bent ingelogd als <?php echo $_SESSION['gebruikersnaam']; ?>.</p>
        <?php endif; ?>

        <!-- Graph canvas -->
        <canvas id="myChart" style="width:100%; max-width:800px; margin:auto;"></canvas>

        <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Zaterdag',
                    data: <?php echo json_encode($zaterdag); ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }, {
                    label: 'Zondag',
                    data: <?php echo json_encode($zondag); ?>,
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }, {
                    label: 'Totaal',
                    data: <?php echo json_encode($totaal); ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>
    </div>
</body>
</html>