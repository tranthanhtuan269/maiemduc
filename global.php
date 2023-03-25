<?php

$dbhost="localhost";

$dbname="mai";
$dbuser="root";
$dbpass="";

$db=mysql_connect("$dbhost","$dbuser","$dbpass") or die("Die connect: ".mysql_error()); 

mysql_select_db("$dbname") or die("Die select database: ".mysql_error()); 

//config upload

?>