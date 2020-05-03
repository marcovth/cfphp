<?php 

//echo "Application.php";
require './incl-cfphpParseTags.php';
require './incl-cfphpFunctions.php';
require "./cfphpParser.php";

$cffileName=basename($_SERVER['PHP_SELF']);
$cffileExt=ListLast($cffileName,".");
$cffileName=Replace($cffileName,".$cffileExt","");
$dir=__DIR__ ."/" ;
$cp_dir=__DIR__ ."/" ;
// echo "$dir $cffileName . " . uCASE($cffileExt) . " <p>\n\n";

$cp_debugMode=true;
$cp_UserIpAddress=getUserIpAddress(); //::1
$cp_DebuggerRemoteIpAddress="YourRemoteIP";


if(UCASE($cffileExt)==="CFML" or UCASE($cffileExt)==="CFM" or UCASE($cffileExt)==="CFC"){
	$cp_CFfile="$dir$cffileName.$cffileExt";
	$cp_PHPfile="$dir$cffileName.$cffileExt";
	// echo "$cp_CFfile";
	//$cp_PHPcode=cfphpParser($cp_CFfile); echo $cp_PHPcode;
	die();
}

?>