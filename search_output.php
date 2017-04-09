<table class='search'>
<?php
$values = explodeKeywords($_GET['keywords']);

$sql = "SELECT `table`, title FROM dictionaries_list WHERE code='" . $_GET['lang'][0] . "'";
$result = $conn->queryOne($sql);
$table = $result['table'];
$title = $result['title'];
foreach ($_GET['rng'] as $g) {
    foreach ($values as $v) {
        echo "<tr class='main'><th colspan='4'>\"$v\" ∈ \"$data1[$g]\" ∈ \"$title\"</th></tr><tr>";
        $sql = "SELECT * FROM $table WHERE " . $data1[$g] . "='$v'";
        $result = $conn->queryAll($sql);

        if (!$result) {
            $sql = "SELECT * FROM $table WHERE " . $data1[$g] . " LIKE '%$v%'";
            $result = $conn->queryAll($sql);
        }

        if (!$result) {
            $sql = "SELECT * FROM $table WHERE levenshtein('$v'," . $data1[$g] . ") < " . round(strlen($v) / 2) . " ORDER BY levenshtein('$v'," . $data1[$g] . ") LIMIT 10";
            $result = $conn->queryAll($sql);
            if ($result) { echo "<tr><th colspan=4 class='suggest'>Mysleli jste některý z těchto výrazů?</th></tr>"; }
            else { echo "<tr><th colspan=4 class='error'>Nebyl nalezen žádný odpovídající výraz</th></tr>"; continue; }
        }

        if ($result) {
            $array_keys = array_keys($result[0]);
            echo "<tr class='output'>";
            foreach ($array_keys as $j) {
                echo "<td>$j</td>";
            }
            echo "</tr>";

            foreach ($result as $r) {
                echo "<tr>";
                $array_values = array_values($r);
                for ($j = 0; $j < count($array_values); $j++) {
                    if ($array_keys[$j] === $data1[$g]) $array_values[$j] = str_replace($v,"<b>$v</b>",$array_values[$j]);
                    echo "<td>$array_values[$j]</td>";
                }
                echo "</tr>";
            }
        }

    }
}



function explodeKeywords($keywords) {
    $values = array();
    $value = "";
    $flag = false;
    for ($i = 0; $i < strlen($keywords); $i++) {
        if ($keywords[$i] === " " && !$flag) {
            if (!empty($value)) $values[] = $value;
            $value = "";
            continue;
        }
        else if (preg_match("/[\"'«»„“]/", $keywords[$i])) {
            if (!$flag) { $flag = true; continue; }
            $flag = false;
            if (!empty($value)) $values[] = $value;
            $value = "";
            continue;
        }
        else if (preg_match("/[.,;:]/", $keywords[$i])) continue;
        else $value .= $keywords[$i];

    }
    if (!empty($value)) { $values[] = $value; }
    return $values;
}
?>
</table>
