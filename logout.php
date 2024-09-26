<?php
session_start();
session_unset(); // Verwijdert alle sessievariabelen
session_destroy(); // Vernietigt de sessie

// Verwijst de gebruiker terug naar de homepage na uitloggen
header("Location: index.php");
exit();
?>
