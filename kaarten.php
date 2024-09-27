<?php
session_start();
require_once 'db.php';

// Fetch data for 2023
$sql2023 = "SELECT datum, zaterdag, zondag, totaal FROM kaartentellen_2023 ORDER BY datum";
$result2023 = $conn->query($sql2023);

$dates2023 = [];
$zaterdag2023 = [];
$zondag2023 = [];
$totaal2023 = [];

if ($result2023->num_rows > 0) {
    while($row = $result2023->fetch_assoc()) {
        $dates2023[] = $row["datum"];
        $zaterdag2023[] = $row["zaterdag"];
        $zondag2023[] = $row["zondag"];
        $totaal2023[] = $row["totaal"];
    }
}

// Fetch data for 2024
$sql2024 = "SELECT datum, zaterdag, zondag, totaal FROM kaartentellen_2024 ORDER BY datum";
$result2024 = $conn->query($sql2024);

$dates2024 = [];
$zaterdag2024 = [];
$zondag2024 = [];
$totaal2024 = [];

if ($result2024->num_rows > 0) {
    while($row = $result2024->fetch_assoc()) {
        $dates2024[] = $row["datum"];
        $zaterdag2024[] = $row["zaterdag"];
        $zondag2024[] = $row["zondag"];
        $totaal2024[] = $row["totaal"];
    }
}

$conn->close();

// Calculate the difference between 2024 and 2023 totals
$lastTotal2023 = end($totaal2023);
$lastTotal2024 = end($totaal2024);
$totalDifference = $lastTotal2024 - $lastTotal2023;

// Calculate the difference for each day
$diffDates = [];
$diffTotals = [];
$maxDays = max(count($dates2023), count($dates2024));
for ($i = 0; $i < $maxDays; $i++) {
    $diffDates[] = isset($dates2024[$i]) ? $dates2024[$i] : $dates2023[$i];
    $total2024 = isset($totaal2024[$i]) ? $totaal2024[$i] : 0;
    $total2023 = isset($totaal2023[$i]) ? $totaal2023[$i] : 0;
    $diffTotals[] = $total2024 - $total2023;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaartentellen 2023 vs 2024 Vergelijking</title>
    <link rel="stylesheet" href="css/kaarten.css">
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

    <div class="container">
        <!-- Left Menu -->
        <div class="menu">
            <h3>Menu</h3>
            <ul>
                <li><a href="#graph2023">Grafiek 2023</a></li>
                <li><a href="#graph2024">Grafiek 2024</a></li>
                <li><a href="#graphDifference">Verschil Grafiek</a></li>
                <li><a href="#comparison">Vergelijking</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Kaartentellen 2023 vs 2024 Vergelijking</h1>

            <div id="graph2023" class="chart-container">
                <h2>Kaartentellen 2023</h2>
                <canvas id="myChart2023"></canvas>
            </div>

            <div id="graph2024" class="chart-container">
                <h2>Kaartentellen 2024</h2>
                <canvas id="myChart2024"></canvas>
            </div>

            <div id="graphDifference" class="chart-container">
                <h2>Verschil 2024 - 2023</h2>
                <canvas id="myChartDifference"></canvas>
            </div>

            <div id="comparison">
                <h2>Vergelijking 2023 vs 2024</h2>
                <p>Totaal aantal kaarten 2023: <?php echo $lastTotal2023; ?></p>
                <p>Totaal aantal kaarten 2024: <?php echo $lastTotal2024; ?></p>
                <p>Verschil in totaal aantal kaarten: <?php echo $totalDifference; ?></p>
            </div>

            <script>
            function createChart(canvasId, labels, zaterdag, zondag, totaal) {
                var ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Zaterdag',
                            data: zaterdag,
                            borderColor: 'rgb(255, 99, 132)',
                            tension: 0.1
                        }, {
                            label: 'Zondag',
                            data: zondag,
                            borderColor: 'rgb(54, 162, 235)',
                            tension: 0.1
                        }, {
                            label: 'Totaal',
                            data: totaal,
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
            }

            function createDifferenceChart(canvasId, labels, difference) {
                var ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Verschil (2024 - 2023)',
                            data: difference,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgb(75, 192, 192)',
                            borderWidth: 1
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
            }

            createChart('myChart2023', <?php echo json_encode($dates2023); ?>, <?php echo json_encode($zaterdag2023); ?>, <?php echo json_encode($zondag2023); ?>, <?php echo json_encode($totaal2023); ?>);
            createChart('myChart2024', <?php echo json_encode($dates2024); ?>, <?php echo json_encode($zaterdag2024); ?>, <?php echo json_encode($zondag2024); ?>, <?php echo json_encode($totaal2024); ?>);
            createDifferenceChart('myChartDifference', <?php echo json_encode($diffDates); ?>, <?php echo json_encode($diffTotals); ?>);
            </script>
        </div>
    </div>
</body>
</html>