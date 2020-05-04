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
		
		echo "<style type=\"text/css\" media=\"screen\">";
		echo "	.ace_editor ";
		echo "		border: 1px solid lightgray;";
		echo "		margin: auto;";
		echo "		height: 600px;";
		echo "		width: 50%;";
		echo "	}";
		echo "	.scrollmargin {";
		echo "		height: 80px;";
		echo "		text-align: center;";
		echo "	}";
		echo "</style>";
		
		echo "<form>";
		echo "<table width=\"100%\">";
		echo "	<tr><td align=center colspan=2><input type=button value=\"Save CFML code\"> <input type=button value=\"CFM->PHP\"> <input type=button value=\"Save PHP code\"></td></tr>";
		echo "	<tr>";
		echo "		<td valign=\"top\" id=\"CFMcode\" width=\"50%\"><textarea id=\"editor1\" rows=40 name=\"editor1\">".$cp_CFcode."</textarea></td>";
		echo "		<td valign=\"top\" id=\"PHPcode\" width=\"50%\"><textarea id=\"editor2\" rows=40 name=\"editor2\">".$cp_PHPcode."</textarea></td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td valign=\"top\" id=\"PHPpage\" width=\"100%\" colspan=2><iframe width=\"100%\" height=\"100%\"  src=\"./$cffileName.php\"></iframe></td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
		
		echo "<script src=\"AceEditor/ace.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
		echo "<script>";
			
		echo "	var editor1 = ace.edit(\"editor1\");";
		echo "	editor1.setTheme(\"ace/theme/tomorrow\");";
		echo "	editor1.session.setMode(\"ace/mode/html\");";
		echo "	editor1.setAutoScrollEditorIntoView(true);";
		echo "	//editor1.setOption(\"maxLines\", 100);";
		echo "	document.getElementById('editor1').style.fontSize='16px';";

			
		echo "	var editor2 = ace.edit(\"editor2\");";
		echo "	editor2.setTheme(\"ace/theme/tomorrow\");";
		echo "	editor2.session.setMode(\"ace/mode/php\");";
		echo "	editor2.setAutoScrollEditorIntoView(true);";
		echo "	//editor2.setOption(\"maxLines\", 100);";
		echo "	document.getElementById('editor2').style.fontSize='16px';";
	
		echo "</script>";
		
		
	} else {
		// If PHP translated code excists ... redirect automatically to the PHP page ... OR ... show the PHP page inside an iFrame to keep the .cfml extension.
	}
	die(); // To avoid displaying the initial CFML code
}

?>