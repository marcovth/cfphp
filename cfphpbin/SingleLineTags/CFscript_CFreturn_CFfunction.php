<?php

//CFscript_CFreturn_CFfunction.php


function ParseCFscript($AttributeLine,&$toPHPtranslation){
	//$toPHPtranslation.="[CFSCRIPT $AttributeLine]";
	$toPHPtranslation.="<?php //CFSCRIPT ";

}

function ParseCFreturn($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFRETURN $AttributeLine]";

}

function ParseCFfunction($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFFUNCTION $AttributeLine]";

}


?>