<?php
//error_reporting(-1); // reports all errors
//ini_set("display_errors", "1"); 

//ini_set('xdebug.max_nesting_level', 500);
//require './incl-cfphpParseTags.php';
//require './incl-cfphpFunctions.php';

require "./cfphpbin/incl-ParseCFquery.php";
require "./cfphpbin/incl-cfphpParseNestedTags.php";


function cfphpParser($cp_CFfile){

	$DebugLevel=1; // 1, 2 or 3

	$output=""; $InCFscript=false; $InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";
	$file = fopen($cp_CFfile,"r") or die;
	if ($file) {
		while (($line = fgets($file)) !== false) {
			
			//if(preg_match('/(\/cfscript)/', $line)) $InCFscript=false;
			if(FindNoCase("\/cfscript",$line))  $InCFscript=false;
			if(FindNoCase("\/CFQUERY",$line))  $InsideInnerHTML=false;
			
			
			if($InCFscript){
				// Line inside cfscript block ... copy over, don't even check ...
				$output.="$line";
			} else if($InsideInnerHTML){
				// Line inside InnerHTML block ... copy over, don't even check ...
				$InnerHTML.="$line";
			} else if(FindNoCase("\<cf",$line) or FindNoCase("\<\/cf",$line) ){ // preg_match('/(\<cf)/', $line) or preg_match('/(\<\/cf)/', $line) ) {
				// Line with a CF tag ...
				include "./cfphpbin/incl-cfpfpParseCharLoop.php";
				
			} else {
				// HTML line ...
				$output.="$line";
			}
			
		}
		fclose($file);
	} else {
		// error opening the file.
	} 
	
	return $output;
}	

?>

