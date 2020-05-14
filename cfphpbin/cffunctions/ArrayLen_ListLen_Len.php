<?php

//ArrayLen_ListLen_Len.php

function ArrayLen($array){
	// Which one is better? ...
	//if(count($array)>0) return count(reset($array));
	if(!empty($array)) return sizeof($array);
	else return 0;
}

function ListLen($string,$delimiter){
	// ListGetAt(list, position [, delimiters,  includeEmptyFields])
	if($delimiter==="") $delimiter=",";
	$words=explode($delimiter,$string);
	return sizeof($words);
}

function Len($string){
	
}
?>