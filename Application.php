<?php //echo "Application.php";
require './incl-cfphpParseTags.php';
require './incl-cfphpFunctions.php';

$cffileName=basename($_SERVER['PHP_SELF']);
$cffileExt=ListLast($cffileName,".");
$cffileName=Replace($cffileName,".$cffileExt","");
$dir=__DIR__ ."/" ;
echo "$dir $cffileName . " . UCASE($cffileExt) . " <p>\n\n";

?>