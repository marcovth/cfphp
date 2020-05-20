<?php

//CFabort_CFdump.php


function ParseCFabort($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="<?php die(); ?>";

}

function ParseCFdump($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="<?php die(); ?>";

}


?>