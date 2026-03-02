<?php
include 'db.php';
$q = $_GET['q'];
$seat = $_SESSION['seat'];

$result = $conn->query(
"SELECT * FROM songs
 WHERE title LIKE '%$q%'"
);

while ($row = $result->fetch_assoc()) {
echo "
<tr>
<td><b>{$row['title']}</b><br>
<small>{$row['artist']}</small></td>
<td class='text-end'>
<button class='btn btn-success btn-sm'
onclick=\"playSong('{$row['title']}','{$row['audio_file']}')\">
▶
</button>
<button class='btn btn-light btn-sm'
onclick='toggleFavourite({$row['id']})'>❤️</button>
</td>
</tr>";
}
?>


