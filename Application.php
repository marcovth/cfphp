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
	if($cp_debugMode==false and file_exists($cp_PHPfile_f)){

		if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
			echo "<form name=\"SaveFiles\" action=\"./$cffileName.$cffileExt\"  method=\"post\">\n";
			echo "	<input type=button value=\"Edit page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\"> \n";
			echo "	<input type=hidden name=SaveWhat id=SaveWhat value=0>\n";
			echo "</form>\n";
		}
		try {
			include "./$cffileName.php"; // <<<<<<< Final execution of PHP file
		} catch (Exception $e) {
			echo 'PHP error: ',  $e->getMessage(), "\n";
		}
		die();
		
	} else if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
		// Can "::1" or 127.0.0.1 be spoofed? ... https://security.stackexchange.com/questions/124184/is-it-possible-to-send-http-packet-via-spoofed-ip
		// It's probably safe like this? Will need more expert opinion.
		
		// ToDo ... backup previous cfml and final-php files to an non-web-accessible folder ...
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==1 ){
			if( isset($_POST["CFMcode"]) and trim($_POST["CFMcode"])!==""){
				//echo "$cp_CFfile";
				$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["CFMcode"])); fclose($exportFile);
			}
			if( isset($_POST["PHPcode"]) and trim($_POST["PHPcode"])!==""){
				//echo "$cp_CFfile";
				$exportFile = fopen($cp_PHPfile_t, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["PHPcode"])); fclose($exportFile);
			}
			$cp_PHPcode=cfphpParser($cp_CFfile);
			$cp_CFcode=$_POST["CFMcode"];
		}
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==2 ){
			if( isset($_POST["CFMcode"]) and trim($_POST["CFMcode"])!==""){
				//echo "$cp_CFfile";
				$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["CFMcode"])); fclose($exportFile);
				$cp_CFcode=$_POST["CFMcode"];
			}
			if( isset($_POST["PHPcode"]) and trim($_POST["PHPcode"])!==""){
				//echo "$cp_CFfile";
				$exportFile = fopen($cp_PHPfile_t, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["PHPcode"])); fclose($exportFile);
				//$exportFile = fopen($cp_PHPfile_f, "w") or die("Unable to write to CFML file!");
				//fwrite($exportFile,trim($_POST["PHPcode"])); fclose($exportFile);
				$cp_PHPcode=$_POST["PHPcode"];
			}
			
			if( !isset($_POST["CFMcode"]) or trim($_POST["CFMcode"])===""){
				$cp_CFcode=ReadFileTXT($cp_CFfile);
			}
			
			if( !isset($_POST["PHPcode"]) or trim($_POST["PHPcode"])===""){
				$cp_PHPcode=cfphpParser($cp_CFfile);
			}
			
		}
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==3 ){ // Final PHP page ...
			if( isset($_POST["CFMcode"]) and trim($_POST["CFMcode"])!==""){
				//echo "$cp_CFfile";
				$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["CFMcode"])); fclose($exportFile);
			}
			if( isset($_POST["PHPcode"]) and trim($_POST["PHPcode"])!==""){
				//echo "$cp_CFfile";
				$exportFile = fopen($cp_PHPfile_t, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["PHPcode"])); fclose($exportFile);
				$exportFile = fopen($cp_PHPfile_f, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["PHPcode"])); fclose($exportFile);
			}
			//$cp_PHPcode=$_POST["PHPcode"];
			//$cp_CFcode=$_POST["CFMcode"];
			
			echo "<form name=\"SaveFiles\" action=\"./$cffileName.$cffileExt\"  method=\"post\">\n";
			echo "	<input type=button value=\"Edit page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\"> \n";
			echo "	<input type=hidden name=SaveWhat id=SaveWhat value=0>\n";
			echo "</form>\n";
			
			include "./$cffileName.php"; // <<<<<<< Final execution of PHP file
			die(); // To avoid the exucution of the CFML template
		}
		
		if( !isset($_POST["SaveWhat"]) or (isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==0) ){
			$cp_PHPcode=cfphpParser($cp_CFfile); // echo $cp_PHPcode;
			$cp_CFcode=ReadFileTXT($cp_CFfile);
		}
		
		
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
		echo "	<tr><td align=center>\n";
		echo "		<input type=button value=\"Translate: CFML => PHP_edit page\" onclick=\"document.getElementById('SaveWhat').value=1;submit();\"> \n";
		echo "		<input type=button value=\"Update PHP_editing page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\">\n";
		echo "		<input type=button value=\"Save final PHP page for deployment\" onclick=\"document.getElementById('SaveWhat').value=3;submit();\"></td></tr>\n";
		echo "		<input type=hidden name=SaveWhat id=SaveWhat value=0><input type=hidden  id=\"CFMcode\" name=\"CFMcode\"><input type=hidden id=\"PHPcode\" name=\"PHPcode\">\n";
		echo "	<tr><td valign=\"top\" id=\"editor1\" width=\"50%\"><textarea rows=20>".$cp_CFcode."</textarea></td></tr>\n";//$cp_CFcode.
		echo "	<tr><td valign=\"top\" id=\"editor2\" width=\"50%\"><textarea rows=20>".$cp_PHPcode."</textarea></td></tr>\n";//$cp_PHPcode.</tr>\n";
		echo "	<tr><td valign=\"top\" id=\"PHPpage\" width=\"100%\"><iframe width=\"100%\" height=\"600\"  src=\"./$cffileName.tmp.php\"></iframe></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		
		echo "<script src=\"./AceEditor/ace.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
		echo "<script>\n";
		
		echo "	var CFMcodeobj=document.getElementById(\"CFMcode\");\n";
		echo "	var PHPcodeobj=document.getElementById(\"PHPcode\");\n";
		
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
		echo "		 CFMcodeobj.value=editor1.getValue();\n";
		echo "	})\n";
		
		echo "	editor2.renderer.on(\"afterRender\", function(e2) {\n";
		echo "		if (editor1.session.getScrollTop() != editor2.session.getScrollTop()) {\n";
		echo "			editor1.session.setScrollTop(editor2.session.getScrollTop());\n";
		echo "		 }\n";
		echo "		if (editor1.session.getScrollLeft() != editor2.session.getScrollLeft()) {\n";
		echo "			editor1.session.setScrollLeft(editor2.session.getScrollLeft());\n";
		echo "		 }\n";
		echo "		 PHPcodeobj.value=editor2.getValue();\n";
		echo "	})\n";
		

		echo "</script>\n";
		
	} else {
		echo "Sorry, you probably ended up here in error. Ask your administrator to process this page.";
	}
	die(); // To avoid displaying the initial CFML code
}

?>