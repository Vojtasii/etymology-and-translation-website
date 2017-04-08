<table class='search'>
<?php
$sql = "SELECT `table` FROM dictionaries_list WHERE code='" . $_GET['lang'][0] . "'";
$result = $conn->queryOne($sql);
$sql = "SELECT * FROM $result[table] WHERE " . $data1[$_GET['rng'][0]] . "='$_GET[keywords]'";
$result = $conn->queryOne($sql);

echo "<tr>";
$output = array_keys($result);
for ($j = 0; $j < count($output); $j++) {
    echo "<th>$output[$j]</th>";
}
echo "</tr>";
echo "<tr>";
$output = array_values($result);
for ($j = 0; $j < count($output); $j++) {
    echo "<td>$output[$j]</td>";
}
echo "</tr>";

?>
</table>
