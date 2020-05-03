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
	$cp_PHPfile="$dir$cffileName.php";
	if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
		$cp_PHPcode=cfphpParser($cp_CFfile); // echo $cp_PHPcode;
		$cp_CFcode=ReadFileTXT($cp_CFfile);
		
		echo '<table width="100%">\n';
		echo '	<tr>\n';
		echo '		<td valign="top" id="CFMcode" width="50%">$cp_CFcode</td>\n';
		echo '		<td valign="top" id="PHPcode" width="50%">$cp_PHPcode</td>\n';
		echo '	</tr>\n';
		echo '	<tr>\n';
		echo '		<td valign="top" id="PHPpage" width="100%" colspan=2><iframe width="100%" height="100%"  src="./$cffileName.php"></iframe></td>\n';
		echo '	</tr>\n';
		echo '</table>\n';
		
		
		
	} else {
		// If PHP translated code excists ... redirect automatically to the PHP page ... OR ... show the PHP page inside an iFrame to keep the .cfml extension.
	}
	die(); // To avoid displaying the initial CFML code
}

?>