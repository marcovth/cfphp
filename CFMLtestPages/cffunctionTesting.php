<?php

//require './cfphpbin/incl-cfphpFunctions.php';

echo "<li> FindNoCase(\"PHP\",\"php, this is a test of PHP\",5) : ".FindNoCase("PHP","php, this is a test of PHP",5)." [CFML offset = 1]"; 
echo "<li> FindNoCase(\"PHP\",\"php, this is a test of PHP\") : ".FindNoCase("PHP","php, this is a test of PHP")." [CFML offset = 1]"; 
echo "<p>";
echo "<li> Find(\"PHP\",\"php, this is a test of PHP\") : ".Find("PHP","php, this is a test of PHP")." [CFML offset = 1]"; 
echo "<li> Find(\"php\",\"php, this is a test of PHP\") : ".Find("php","php, this is a test of PHP")." [CFML offset = 1]"; 
echo "<li> Find(\"PHP\",\"php, this is a test of PHP\",5) : ".Find("PHP","php, this is a test of PHP",5)." [CFML offset = 1]"; 
echo "<li> Find(\"php\",\"php, this is a test of PHP\",5) : ".Find("php","php, this is a test of PHP",5)." [CFML offset = 1]"; 
echo "<li> if(!Find(\"php\",\"php, this is a test of PHP\",5)) : "; if(!Find("php","php, this is a test of PHP",5)) echo "false";
echo "<p>";
echo "<li> Find(\"=\",\"php, this a = test of PHP\") : ".Find("=","php, this a = test of PHP")." [CFML offset = 1]"; 
echo "<li> Find(\"'\",\"php, this a ' test of PHP\") : ".Find("'","php, this a ' test of PHP")." [CFML offset = 1]"; 
echo "<li> Find(\"\"\",\"php, this a \" test of PHP\") : ".Find("\"","php, this a \" test of PHP")." [CFML offset = 1]"; 
echo "<li> Find(\" \",\"php, this a test of PHP\") : ".Find(" ","php, this a test of PHP")." [CFML offset = 1]"; 
echo "<li> Find(\"(\",\"php, this a ( test of PHP\") : ".Find("(","php, this a ( test of PHP")." [CFML offset = 1]"; 

$line1="<cfset";
$line2="</cfset";

if(FindNoCase("<cf",$line1)) echo "<li>TRUE"; else echo "<li>FALSE";
if(FindNoCase("</cf",$line2)) echo "<li>TRUE"; else echo "<li>FALSE";

echo "<p>";
echo "<p>";

$cf_qryName="FirstTest";
$cf_DBfileName=md5($cf_subfolderDir.$cf_fileName."_".$cf_qryName);
$cf_DBfilePath="$cf_serverRoot../tempdb/$cf_DBfileName.db";
$cf_DB = new SQLite3($cf_DBfilePath);

$cf_DBtest = $cf_DB->querySingle('SELECT SQLITE_VERSION()');
echo $cf_DBtest . "\n";
$cf_DB->exec("DROP TABLE IF EXISTS cars");
$cf_DB->exec("CREATE TABLE cars(id INTEGER PRIMARY KEY, name TEXT, price INT)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Audi', 52642)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Mercedes', 57127)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Skoda', 9000)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Volvo', 29000)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Bentley', 350000)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Citroen', 21000)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Hummer', 41400)");
$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Volkswagen', 21600)");

//DeleteFile($cf_DBfilePath);

//echo $cf_subfolderDir.$cf_fileName."_qname<br>\n";













?>