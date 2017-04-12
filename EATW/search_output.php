<table class='search'>
<?php
foreach ($_GET['rng'] as $g) {
    foreach ($values as $v) {
        echo "<tr class='main'><th colspan='100%'>\"$v\" ∈ \"$data1[$g]\" ∈ \"$title\"</th></tr><tr>";
        $sql = "SELECT * FROM $table WHERE " . $data1[$g] . "='$v'";
        $result = $conn->queryAll($sql);

        if (!$result) {
            $sql = "SELECT * FROM $table WHERE " . $data1[$g] . " LIKE '$v'";
            $result = $conn->queryAll($sql);
        }

        if (!$result) {
            $lim = round(strlen($v) / 2);
            $sql = "SELECT * FROM $table
                    WHERE (@lvdistance:= levenshteinlim('$v'," . $data1[$g] . ", $lim)) < $lim
                    ORDER BY @lvdistance LIMIT 8";
            try {
                $result = $conn->queryAll($sql);
                if ($result) { echo "<tr><th colspan=100% class='suggest'>Mysleli jste některý z těchto výrazů?</th></tr>"; }
                else { echo "<tr><th colspan=100% class='error'>Nebyl nalezen žádný odpovídající výraz</th></tr>"; continue; }
            }
            catch(\PDOException $e) { echo "<tr class='output'><th colspan=100%>Chyba při hledání v odstavci</th></tr>"; continue;

            }
        }

        $array_keys = array_keys($result[0]);
        echo "<tr class='output'>";
        foreach ($array_keys as $j) {
            echo "<td>$j</td>";
        }
        echo "</tr>";

        $trim_v = trim($v,"\% \t\n\r\0\x0B");
        foreach ($result as $r) {
            echo "<tr>";
            $array_values = array_values($r);
            for ($j = 0; $j < count($array_values); $j++) {
                if ($array_keys[$j] === $data1[$g]) $array_values[$j] = str_replace($trim_v,"<b>$trim_v</b>",$array_values[$j]);
                echo "<td>$array_values[$j]</td>";
            }
            echo "</tr>";
        }
    }
}


?>
</table>
