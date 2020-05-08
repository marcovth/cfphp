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


?>