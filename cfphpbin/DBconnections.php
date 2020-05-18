<?php

if(strtoupper(basename($_SERVER['PHP_SELF']))==="DBCONNECTIONS.PHP"){
	echo "Sorry, but you are not allowed to call this page.";
	die(); 
}

// MySQL connection ...

$cf_qDBname="northwind";
${$cf_qDBname."_type"}="MySQL";
try {
  ${$cf_qDBname} = new PDO("mysql:host=localhost;dbname=northwind","root","root");
  ${$cf_qDBname}->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}





?>