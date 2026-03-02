<?php
include 'db.php';

$id = intval($_GET['id']);
$seat = $_SESSION['seat'];

$exists = $conn->query(
    "SELECT 1 FROM favourites WHERE song_id=$id AND seat_no='$seat'"
)->num_rows;

if ($exists) {
    $conn->query("DELETE FROM favourites WHERE song_id=$id AND seat_no='$seat'");
} else {
    $conn->query("INSERT INTO favourites (song_id, seat_no) VALUES ($id, '$seat')");
}

