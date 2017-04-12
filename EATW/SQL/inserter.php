<?php
include("..\class.php\DBconn.class.php");
$raw = file_get_contents("dictionaries_list.csv");
$data = str_getcsv($raw, "\n"); //parse the rows
foreach($data as &$row) $row = str_getcsv($row, ";"); //parse the items in rows
array_shift($data);
$temp = array();
foreach ($data as $d) {
    $temp[] = '(\'' . implode($d, "','") . '\')';
}
$data = $temp;
$data = implode($data, ",");


$dbini = parse_ini_file('..\..\.htcredentials.ini');
   define("dbhost", $dbini["dbhost"]);
   define("dbuser", $dbini["dbuser"]);
   define("dbpass", "");//$dbini["dbpass"]);
   define("dbname", $dbini["dbname"]);
$conn = new DBconn(dbhost, dbuser, dbpass, dbname);

$sql = "LOCK TABLES `dictionaries_list` WRITE";
$conn->query($sql);
$sql = "INSERT INTO `dictionaries_list` VALUES $data";
echo $sql;
$conn->query($sql);
$sql = "UNLOCK TABLES";
$conn->query($sql);

?>
