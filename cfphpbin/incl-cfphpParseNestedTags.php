<?php

function ParseNestedTags($line){
	echo "Nested: $line<br>\n";
	$output="";
	$InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";
	// Line with a CF tag ...
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/incl-cfpfpParseCharLoop.php";
	return $output;
}	

?>

