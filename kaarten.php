<?php
session_start();
require_once 'db.php';

// Determine available years and current selection
$availableYears = range(2023, date("Y"));
$currentYear = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
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
    <title>Kaartentellen Vergelijking</title>
    <link rel="stylesheet" href="css/kaarten.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="about.html">Over Ons</a>
        <a href="contact.html">Contact</a>
        <?php if (isset($_SESSION['gebruikersnaam'])): ?>
            <a href="kaarten.php">Kaarten</a>
            <a href="profile.php">Profiel</a>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>
            <a href="logout.php">Uitloggen</a>
        <?php else: ?>
            <a href="register.php">Registreren</a>
            <a href="login.php">Inloggen</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <div class="menu">
            <h3>Menu</h3>
            <ul>
                <li><a href="#graphs">Grafieken</a></li>
                <li><a href="#gauges">Meters</a></li>
                <li><a href="#comparison">Vergelijking</a></li>
            </ul>
            <h3>Selecteer Jaar</h3>
            <ul class="year-selector">
                <?php foreach ($availableYears as $year): ?>
                    <li>
                        <a href="?year=<?php echo $year; ?>" <?php echo $year == $currentYear ? 'class="active"' : ''; ?>>
                            <?php echo $year; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
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
                <div id="gaugeTotal" class="gauge"></div>
                <div id="gaugeZaterdag" class="gauge"></div>
                <div id="gaugeZondag" class="gauge"></div>
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
            function createChart(canvasId, labels, zaterdag, zondag, totaal, year) {
                var ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Zaterdag ' + year,
                            data: zaterdag,
                            borderColor: 'rgb(255, 99, 132)',
                            tension: 0.1
                        }, {
                            label: 'Zondag ' + year,
                            data: zondag,
                            borderColor: 'rgb(54, 162, 235)',
                            tension: 0.1
                        }, {
                            label: 'Totaal ' + year,
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

            function createDifferenceChart(canvasId, labels, zaterdag, zondag, totaal, prevYear, currYear) {
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
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Verschil ' + currYear + ' - ' + prevYear
                            }
                        }
                    }
                });
            }

            google.charts.load('current', {'packages':['gauge']});
            google.charts.setOnLoadCallback(drawGauges);

            function drawGauges() {
                var dataTotal = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Totaal', <?php echo $lastTotalCurrent; ?>]
                ]);

                var dataZaterdag = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Zaterdag', <?php echo $lastZaterdagCurrent; ?>]
                ]);

                var dataZondag = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Zondag', <?php echo $lastZondagCurrent; ?>]
                ]);

                var options = {
                    width: 300, height: 300,
                    redFrom: 0, redTo: 300,
                    yellowFrom: 300, yellowTo: 600,
                    greenFrom: 600, greenTo: 1476,
                    minorTicks: 5,
                    max: 1476
                };

                var optionsZaterdag = {
                    width: 300, height: 300,
                    redFrom: 0, redTo: 150,
                    yellowFrom: 150, yellowTo: 300,
                    greenFrom: 300, greenTo: 738,
                    minorTicks: 5,
                    max: 738
                };

                var chart = new google.visualization.Gauge(document.getElementById('gaugeTotal'));
                var chartZaterdag = new google.visualization.Gauge(document.getElementById('gaugeZaterdag'));
                var chartZondag = new google.visualization.Gauge(document.getElementById('gaugeZondag'));

                chart.draw(dataTotal, options);
                chartZaterdag.draw(dataZaterdag, optionsZaterdag);
                chartZondag.draw(dataZondag, optionsZaterdag);

                addLabel('gaugeTotal', 'Totaal: <?php echo $lastTotalCurrent; ?> (<?php echo $totalDifference >= 0 ? "+$totalDifference" : $totalDifference; ?>)');
                addLabel('gaugeZaterdag', 'Zaterdag: <?php echo $lastZaterdagCurrent; ?> (<?php echo $zaterdagDifference >= 0 ? "+$zaterdagDifference" : $zaterdagDifference; ?>)');
                addLabel('gaugeZondag', 'Zondag: <?php echo $lastZondagCurrent; ?> (<?php echo $zondagDifference >= 0 ? "+$zondagDifference" : $zondagDifference; ?>)');
            }

            function addLabel(elementId, text) {
                var label = document.createElement('div');
                label.className = 'gauge-label';
                label.textContent = text;
                document.getElementById(elementId).appendChild(label);
            }

            // Create charts
            createChart('myChartPrevious', <?php echo json_encode($dataPreviousYear['dates']); ?>, <?php echo json_encode($dataPreviousYear['zaterdag']); ?>, <?php echo json_encode($dataPreviousYear['zondag']); ?>, <?php echo json_encode($dataPreviousYear['totaal']); ?>, '<?php echo $previousYear; ?>');
            createChart('myChartCurrent', <?php echo json_encode($dataCurrentYear['dates']); ?>, <?php echo json_encode($dataCurrentYear['zaterdag']); ?>, <?php echo json_encode($dataCurrentYear['zondag']); ?>, <?php echo json_encode($dataCurrentYear['totaal']); ?>, '<?php echo $currentYear; ?>');
            createDifferenceChart('myChartDifference', <?php echo json_encode($diffDates); ?>, <?php echo json_encode($diffZaterdag); ?>, <?php echo json_encode($diffZondag); ?>, <?php echo json_encode($diffTotaal); ?>, '<?php echo $previousYear; ?>', '<?php echo $currentYear; ?>');
            </script>
        </div>
    </div>
</body>
</html>