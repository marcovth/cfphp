<?php


function ArrayLen(&$array){
	// Which one is better? ...
	//if(count($array)>0) return count(reset($array));
	if(!empty($array)) return sizeof($array)-1;
	else return 0;
}

function ArrayIsEmpty(&$array){
	return empty($array);
}

function ArrayDeleteLast(&$array){
	// New, cfPHP specific
	// Deletes the last element from an array
	// The array will be resized.
	// ArrayDeleteLast(array) → returns boolean
	if(!empty($array) and sizeof($array)-1 >=0){
		//echo "Delete=[".$array[sizeof($array)-1]."]<br>\n";
		unset($array[sizeof($array)-1]); // PHP 0-based
		return true;
	} else return false;
}

function ArrayDeleteFirst(&$array){
	// New, cfPHP specific
	// Deletes the first element from an array
	// The array will be resized.
	// ArrayDeleteFirst(array) → returns boolean
	if(!empty($array)){
		unset($array[0]);  // PHP 0-based
		return true;
	} else return false;
}

function ArrayDeleteAt(&$array,$index){
	// Deletes the element at index from an array
	// The array will be resized, so that the deleted element doesn't leave a gap.
	// ArrayDeleteAt(array, index) → returns boolean
	if(!empty($array) and IsNumeric($index) and $index<sizeof($array)){
		unset($array[$index-1]);  // Index = CFML 1-based, and not PHP 0-based
		return true;
	} else return false;
}



?>