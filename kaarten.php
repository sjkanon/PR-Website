<?php
session_start();
require_once 'db.php';

// Determine current and previous years
$currentYear = date("Y");
$previousYear = $currentYear - 1;

// Fetch data for current and previous years
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
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data['dates'][] = $row["datum"];
            $data['zaterdag'][] = $row["zaterdag"];
            $data['zondag'][] = $row["zondag"];
            $data['totaal'][] = $row["totaal"];
        }
    }
    return $data;
}

$dataPreviousYear = fetchData($previousYear);
$dataCurrentYear = fetchData($currentYear);

$conn->close();

// Calculate differences
$diffDates = [];
$diffZaterdag = [];
$diffZondag = [];
$diffTotaal = [];
$maxDays = max(count($dataPreviousYear['dates']), count($dataCurrentYear['dates']));
for ($i = 0; $i < $maxDays; $i++) {
    $diffDates[] = isset($dataCurrentYear['dates'][$i]) ? $dataCurrentYear['dates'][$i] : $dataPreviousYear['dates'][$i];
    $diffZaterdag[] = (isset($dataCurrentYear['zaterdag'][$i]) ? $dataCurrentYear['zaterdag'][$i] : 0) - (isset($dataPreviousYear['zaterdag'][$i]) ? $dataPreviousYear['zaterdag'][$i] : 0);
    $diffZondag[] = (isset($dataCurrentYear['zondag'][$i]) ? $dataCurrentYear['zondag'][$i] : 0) - (isset($dataPreviousYear['zondag'][$i]) ? $dataPreviousYear['zondag'][$i] : 0);
    $diffTotaal[] = (isset($dataCurrentYear['totaal'][$i]) ? $dataCurrentYear['totaal'][$i] : 0) - (isset($dataPreviousYear['totaal'][$i]) ? $dataPreviousYear['totaal'][$i] : 0);
}

// Calculate latest totals and differences
$lastTotalPrevious = end($dataPreviousYear['totaal']) ?: 0;
$lastTotalCurrent = end($dataCurrentYear['totaal']) ?: 0;
$totalDifference = $lastTotalCurrent - $lastTotalPrevious;

$lastZaterdagPrevious = end($dataPreviousYear['zaterdag']) ?: 0;
$lastZaterdagCurrent = end($dataCurrentYear['zaterdag']) ?: 0;
$zaterdagDifference = $lastZaterdagCurrent - $lastZaterdagPrevious;

$lastZondagPrevious = end($dataPreviousYear['zondag']) ?: 0;
$lastZondagCurrent = end($dataCurrentYear['zondag']) ?: 0;
$zondagDifference = $lastZondagCurrent - $lastZondagPrevious;

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaartentellen <?php echo $previousYear; ?> vs <?php echo $currentYear; ?> Vergelijking</title>
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
            <h1>Kaartentellen <?php echo $previousYear; ?> vs <?php echo $currentYear; ?> Vergelijking</h1>

            <div id="graphs">
                <div id="graphPrevious" class="chart-container">
                    <h2>Kaartentellen <?php echo $previousYear; ?></h2>
                    <canvas id="myChartPrevious"></canvas>
                </div>

                <div id="graphCurrent" class="chart-container">
                    <h2>Kaartentellen <?php echo $currentYear; ?></h2>
                    <canvas id="myChartCurrent"></canvas>
                </div>

                <div id="graphDifference" class="chart-container">
                    <h2>Verschil <?php echo $currentYear; ?> - <?php echo $previousYear; ?></h2>
                    <canvas id="myChartDifference"></canvas>
                </div>
            </div>

            <div id="gauges" class="gauge-container">
                <div class="gauge" id="gaugeTotal"></div>
                <div class="gauge" id="gaugeZaterdag"></div>
                <div class="gauge" id="gaugeZondag"></div>
            </div>

            <div id="comparison">
                <h2>Vergelijking <?php echo $previousYear; ?> vs <?php echo $currentYear; ?></h2>
                <p>Totaal aantal kaarten <?php echo $previousYear; ?>: <?php echo $lastTotalPrevious; ?></p>
                <p>Totaal aantal kaarten <?php echo $currentYear; ?>: <?php echo $lastTotalCurrent; ?></p>
                <p>Verschil in totaal aantal kaarten: <?php echo $totalDifference; ?></p>
                <p>Zaterdag <?php echo $previousYear; ?>: <?php echo $lastZaterdagPrevious; ?>, Zaterdag <?php echo $currentYear; ?>: <?php echo $lastZaterdagCurrent; ?>, Verschil: <?php echo $zaterdagDifference; ?></p>
                <p>Zondag <?php echo $previousYear; ?>: <?php echo $lastZondagPrevious; ?>, Zondag <?php echo $currentYear; ?>: <?php echo $lastZondagCurrent; ?>, Verschil: <?php echo $zondagDifference; ?></p>
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

            createChart('myChartPrevious', <?php echo json_encode($dataPreviousYear['dates']); ?>, <?php echo json_encode($dataPreviousYear['zaterdag']); ?>, <?php echo json_encode($dataPreviousYear['zondag']); ?>, <?php echo json_encode($dataPreviousYear['totaal']); ?>);
            createChart('myChartCurrent', <?php echo json_encode($dataCurrentYear['dates']); ?>, <?php echo json_encode($dataCurrentYear['zaterdag']); ?>, <?php echo json_encode($dataCurrentYear['zondag']); ?>, <?php echo json_encode($dataCurrentYear['totaal']); ?>);
            createDifferenceChart('myChartDifference', <?php echo json_encode($diffDates); ?>, <?php echo json_encode($diffZaterdag); ?>, <?php echo json_encode($diffZondag); ?>, <?php echo json_encode($diffTotaal); ?>);

            createGauge('gaugeTotal', <?php echo $lastTotalCurrent; ?>, 1476, 'Totaal: <?php echo $lastTotalCurrent; ?> (<?php echo $totalDifference >= 0 ? "+$totalDifference" : $totalDifference; ?>)');
            createGauge('gaugeZaterdag', <?php echo $lastZaterdagCurrent; ?>, 738, 'Zaterdag: <?php echo $lastZaterdagCurrent; ?> (<?php echo $zaterdagDifference >= 0 ? "+$zaterdagDifference" : $zaterdagDifference; ?>)');
            createGauge('gaugeZondag', <?php echo $lastZondagCurrent; ?>, 738, 'Zondag: <?php echo $lastZondagCurrent; ?> (<?php echo $zondagDifference >= 0 ? "+$zondagDifference" : $zondagDifference; ?>)');
            </script>
        </div>
    </div>
</body>
</html>