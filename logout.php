<?php
session_start();
session_destroy(); // Vernietig de sessie
header("Location: index.php"); // Omleiden naar de homepage
exit();
?>
