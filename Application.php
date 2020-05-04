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
		
		echo "	<style type=\"text/css\" media=\"screen\"> \n";
		echo "	.ace_editor { \n";
		echo "		border: 1px solid lightgray; \n";
		echo "		margin: auto; \n";
		echo "		height: 600px; \n";
		echo "		width: 50%; \n";
		echo "	} \n";
		echo "	.scrollmargin { \n";
		echo "		height: 80px; \n";
		echo "		text-align: center; \n";
		echo "	} \n";
		echo "	</style> \n";
		
		echo "<form>\n";
		echo "<table width=\"100%\">\n";
		echo "	<tr><td align=center colspan=2><input type=button value=\"Save CFML code\"> <input type=button value=\"CFM->PHP\"> <input type=button value=\"Save PHP code\"></td></tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" id=\"editor1\" width=\"50%\"><textarea rows=40 name=\"CFMcode\">".$cp_CFcode."</textarea></td>\n";//$cp_CFcode.
		echo "		<td valign=\"top\" id=\"editor2\" width=\"50%\"><textarea rows=40 name=\"PHPcode\">".$cp_PHPcode."</textarea></td>\n";//$cp_PHPcode.
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" id=\"PHPpage\" width=\"100%\" colspan=2><iframe width=\"100%\" height=\"100%\"  src=\"./$cffileName.php\"></iframe></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		
		echo "<script src=\"./AceEditor/ace.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
		echo "<script>\n";
			
		echo "	var editor1 = ace.edit(\"editor1\");\n";
		echo "	editor1.setTheme(\"ace/theme/tomorrow\");\n";
		echo "	editor1.session.setMode(\"ace/mode/php\");\n";
		echo "	editor1.setAutoScrollEditorIntoView(true);\n";
		echo "	//editor1.setOption(\"maxLines\", 100);\n";
		echo "	document.getElementById('editor1').style.fontSize='16px';\n";

			
		echo "	var editor2 = ace.edit(\"editor2\");\n";
		echo "	editor2.setTheme(\"ace/theme/tomorrow\");\n";
		echo "	editor2.session.setMode(\"ace/mode/php\");\n";
		echo "	editor2.setAutoScrollEditorIntoView(true);\n";
		echo "	//editor2.setOption(\"maxLines\", 100);\n";
		echo "	document.getElementById('editor2').style.fontSize='16px';\n";


		echo "	editor1.renderer.on(\"afterRender\", function(e) {\n";
		echo "		if (editor2.session.getScrollTop() != editor1.session.getScrollTop()) {\n";
		echo "			editor2.session.setScrollTop(editor1.session.getScrollTop());\n";
		echo "		 }\n";
		echo "	})\n";
	
		echo "</script>\n";
		
		
	} else {
		// If PHP translated code excists ... redirect automatically to the PHP page ... OR ... show the PHP page inside an iFrame to keep the .cfml extension.
	}
	die(); // To avoid displaying the initial CFML code
}

?>