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
// echo "$dir $cffileName . " . UCASE($cffileExt) . " <p>\n\n";

if(UCASE($cffileExt)==="CFML" or UCASE($cffileExt)==="CFM" or UCASE($cffileExt)==="CFC"){
	$cp_CFfile="$dir$cffileName.$cffileExt";
	$cp_PHPfile="$dir$cffileName.$cffileExt";
	// echo "$cp_CFfile";
	$cp_PHPcode=cfphpParser($cp_CFfile);
	echo $cp_PHPcode;
	die();
}

?>