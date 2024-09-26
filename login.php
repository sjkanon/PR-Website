<?php
session_start();
$conn = new mysqli('localhost', 'pr_djm', '_7vd3nC37' , 'techniekdjm_');

if ($conn->connect_error){
    die("Verbindingsfout: " . $conn->connect_error);
}

$gebruikersnaam = $_POST['gebruikersnaam'];
$wachtwoord = $_POST['wachtwoord']

$gebruikersnaam = $conn->real_escape_string($gebruikersnaam);
