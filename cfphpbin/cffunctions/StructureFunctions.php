<?php

//StructNew.php

function CheckForStructure($string){
	
}

function StructNew($string){
	$string=Replace($string,',',"_","ALL");
	$GLOBALS["cf_ActiveStructureNames"].=",".$string;
	return "struct";
}



?>