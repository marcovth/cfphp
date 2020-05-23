<?php

function ArrayNew($ValuesString1D=""){
	// Creates an array of 1-3 dimensions. 
	// Index array elements with square brackets: [ ]. 
	// CFML arrays expand dynamically as data is added.
	// ArrayNew(dimension [, isSynchronized]) → returns array
	// In PHP you don't have to initialize multi dimentional arrays. $dimension=ignored.
	// Example of $ValuesString1D: <cfset x=ArrayNew("1,4,55,'zz'")>
	
	//$ValuesString1D=trim(RemoveSurroundingQuotes($ValuesString1D));
	//if($ValuesString1D!==""){
		// Adding an empty 0-base element to push the first item in the string to CFML index 1.
		// This might give problems later on.
	//	$ValuesString1D="null,".$ValuesString1D;
	//}
	//$temp=array($ValuesString1D); 
	
	$MDVariables=explode(",",$ValuesString1D); $n=1;
	if(!ArrayIsEmpty($MDVariables)){
		foreach($MDVariables as &$MD) {
			$MD=trim($MD,"\""); $MD=trim($MD,"'"); $MD=trim($MD); 
			//echo "[$MD]";
			if(IsNumeric($MD)) $temp[$n]=$MD;
			else $temp[$n]="'".$MD."'";
			$n++;
		}
	}
	
	return $temp;
}

function ArrayLen(&$array){
	// Which one is better? ...
	//if(count($array)>0) return count(reset($array));
	if(!empty($array)) return sizeof($array);
	else return 0;
}

function ArrayIsEmpty(&$array){
	if(empty($array)) return true; else return false;
}

function ArrayDelete(&$array,$value){
	// Deletes the first element in an array that matches the value of value.
	// The search is case-sensitive.
	// Returns true if the element was found and removed.
	// The array will be resized, so that the deleted element doesn't leave a gap.
	// ArrayDelete(array, value) → returns boolean
	$value=trim(RemoveSurroundingQuotes($value));
		//if (($key = array_search($value, $array)) !== false) {
		//	unset($array[$key]);
		//	return true;
		//} else return false;
	/*
		Alternative ...
		$arr = array('nice_item', 'remove_me', 'another_liked_item', 'remove_me_also');
		$arr = array_diff($arr, array('remove_me', 'remove_me_also'));
		Results in ... array('nice_item', 'another_liked_item')
	*/
	try{
		$n=0; $found=0;
		foreach($array as $key => &$val){
			if(IsNumeric($val)){
				$val=trim(RemoveSurroundingQuotes($val));
				if($val==$value){
					$found=$n; unset($array[$key]); break;
				}
			} else {
				if($val===$value){
					$found=$n; unset($array[$key]); break;
				}
			}
			$n++;
		}
		if($found>0) return true;
		else return false;
	} catch ( \Exception $e ) {
		return false;
	}
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
			//echo "[$val]";
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

function ArrayMedian(&$array){
	//Calculates the Median value of items in an array. 
	// All elements in the array must contain values that can be converted to numeric.
	// ArrayMedian(array) → returns numeric
	try{
		$numbers = array();
		foreach($array as &$value){
			if(IsNumeric($value)){
				ArrayAppend($numbers,$value);
			}
		}
		sort($numbers);
		if(ArrayLen($numbers)%2==0){
			return ($numbers[(ArrayLen($numbers)/2)-1] + $numbers[(ArrayLen($numbers)/2)+0]) / 2;
		} else {
			return $numbers[ArrayLen($numbers)/2];
		}
	} catch ( \Exception $e ) {
		return 0;
	}
}

?>