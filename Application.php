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
	$cp_PHPfile_t="$dir$cffileName.tmp.php";
	$cp_PHPfile_f="$dir$cffileName.php";
	if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==1 ){
			// echo $_POST["SaveWhat"]; 
			
			if( isset($_POST["CFMcode"]) and trim($_POST["CFMcode"])!==""){
				$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["CFMcode"])); fclose($exportFile);
			}
			
		}
		
		
		$cp_PHPcode=cfphpParser($cp_CFfile); // echo $cp_PHPcode;
		$cp_CFcode=ReadFileTXT($cp_CFfile);
		
		$exportFile = fopen($cp_PHPfile_t, "w") or die("Unable to open PHP temp file!");
		fwrite($exportFile,$cp_PHPcode); fclose($exportFile);
		
		echo "	<style type=\"text/css\" media=\"screen\"> \n";
		echo "	.ace_editor { \n";
		echo "		border: 1px solid lightgray; \n";
		echo "		margin: auto; \n";
		echo "		height: 300px; \n";
		echo "		width: 100%; \n";
		echo "	} \n";
		echo "	.scrollmargin { \n";
		echo "		height: 80px; \n";
		echo "		text-align: center; \n";
		echo "	} \n";
		echo "	</style> \n";
		
		echo "<form name=\"SaveFiles\" action=\"./$cffileName.$cffileExt\"  method=\"post\">\n";
		echo "<table width=\"100%\">\n";
		echo "	<tr><td align=center><input type=button value=\"Translate: (overwrites)CFM => (overwrites)PHP_temp\" onclick=\"document.getElementById('SaveWhat').value=1;submit();\"> \n";
		echo "		<input type=button value=\"Save (overwrite) PHP_temp to PHP_final page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\"></td></tr>\n";
		echo "		<input type=hidden name=SaveWhat id=SaveWhat value=0>\n";
		echo "	<tr><td valign=\"top\" id=\"editor1\" width=\"50%\"><textarea rows=20 id=\"CFMcode\" name=\"CFMcode\">".$cp_CFcode."</textarea></td></tr>\n";//$cp_CFcode.
		echo "	<tr><td valign=\"top\" id=\"editor2\" width=\"50%\"><textarea rows=20 id=\"PHPcode\" name=\"PHPcode\">".$cp_PHPcode."</textarea></td></tr>\n";//$cp_PHPcode.</tr>\n";
		echo "	<tr><td valign=\"top\" id=\"PHPpage\" width=\"100%\"><iframe width=\"100%\" height=\"600\"  src=\"./$cffileName.tmp.php\"></iframe></td></tr>\n";
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
		echo "		if (editor2.session.getScrollLeft() != editor1.session.getScrollLeft()) {\n";
		echo "			editor2.session.setScrollLeft(editor1.session.getScrollLeft());\n";
		echo "		 }\n";
		echo "	})\n";
		
		echo "	editor2.renderer.on(\"afterRender\", function(e2) {\n";
		echo "		if (editor1.session.getScrollTop() != editor2.session.getScrollTop()) {\n";
		echo "			editor1.session.setScrollTop(editor2.session.getScrollTop());\n";
		echo "		 }\n";
		echo "		if (editor1.session.getScrollLeft() != editor2.session.getScrollLeft()) {\n";
		echo "			editor1.session.setScrollLeft(editor2.session.getScrollLeft());\n";
		echo "		 }\n";
		echo "	})\n";
	
		echo "</script>\n";
		
		
	} else {
		// If PHP translated code excists ... redirect automatically to the PHP page ... OR ... show the PHP page inside an iFrame to keep the .cfml extension.
	}
	die(); // To avoid displaying the initial CFML code
}

?>