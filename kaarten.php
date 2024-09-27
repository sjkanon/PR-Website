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
            // JavaScript for charts and gauges (same as before)
            // ... (include all the JavaScript code here)
            </script>
        </div>
    </div>
</body>
</html>