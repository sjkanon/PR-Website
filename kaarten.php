<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaartentellen 2023 Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="myChart"></canvas>

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
</body>
</html>