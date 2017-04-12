<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("../class.php/DBconn.class.php");
    $dbini = parse_ini_file('../../.htcredentials.ini');
       define("dbhost", $dbini["dbhost"]);
       define("dbuser", $_POST['dbuser']);
       define("dbpass", $_POST['dbpass']);
       define("dbname", $dbini["dbname"]);
    $conn = new DBconn(dbhost, dbuser, dbpass, dbname);

    $data = csv_values("DATA/dictionaries_list.csv");
    $sql = "LOCK TABLES `dictionaries_list` WRITE";
    $conn->query($sql); //($data[columns])
    $sql = "INSERT INTO `dictionaries_list`
            VALUES $data[values]";
            //WHERE $data[columns] NOT IN (SELECT $data[columns] FROM `dictionaries_list`)";
    $conn->query($sql);
    $sql = "UNLOCK TABLES";
    $conn->query($sql);
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

?>
<form method='post' action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input name="dbuser" type="text" id="dbuser" placeholder="DB user">
<input name="dbpass" type="password" id="dbpass" placeholder="DB password">
<input name="add" type="submit" id="add" value="Update database">
</form>
