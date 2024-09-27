<?php
session_start();
require_once 'db.php';

// Fetch data for 2023 and 2024
function fetchData($year) {
    global $conn;
    $sql = "SELECT datum, zaterdag, zondag, totaal FROM kaartentellen_$year ORDER BY datum";
    $result = $conn->query($sql);
    $data = [
        'dates' => [],
        'zaterdag' => [],
        'zondag' => [],
        'totaal' => []
    ];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data['dates'][] = $row["datum"];
            $data['zaterdag'][] = $row["zaterdag"];
            $data['zondag'][] = $row["zondag"];
            $data['totaal'][] = $row["totaal"];
        }
    }
    return $data;
}

$data2023 = fetchData(2023);
$data2024 = fetchData(2024);

$conn->close();

// Calculate differences
$diffDates = [];
$diffZaterdag = [];
$diffZondag = [];
$diffTotaal = [];
$maxDays = max(count($data2023['dates']), count($data2024['dates']));
for ($i = 0; $i < $maxDays; $i++) {
    $diffDates[] = isset($data2024['dates'][$i]) ? $data2024['dates'][$i] : $data2023['dates'][$i];
    $diffZaterdag[] = (isset($data2024['zaterdag'][$i]) ? $data2024['zaterdag'][$i] : 0) - (isset($data2023['zaterdag'][$i]) ? $data2023['zaterdag'][$i] : 0);
    $diffZondag[] = (isset($data2024['zondag'][$i]) ? $data2024['zondag'][$i] : 0) - (isset($data2023['zondag'][$i]) ? $data2023['zondag'][$i] : 0);
    $diffTotaal[] = (isset($data2024['totaal'][$i]) ? $data2024['totaal'][$i] : 0) - (isset($data2023['totaal'][$i]) ? $data2023['totaal'][$i] : 0);
}

// Calculate latest totals and differences
$lastTotal2023 = end($data2023['totaal']);
$lastTotal2024 = end($data2024['totaal']);
$totalDifference = $lastTotal2024 - $lastTotal2023;

$lastZaterdag2023 = end($data2023['zaterdag']);
$lastZaterdag2024 = end($data2024['zaterdag']);
$zaterdagDifference = $lastZaterdag2024 - $lastZaterdag2023;

$lastZondag2023 = end($data2023['zondag']);
$lastZondag2024 = end($data2024['zondag']);
$zondagDifference = $lastZondag2024 - $lastZondag2023;

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaartentellen 2023 vs 2024 Vergelijking</title>
    <link rel="stylesheet" href="css/kaarten.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gauge-chart@latest/dist/bundle.js"></script>
</head>
<body>
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
        <div class="menu">
            <h3>Menu</h3>
            <ul>
                <li><a href="#graphs">Grafieken</a></li>
                <li><a href="#gauges">Meters</a></li>
                <li><a href="#comparison">Vergelijking</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Kaartentellen 2023 vs 2024 Vergelijking</h1>

            <div id="graphs">
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
            </div>

            <div id="gauges" class="gauge-container">
                <div class="gauge" id="gaugeTotal"></div>
                <div class="gauge" id="gaugeZaterdag"></div>
                <div class="gauge" id="gaugeZondag"></div>
            </div>

            <div id="comparison">
                <h2>Vergelijking 2023 vs 2024</h2>
                <p>Totaal aantal kaarten 2023: <?php echo $lastTotal2023; ?></p>
                <p>Totaal aantal kaarten 2024: <?php echo $lastTotal2024; ?></p>
                <p>Verschil in totaal aantal kaarten: <?php echo $totalDifference; ?></p>
                <p>Zaterdag 2023: <?php echo $lastZaterdag2023; ?>, Zaterdag 2024: <?php echo $lastZaterdag2024; ?>, Verschil: <?php echo $zaterdagDifference; ?></p>
                <p>Zondag 2023: <?php echo $lastZondag2023; ?>, Zondag 2024: <?php echo $lastZondag2024; ?>, Verschil: <?php echo $zondagDifference; ?></p>
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

            function createDifferenceChart(canvasId, labels, zaterdag, zondag, totaal) {
                var ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Zaterdag Verschil',
                            data: zaterdag,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1
                        }, {
                            label: 'Zondag Verschil',
                            data: zondag,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1
                        }, {
                            label: 'Totaal Verschil',
                            data: totaal,
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

            function createGauge(elementId, value, maxValue, label) {
                GaugeChart.gaugeChart(document.getElementById(elementId), 300, {
                    hasNeedle: true,
                    needleColor: 'gray',
                    needleUpdateSpeed: 1000,
                    arcColors: ['rgb(44, 151, 222)', 'lightgray'],
                    arcDelimiters: [value / maxValue * 100],
                    rangeLabel: ['0', maxValue.toString()],
                    centralLabel: label,
                });
            }

            createChart('myChart2023', <?php echo json_encode($data2023['dates']); ?>, <?php echo json_encode($data2023['zaterdag']); ?>, <?php echo json_encode($data2023['zondag']); ?>, <?php echo json_encode($data2023['totaal']); ?>);
            createChart('myChart2024', <?php echo json_encode($data2024['dates']); ?>, <?php echo json_encode($data2024['zaterdag']); ?>, <?php echo json_encode($data2024['zondag']); ?>, <?php echo json_encode($data2024['totaal']); ?>);
            createDifferenceChart('myChartDifference', <?php echo json_encode($diffDates); ?>, <?php echo json_encode($diffZaterdag); ?>, <?php echo json_encode($diffZondag); ?>, <?php echo json_encode($diffTotaal); ?>);

            createGauge('gaugeTotal', <?php echo $lastTotal2024; ?>, 1476, 'Totaal: <?php echo $lastTotal2024; ?> (<?php echo $totalDifference >= 0 ? "+$totalDifference" : $totalDifference; ?>)');
            createGauge('gaugeZaterdag', <?php echo $lastZaterdag2024; ?>, 738, 'Zaterdag: <?php echo $lastZaterdag2024; ?> (<?php echo $zaterdagDifference >= 0 ? "+$zaterdagDifference" : $zaterdagDifference; ?>)');
            createGauge('gaugeZondag', <?php echo $lastZondag2024; ?>, 738, 'Zondag: <?php echo $lastZondag2024; ?> (<?php echo $zondagDifference >= 0 ? "+$zondagDifference" : $zondagDifference; ?>)');
            </script>
        </div>
    </div>
</body>
</html>