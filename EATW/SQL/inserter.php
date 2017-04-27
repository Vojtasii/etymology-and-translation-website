<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("../class.php/DBconn.class.php");
    $dbini = parse_ini_file('../.htcredentials.ini');
       define("dbhost", $dbini["dbhost"]);
       define("dbuser", $_POST['dbuser']);
       define("dbpass", $_POST['dbpass']);
       define("dbname", $dbini["dbname"]);
    $conn = new DBconn(dbhost, dbuser, dbpass, dbname);

    $sql = "TRUNCATE TABLE `dictionaries_list`";
    $conn->query($sql);
    $data = csv_values("DATA/dictionaries_list.csv");
    insert_csv_into_db($data, "dictionaries_list");



    $result = getDictionariesList("`table`, `csv`");
    //print_r($result);
    $table = array_column($result, 'table');
    $csv = array_column($result, 'csv');
    /*print_r($csv);
    print_r($table);*/
    for ($i = 0; $i < count($csv); $i++) {
        if (!empty($csv[$i])) {
            insert_csv_into_db(csv_values("DATA/".$csv[$i]), $table[$i]);
            echo "<br>importing $csv[$i]<br>";
        }


    }
}

function csv_values ($path) {
    $raw = file_get_contents($path);
    $data = str_getcsv($raw, "\n"); //parse the rows
    $columns = $data[0];
    array_shift($data);
    $temp = array();
    foreach($data as $row) { $row = str_getcsv($row, ";");//parse the items in rows
                             $temp[] = $row = "('" . implode($row, "','") . "')"; }
    $data = $temp;
    $data = implode($data, ",");
    $columns = str_replace("\"", "", $columns);
    $columns = "`" . str_replace(";", "`,`", $columns) . "`";
    return array('columns' => $columns, 'values' => $data);
}

function insert_csv_into_db ($data, $table) {
    global $conn;
    /*$sql = "TRUNCATE TABLE `$table`";
    $conn->query($sql);*/
    $sql = "CREATE TABLE IF NOT EXISTS `$table`";
    $conn->query($sql);
    $sql = "LOCK TABLES `$table` WRITE";
    $conn->query($sql); //($data[columns])
    $sql = "INSERT INTO `$table`
            VALUES $data[values]";
    $conn->query($sql);
    $sql = "UNLOCK TABLES";
    $conn->query($sql);

}

function getDictionariesList($args, $conds = null) {
    global $conn;
    $sql = "SELECT $args FROM dictionaries_list";
    if ($conds !== null) $sql .= " WHERE $conds";
    $result = $conn->queryAll($sql);
    return $result;
}

?>
<form method='post' action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input name="dbuser" type="text" id="dbuser" placeholder="DB user">
<input name="dbpass" type="password" id="dbpass" placeholder="DB password">
<input name="add" type="submit" id="add" value="Update database">
</form>
