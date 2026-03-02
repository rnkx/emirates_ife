<?php
include 'db.php';
$seat = $_SESSION['seat'];
$conn->query("DELETE FROM favourites WHERE seat_no='$seat'");
