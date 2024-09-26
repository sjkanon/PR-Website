<?php
$host = 'localhost';
$db = 'techniekdjm_';
$user = 'pr_djm';
$pass = '_7vd3nC37';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kan geen verbinding maken met de database: " . $e->getMessage());
}
?>
