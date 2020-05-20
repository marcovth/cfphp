<?php

function ParseCFtry($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFTRY $AttributeLine]";

}

function ParseCFcatch($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFCATCH $AttributeLine]";

}


?>