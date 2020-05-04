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
		
		echo "<link rel=\"stylesheet\" href=\"".$cp_CodeMirrorPath."codemirror.css\">";
		echo "<script src=\"".$cp_CodeMirrorPath."codemirror.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."matchbrackets.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."htmlmixed.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."xml.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."javascript.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."css.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."clike.js\"></script>";
		echo "<script src=\"".$cp_CodeMirrorPath."php.js\"></script>";
		
		// textarea { width:100%; } style=\"width:100%;\"
		
		echo "<style type='text/css'>";
		echo "	textarea {";
		echo "		width:100%;";
		echo "		white-space: nowrap;";
		echo "		overflow:    scroll;";
		echo "		overflow-y:  scroll;";
		echo "		overflow-x:  scroll;";
		echo "		overflow:    -moz-scrollbars-horizontal;";
		echo "	}";
		echo "</style>";
		
		echo "<form>";
		echo "<table width=\"100%\">";
		echo "	<tr><td align=center colspan=2><input type=button value=\"Save CFML code\"> <input type=button value=\"CFM->PHP\"> <input type=button value=\"Save PHP code\"></td></tr>";
		echo "	<tr>";
		echo "		<td valign=\"top\" id=\"CFMcode\" width=\"50%\"><textarea id=\"code1\" rows=40 name=\"code1\">".$cp_CFcode."</textarea></td>";
		echo "		<td valign=\"top\" id=\"PHPcode\" width=\"50%\"><textarea id=\"code2\" rows=40 name=\"code2\">".$cp_PHPcode."</textarea></td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td valign=\"top\" id=\"PHPpage\" width=\"100%\" colspan=2><iframe width=\"100%\" height=\"100%\"  src=\"./$cffileName.php\"></iframe></td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
		
		
	} else {
		// If PHP translated code excists ... redirect automatically to the PHP page ... OR ... show the PHP page inside an iFrame to keep the .cfml extension.
	}
	die(); // To avoid displaying the initial CFML code
}

?>