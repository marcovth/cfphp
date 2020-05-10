<?php
//echo "subDirectory Application.php";
// Application file for this subdirectory.
// This file prepends php code to each of your php file in this subdirectory.

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
	$cf_webRootAddress=$cf_httpType."://".$cf_serverName."/";  							//echo "$cf_webRootAddress<br>\n";
} else { 					// website is located in a subdirectory ...
	$cf_subtree=explode("/",$cf_subfolderRoot);
	$cf_webRootDir=$cf_serverRoot."/".$cf_subtree[1]."/";  						//echo "$cf_webRootDir<br>\n";
	$cf_webRootAddress=$cf_httpType."://".$cf_serverName."/".$cf_subtree[1]."/";//echo "$cf_webRootAddress<br>\n";
}

$cf_fileName=basename($_SERVER['PHP_SELF']); 									//echo "$cf_fileName<br>\n";
	$cf_fileNameParts=explode(".",$cf_fileName);
	$cf_fileNameExt=$cf_fileNameParts[sizeof($cf_fileNameParts)-1];				//echo "$cf_fileNameExt<br>\n";
	$cf_fileNameName=rtrim($cf_fileName,".$cf_fileNameExt");					//echo "$cf_fileNameName<br>\n";

if(strtoupper($cf_fileNameExt)==="CFML" or strtoupper($cf_fileNameExt)==="CFM" or strtoupper($cf_fileNameExt)==="CFC"){
	//echo "Process CFML page<br>\n";
	if(!is_dir("./cftemp")) mkdir("./cftemp",0777,true);
	if(!is_dir("./cffinal")) mkdir("./cffinal",0777,true);
	copy("./Application.php","./cftemp/Application.php");
	copy("./Application.php","./cffinal/Application.php");
	
	// include the webroot Application.php file ...
																				//echo "$cf_webRootDir/Application.php<br>\n";
	include "$cf_webRootDir/Application.php";
} // else ... most likely a php or html file ... process as usual and move on ...

// The cf_variables from above will be available for your php code.

//phpinfo();
?>