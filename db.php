<?php
session_start();
/* LANGUAGE HANDLING */
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'en';
$T = include "lang/$lang.php";

/* SEAT */
if (!isset($_SESSION['seat'])) {
    $_SESSION['seat'] = "12A";
}
$conn = new mysqli("localhost", "root", "", "emirates_ice");
if ($conn->connect_error) {
    die("Database connection failed");
} 
if (!isset($_SESSION['seat'])) {
    $_SESSION['seat'] = "12A"; // simulated passenger seat
}
?>
