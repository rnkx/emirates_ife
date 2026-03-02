<?php
include 'db.php';
$cat = $_GET['cat'];

$sql = $cat == ""
? "SELECT * FROM songs"
: "SELECT * FROM songs WHERE category='$cat'";

$result = $conn->query($sql);

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
