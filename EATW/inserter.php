<?php
include("class.php\DBconn.class.php");
LOCK TABLES `dictionaries_list` WRITE;
/*!40000 ALTER TABLE `dictionaries_list` DISABLE KEYS */;
INSERT INTO `dictionaries_list` VALUES (1,'cestina','Čeština','cs','Slovo'),(2,'francais','Français','fr','Mot'),(3,'lietuviu','Lietuvių','lt','Žodis'),(4,'english','English','en','Word'),(5,'slovencina','Slovenčina','sk','Slovo'),(6,'slovenscina','Slovenščina','sl','Beseda');
/*!40000 ALTER TABLE `dictionaries_list` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
?>
