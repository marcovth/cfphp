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
	try{
		if(!empty($array) and sizeof($array)-1 >=0){
			//echo "Delete=[".$array[sizeof($array)-1]."]<br>\n";
			unset($array[sizeof($array)-1]); // PHP 0-based
			return true;
		} else return false;
	} catch ( \Exception $e ) {
		return false;
	}
}

function ArrayDeleteFirst(&$array){
	// New, cfPHP specific
	// Deletes the first element from an array
	// The array will be resized.
	// ArrayDeleteFirst(array) → returns boolean
	try{
		if(!empty($array)){
			unset($array[0]);  // PHP 0-based
			return true;
		} else return false;
	} catch ( \Exception $e ) {
		return false;
	}
}

function ArrayDeleteAt(&$array,$index){
	// Deletes the element at index from an array
	// The array will be resized, so that the deleted element doesn't leave a gap.
	// ArrayDeleteAt(array, index) → returns boolean
	try{
		if(!empty($array) and IsNumeric($index) and $index<sizeof($array)){
			unset($array[$index-1]);  // Index = CFML 1-based, and not PHP 0-based
			return true;
		} else return false;
	} catch ( \Exception $e ) {
		return false;
	}
}

function ArrayAppend(&$array,$value){
	// Appends an element to the end of an array.
	// ArrayAppend(array, value [, merge]) → returns boolean
	try{
		array_push($array,$value);
		return true;
	} catch ( \Exception $e ) {
		return false;
	}
}

function ArrayPrepend(&$array,$value){
	// Prepends an element to the end of an array.
	// ArrayPrepend(array, value [, merge]) → returns boolean
	try{
		array_unshift($array,$value);
		return true;
	} catch ( \Exception $e ) {
		return false;
	}
}

function ArrayAvg(&$array){
	// Calculates the average of the values in an array.
	// All elements in the array must contain values that can be automatically converted to numeric.
	// ArrayAvg(array) → returns numeric
	try{
		$Sum=0; $nSum=0;
		foreach($array as &$val){
			if(IsNumeric($val)){
				$Sum=$Sum+$val;
				$nSum++;
			}
		}
		if($nSum>0 and $Sum>0) return $Sum/$nSum;
		else return 0;
	} catch ( \Exception $e ) {
		return 0;
	}
}

function ArrayMax(&$array){
	// Returns the largest numeric value in an array. If the array
	// parameter value is an empty array, returns zero.
	// All elements must contain values that can be automatically converted to numeric values.
	// ArrayMax(array) → returns numeric
	try{
		$Largest=0;
		foreach($array as &$val){
			if(IsNumeric($val)){
				if($val>$Largest) $Largest=$val;
			}
		}
		return $Largest;
	} catch ( \Exception $e ) {
		return 0;
	}
}

function ArrayMin(&$array){
	// Returns the smallest numeric value in an array. If the array
	// parameter value is an empty array, returns zero.
	// All elements must contain values that can be automatically converted to numeric values.
	// ArrayMin(array) → returns numeric
	try{
		$smallest=10000000000;
		foreach($array as &$val){
			if(IsNumeric($val)){
				if($val<$smallest) $smallest=$val;
			}
		}
		return $smallest;
	} catch ( \Exception $e ) {
		return 0;
	}
}

function ArraySum(&$array){
	// The sum of values in an array. If the array parameter value is
	// an empty array, returns zero.
	// ArraySum(array) → returns numeric
	try{
		$Sum=0;
		foreach($array as &$val){
			if(IsNumeric($val)){
				$Sum=$Sum+$val;
			}
		}
		return $Sum;
	} catch ( \Exception $e ) {
		return 0;
	}
}




?>