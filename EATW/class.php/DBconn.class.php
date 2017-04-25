<?php
class DBconn{
    private $conn;

    function __construct($dbhost, $dbuser, $dbpass, $dbname) {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            );
        $this->conn = @new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass, $options);
    }

    function query($query, $param = Array()) {
        $result = $this->conn->prepare($query);
        $result->execute($param);
    }

    function queryCount($query, $param = Array()) {
        $result = $this->conn->prepare($query);
        $result->execute($param);
        return $result->rowCount();
    }

    function queryOne($query, $param = Array()){
        $result = $this->conn->prepare($query);
        $result->execute($param);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    function queryAll($query, $param = Array()){
        $result = $this->conn->prepare($query);
        $result->execute($param);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
