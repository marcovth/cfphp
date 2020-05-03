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
$cp_CodeMirrorPath="./codemirror/"; // path to "codemirror.js" https://github.com/codemirror/codemirror


if(UCASE($cffileExt)==="CFML" or UCASE($cffileExt)==="CFM" or UCASE($cffileExt)==="CFC"){
	$cp_CFfile="$dir$cffileName.$cffileExt";
	$cp_PHPfile="$dir$cffileName.$cffileExt";
	if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
		$cp_PHPcode=cfphpParser($cp_CFfile); // echo $cp_PHPcode;
		$cp_CFcode=ReadFileTXT($cp_CFfile);
		
		
		
		
	}
	die(); // To avoid displaying the CFML code
}

?>