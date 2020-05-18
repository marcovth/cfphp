<?php

function ListLen($string,$delimiter){
	// ListGetAt(list, position [, delimiters,  includeEmptyFields])
	if($delimiter==="") $delimiter=",";
	$words=explode($delimiter,$string);
	return sizeof($words);
}


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




?>