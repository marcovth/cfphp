<?php

//CFcommend.php

function ParseCFcommend($string,&$toPHPtranslation){
	$string=trim($string);
	$string=trim(RemoveSurroundingQuotes($string));
	$toPHPtranslation.="<?php echo \"<!--- $string --->\"; ?>";

}

function ParseCFhtmlcommend($string,&$toPHPtranslation){
	$string=trim($string);
	$string=trim(RemoveSurroundingQuotes($string));
	$toPHPtranslation.="<?php echo \"<!-- $string -->\"; ?>";

}


?>