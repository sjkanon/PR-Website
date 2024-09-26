<?php
session_start();
$conn = new mysqli('localhost', 'pr_djm', '_7vd3nC37' , 'techniekdjm_');

if ($conn->connect_error){
    die("Verbindingsfout: " . $conn->connect_error);
}

