<?php
// Verbinding maken met de database (gebruik je databasegegevens)
$conn = new mysqli('localhost', 'pr_djm', '_7vd3nC37', 'techniekdjm_');

// Controleer of de verbinding is geslaagd
if ($conn->connect_error) {
    die("Verbindingsfout: " . $conn->connect_error);
}

// Controleer of het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg de ingevoerde gegevens van het formulier
    $gebruikersnaam = $conn->real_escape_string($_POST['gebruikersnaam']);
    $wachtwoord = $_POST['wachtwoord'];

    // Hash het wachtwoord voor veilige opslag
    $hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

    // Voeg de gebruiker toe aan de database
    $stmt = $conn->prepare("INSERT INTO gebruikers (gebruikersnaam, wachtwoord) VALUES (?, ?)");
    $stmt->bind_param("ss", $gebruikersnaam, $hashed_wachtwoord);

    if ($stmt->execute()) {
        echo "Nieuwe gebruiker succesvol geregistreerd.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
