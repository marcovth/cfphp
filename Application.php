<?php 
//echo "WebRoot Application.php";

if(strtoupper(basename($_SERVER['PHP_SELF']))==="APPLICATION.PHP"){
	echo "Sorry, but you are not allowed to call this page.";
	die(); 
}

if(!isset($cf_subfolderDir) or trim($cf_subfolderDir)===""  ){
	$cf_fileDir=dirname(__FILE__); 
		$cf_fileDir=str_replace("\\","/",$cf_fileDir); 	// Replace backward slashes with forward slashes for Windows.
		if(substr($cf_fileDir,-1)!="/") $cf_fileDir.="/"; // If the last char is not a forward slash
}
																				//echo "$cf_fileDir<br>\n";
$cf_serverRoot=$_SERVER['DOCUMENT_ROOT'];			
	if(substr($cf_serverRoot,-1)!="/") $cf_serverRoot.="/"; 					//echo "$cf_serverRoot<br>\n";
																				
if(!isset($cf_subfolderDir) or trim($cf_subfolderDir)===""  ){
	$cf_subfolderRoot=preg_replace("~".$cf_serverRoot."~i","",$cf_fileDir);
		$cf_subfolderRoot="/".$cf_subfolderRoot;         					 	//echo "$cf_subfolderRoot<br>\n";
		$cf_subfolderDir=$cf_fileDir;         								 	//echo "$cf_subfolderDir<br>\n";
}

$cf_serverName=$_SERVER['SERVER_NAME'];         					 			//echo "$cf_serverName<br>\n";

$cf_httpType=$_SERVER["REQUEST_SCHEME"];
if($cf_subfolderRoot=="/"){ 	// website is located in the cf_serverRoot ...
	$cf_webRootDir=$cf_serverRoot;          								 	//echo "$cf_webRootDir<br>\n";
	$cf_webRootAddress=$cf_httpType."://".$cf_serverName."/";  					//echo "$cf_webRootAddress<br>\n";
} else { 					// website is located in a subdirectory ...
	$cf_subtree=explode("/",$cf_subfolderRoot);
	$cf_webRootDir=$cf_serverRoot."/".$cf_subtree[1]."/";  						//echo "$cf_webRootDir<br>\n";
	$cf_webRootAddress=$cf_httpType."://".$cf_serverName."/".$cf_subtree[1]."/";//echo "$cf_webRootAddress<br>\n";
}

if(!isset($cf_fileName) or trim($cf_fileName)===""  ){
	$cf_fileName=basename($_SERVER['PHP_SELF']); 									//echo "$cf_fileName<br>\n";
		$cf_fileNameParts=explode(".",$cf_fileName);
		$cf_fileNameExt=$cf_fileNameParts[sizeof($cf_fileNameParts)-1];				//echo "$cf_fileNameExt<br>\n";
		$cf_fileNameName=rtrim($cf_fileName,".$cf_fileNameExt");					//echo "$cf_fileNameName<br>\n";
}

require $GLOBALS["cf_webRootDir"]."/cfphpbin/incl-cfphpFunctions.php";
require $GLOBALS["cf_webRootDir"]."/cfphpbin/incl-cfphpDetectVariables.php";
require $GLOBALS["cf_webRootDir"]."/cfphpbin/incl-cfphpParseTags.php";
require $GLOBALS["cf_webRootDir"]."/cfphpbin/cfphpParser.php";

$dir=$cf_webRootDir;
//echo "$dir $cf_fileNameName . " . UCASE($cf_fileNameExt) . " <p>\n\n";

$cp_debugMode=true;
$cp_UserIpAddress=getUserIpAddress(); //::1
$cp_DebuggerRemoteIpAddress="YourRemoteIP";


if(UCASE($cf_fileNameExt)==="CFML" or UCASE($cf_fileNameExt)==="CFM" or UCASE($cf_fileNameExt)==="CFC"){

	$cp_CFfile="$cf_subfolderDir$cf_fileNameName.$cf_fileNameExt";				//echo "$cp_CFfile 1<br>\n";
	$cp_PHPfile_t="$cf_subfolderDir/cftemp/$cf_fileNameName.tmp.php";			//echo "$cp_PHPfile_t 1<br>\n";
	$cp_PHPfile_f="$cf_subfolderDir/cffinal/$cf_fileNameName.php";				//echo "$cp_PHPfile_f 1<br>\n";
	if($cp_debugMode==false and file_exists($cp_PHPfile_f)){
		// Can "::1" or 127.0.0.1 be spoofed? ... https://security.stackexchange.com/questions/124184/is-it-possible-to-send-http-packet-via-spoofed-ip
		// It's probably safe like this? Will need more expert opinion.
		
		if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
			echo "<form name=\"SaveFiles\" action=\"./$cf_fileNameName.$cf_fileNameExt\"  method=\"post\">\n";
			echo "	<div style='float:left;position:absolute;top:0;left:0;'><input type=button style='font-size:8px;' value=\"Edit page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\"> \n";
			echo "	<input type=text style='font-size:8px;' name=NewCFMLpage>\n";
			echo "	<input type=button style='font-size:8px;' value=\"New CFML page\" onclick=\"document.getElementById('SaveWhat').value=10;submit();\"></div> \n";
			echo "	<input type=hidden name=SaveWhat id=SaveWhat value=0>\n";
			echo "</form>\n";
		}
		try {
			include "./cffinal/$cf_fileNameName.php"; // <<<<<<< Execution of Final PHP file
		} catch (Exception $e) {
			echo 'PHP error: ',  $e->getMessage(), "\n";
		}
		die(); // To avoid the exucution of the CFML template
		
	} else if($cp_debugMode and ($cp_UserIpAddress==="::1" or $cp_UserIpAddress==="127.0.0.1" or $cp_UserIpAddress===$cp_DebuggerRemoteIpAddress) ){
		// ToDo ... backup previous cfml and final-php files to an non-web-accessible folder ...
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==10 ){
			// Add a new CFML page to the server ...
			if( isset($_POST["NewCFMLpage"]) and trim($_POST["NewCFMLpage"])!==""){
				
				echo "[".$_POST["NewCFMLpage"]."]";
				$cf_fileNameName=$_POST["NewCFMLpage"];
				$cf_fileNameName=ListLast($cf_fileNameName,"/"); $cf_fileNameName=ListLast($cf_fileNameName,"\\"); // Making sure a new file is not stored in another directory
				$cf_fileNameExt=ListLast($cf_fileNameName,".");
				$cf_fileNameName=Replace($cf_fileNameName,".$cf_fileNameExt","");
				
				if( trim($cf_fileNameName)!=="" and (UCASE(trim($cf_fileNameExt))==="CFM" or UCASE(trim($cf_fileNameExt))==="CFML" or UCASE(trim($cf_fileNameExt))==="CFC" ) ){
				
					$cp_CFfile="$cf_subfolderDir$cf_fileNameName.$cf_fileNameExt";		//echo "$cp_CFfile 2<br>\n";
					$cp_PHPfile_t="$cf_subfolderDir/cftemp/$cf_fileNameName.tmp.php";	//echo "$cp_PHPfile_t 2<br>\n";
					$cp_PHPfile_f="$cf_subfolderDir/cffinal/$cf_fileNameName.php";		//echo "$cp_PHPfile_f 2<br>\n";
				
				
					$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
					fwrite($exportFile," "); fclose($exportFile);
					
					echo "You are editing a new CFML file called: $cf_fileNameName.$cf_fileNameExt";
				}
			} else {
				echo "<a href=$cf_fileName>$cf_fileName</a>"; 
				header("Location: $cf_fileName");
				die();
			}
			$cp_PHPcode=" ";
			$cp_CFcode=" ";
		}
		
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==1 ){
			// Translate CFML to PHP_editing file.
			if( isset($_POST["CFMcode"]) and trim($_POST["CFMcode"])!==""){
																					//echo "$cp_CFfile<br>\n";
				$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["CFMcode"])); fclose($exportFile);
			}
			if( isset($_POST["PHPcode"]) and trim($_POST["PHPcode"])!==""){
																					//echo "$cp_PHPfile_t<br>\n";
				$exportFile = fopen($cp_PHPfile_t, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["PHPcode"])); fclose($exportFile);
			}
			$cp_PHPcode=cfphpParser($cp_CFfile);
			$cp_CFcode=$_POST["CFMcode"];
		}
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==2 ){
			// Update PHP_editing file.
			if( isset($_POST["CFMcode"]) and trim($_POST["CFMcode"])!==""){
																					//echo "$cp_CFfile<br>\n";
				$exportFile = fopen($cp_CFfile, "w") or die("Unable to write to CFML file!");
				fwrite($exportFile,trim($_POST["CFMcode"])); fclose($exportFile);
				$cp_CFcode=$_POST["CFMcode"];
			}
			if( isset($_POST["PHPcode"]) and trim($_POST["PHPcode"])!==""){
																					//echo "$cp_PHPfile_t<br>\n";
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
		
		if( isset($_POST["SaveWhat"]) and $_POST["SaveWhat"]==3 ){ 
			// Save Final PHP page ...
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
			
			echo "<form name=\"SaveFiles\" action=\"./$cf_fileNameName.$cf_fileNameExt\"  method=\"post\">\n";
			echo "	<div style='float:left;position:absolute;top:0;left:0;'><input type=button style='font-size:8px;' value=\"Edit page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\"> \n";
			echo "	<input type=text style='font-size:8px;' name=NewCFMLpage>\n";
			echo "	<input type=button style='font-size:8px;' value=\"New CFML page\" onclick=\"document.getElementById('SaveWhat').value=10;submit();\"></div> \n";
			echo "	<input type=hidden name=SaveWhat id=SaveWhat value=0>\n";
			echo "</form>\n";
			
			include "./cffinal/$cf_fileNameName.php"; // <<<<<<< Execution of Final PHP file
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
		
		echo "<form name=\"SaveFiles\" action=\"./$cf_fileNameName.$cf_fileNameExt\"  method=\"post\">\n";
		echo "<table width=\"100%\">\n";
		echo "	<tr><td align=center>\n";
		echo "		<input type=button id=scrollbutton value=\"Unlink Scroll\" onclick=\"scrollW();\">\n";
		echo "		<a href=./ >DirIndex</a>\n";
		echo "		<input type=button value=\"Translate: CFML => PHP_edit page\" onclick=\"document.getElementById('SaveWhat').value=1;submit();\"> \n";
		echo "		<input type=button value=\"Update PHP_editing page\" onclick=\"document.getElementById('SaveWhat').value=2;submit();\">\n";
		echo "		<input type=button value=\"Save final PHP page for deployment\" onclick=\"document.getElementById('SaveWhat').value=3;submit();\"></td></tr>\n";
		echo "		<input type=hidden name=SaveWhat id=SaveWhat value=0><input type=hidden  id=\"CFMcode\" name=\"CFMcode\"><input type=hidden id=\"PHPcode\" name=\"PHPcode\">\n";
		echo "	<tr><td valign=\"top\" id=\"editor1\" width=\"50%\"><textarea rows=20>".$cp_CFcode."\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n</textarea></td></tr>\n";//$cp_CFcode.
		echo "	<tr><td valign=\"top\" id=\"editor2\" width=\"50%\"><textarea rows=20>".$cp_PHPcode."\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n</textarea></td></tr>\n";//$cp_PHPcode.</tr>\n";
		echo "	<tr><td valign=\"top\" id=\"PHPpage\" width=\"100%\"><iframe width=\"100%\" height=\"600\"  src=\"./cftemp/$cf_fileNameName.tmp.php\"></iframe></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		
		echo "<script src=\"$cf_webRootAddress/AceEditor/ace.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
		
		?>
		<script>
			var scrollobj=document.getElementById("scrollbutton");
			var scrollLinked=1;
			function scrollW(){
				//alert("scroll");
				if(scrollLinked){ 
					scrollLinked=0; 
					scrollobj.value="Link Scroll";
				} else {
					scrollLinked=1;
					scrollobj.value="Unlink Scroll";
				} 
			}
		</script>	
		<?php 
		
		
		
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
		echo "			if(scrollLinked) editor2.session.setScrollTop(editor1.session.getScrollTop());\n";
		echo "		 }\n";
		echo "		if (editor2.session.getScrollLeft() != editor1.session.getScrollLeft()) {\n";
		echo "			if(scrollLinked) editor2.session.setScrollLeft(editor1.session.getScrollLeft());\n";
		echo "		 }\n";
		echo "		 CFMcodeobj.value=editor1.getValue();\n";
		echo "	})\n";
		
		echo "	editor2.renderer.on(\"afterRender\", function(e2) {\n";
		echo "		if (editor1.session.getScrollTop() != editor2.session.getScrollTop()) {\n";
		echo "			if(scrollLinked) editor1.session.setScrollTop(editor2.session.getScrollTop());\n";
		echo "		 }\n";
		echo "		if (editor1.session.getScrollLeft() != editor2.session.getScrollLeft()) {\n";
		echo "			if(scrollLinked) editor1.session.setScrollLeft(editor2.session.getScrollLeft());\n";
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