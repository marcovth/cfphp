<?php

//ListFirst_ListLast_ListGetAt_Mid.php



function ListGetAt($string,$pos,$delimiter){
	// ListGetAt(list, position [, delimiters,  includeEmptyFields])
	if($delimiter==="") $delimiter=",";
	$words=explode($delimiter,$string);
	if (array_key_exists($pos-1, $words)) return $words[$pos-1];
	else return "";
}

function ListFirst($string,$delimiter){
	// ListGetAt(list, position [, delimiters,  includeEmptyFields])
	if($delimiter==="") $delimiter=",";
	$words=explode($delimiter,$string);
	if(array_key_exists(0, $words)) return trim($words[0],$delimiter);
	else return "x";
}

function ListLast($string,$delimiter){
	// ListGetAt(list, position [, delimiters,  includeEmptyFields])
	//echo "ListLast";
	if($delimiter==="") $delimiter=",";
	$words=explode($delimiter,$string);
	return $words[ArrayLen($words)-1];
}

function Mid($string,$offset,$len){
	$from=$offset-1; if($from<0)return ""; 	// To prevent a negative number - Start at a specified position from the end of the string
	if($len<0)return "";					// To prevent a negative number - The length to be returned from the end of the string
	return substr($string,$from,$len);
}


?>