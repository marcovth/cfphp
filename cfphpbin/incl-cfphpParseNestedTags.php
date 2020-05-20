<?php

function ParseNestedTags($line){
	echo "Nested: $line<br>\n";
	$toPHPtranslation="";
	$InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";
	// Line with a CF tag ...
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/incl-cfpfpParseCharLoop.php";
	return $toPHPtranslation;
}	

?>

