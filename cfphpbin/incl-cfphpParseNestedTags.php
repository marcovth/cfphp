<?php

function ParseNestedTags($line){
	
	$output="";
	
	$DebugLevel=1; // 1, 2 or 3

	$InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";

	// Line with a CF tag ...
	include "./cfphpbin/incl-cfpfpParseCharLoop.php";
	
	return $output;
	
}	

?>

