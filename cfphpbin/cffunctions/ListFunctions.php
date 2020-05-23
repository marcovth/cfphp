<?php

function ListToArray($list,$delimiter=","){
	// Copies the elements of a list to an array.
	// listToArray(list [, delimiters] [, includeEmptyFields] [, multiCharacterDelimiter]) → returns array
	// https://cfdocs.org/listtoarray
	//if(null===$delimiter) $delimiter=",";
	$InDoubleQuote=false; $InSingleQuote=false;
	//echo "$list [$delimiter]<br>\n";
	$words=array(); $word=""; $n=0;
	for($i=0; $i<strlen($list); $i++){																	//echo "$string[$i]";
		$c=$list[$i];	
		if($c==="\""){						
			// Do nothing other than copying over the quote, but flag that we are inside a DQ string.
			if($InDoubleQuote){ // In Double Quote
				$InDoubleQuote=false;													//echo "(OutDQ)";
				$InSingleQuote=false;
			} else $InDoubleQuote=true;
			$word.=$c;
		} else if($c==="'"){ 
			// Do nothing other than copying over the quote, but flag that we are inside a SQ string.
			if($InSingleQuote){ // In Single Quote
				$InDoubleQuote=false;													//echo "(OutDQ)";
				$InSingleQuote=false;
			} else $InSingleQuote=true;
			$word.=$c;
		} else if( $c===$delimiter and !$InDoubleQuote and !$InSingleQuote ){
			// Copy over the word to the array ...
			$n++; $words[$n]=$word; $word="";
		} else {
			$word.=$c; //echo "[$c]";
		}
	}
	if(trim($word)!==""){ $n++; $words[$n]=$word; }
	//print_r($words);
	return $words;
}

function ListLen($list,$delimiter=","){
	// Determines the number of elements in a list.
	// listLen(list [, delimiters, [includeEmptyValues]]) → returns numeric
	// https://cfdocs.org/listlen
	if($delimiter==="") $delimiter=",";
	$words=ListToArray($delimiter,$list);//explode($delimiter,$list);
	return sizeof($words);
}

function ListFind($list,$find,$delimiter=",",$PartialFind=0,$InvertedSearch=0){
	// Determines the index of the first list element in which a specified value occurs. 
	// Returns 0 if not found. Case-sensitive
	// listFind(list, value [, delimiters, includeEmptyValues]) → returns numeric
	// https://cfdocs.org/listfind
	
	// Additional options with cfPHP ...
	// list="test,aaa,bbb,ccc" Find="aa" ... PartialFind returns 2, FullFind returns 0;
	// list="test,aaa,bbb,ccc" Find="aaa" ... PartialFind returns 2, FullFind returns 2;
	// InvertedSearch will take each element in the same list, and checks if they can 
	// 	be found in ... Find="aaaa is a long sentence" ... 
	//		PartialFind returns 2, FullFind returns 0;

	//echo "[$PartialFind][$delimiter][".UCASE($PartialFind)."]<br>\n";
	if(null===$PartialFind) $PF=false; else $PF=true;
	if(UCASE($PartialFind)==="FULL" or UCASE($PartialFind)==="FULLFIND") { $PF=false; } 
	else if(UCASE($PartialFind)==="PARTIAL") { $PF=true; } 
	else if($PartialFind==0) { $PF=false; } 
	else if($PartialFind==1) { $PF=true; } 
	
	if(null===$InvertedSearch) $IS=false; else $IS=true; 
	if(UCASE($InvertedSearch)==="INVERTED" or UCASE($InvertedSearch)==="INVERTEDSEARCH") { $IS=true; } 
	else if($InvertedSearch==0) { $IS=false; } 
	else if($InvertedSearch==1) { $IS=true; } 

	//echo "ListFindNoCase([list][$find][$delimiter][$PF][$IS])<br>\n";
	//echo "$list<br>\n";
	
	$words=ListToArray($list,$delimiter); //print_r($words);
	$found=0; $n=0;
	foreach($words as &$word) {	$n++;
		if(!$IS){
			// Normal function call ... Find="AA" in list="test,aaa,bbb,ccc"
			//echo "ListFind=[$find] IN [$word]<br>\n";
			if($PF){
				if(Find($find,$word)>0){ 
					$found=$n; break;
				}
			} else {
				$word=RemoveSurroundingQuotes($word);
				if($find===$word){
					$found=$n; break;
				}
			}
		} else {
			// Inverted search ... Find any of the elements in list="test,aaa,bbb,ccc"
			// in search-string Find="aaaa is a long sentence"
			$Sentence=ListToArray($find," ");
			if($PF){
				foreach($Sentence as &$sword) {
					$sword=RemoveSurroundingQuotes($sword);
					if(Find($word,$sword)>0){ 
						$found=$n; break; break;
					}
				}
			} else {
				$word=RemoveSurroundingQuotes($word);
				foreach($Sentence as &$sword) {
					$sword=RemoveSurroundingQuotes($sword);
					if($sword===$word){
						$found=$n; break; break;
					}
				}
			}
		}
	}
	return $found;
}

function ListFindNoCase($list,$find,$delimiter=",",$PartialFind=0,$InvertedSearch=0){
	// Determines the index of the first list element in which a specified value occurs. 
	// Returns 0 if not found. Case-sensitive
	// listFind(list, value [, delimiters, includeEmptyValues]) → returns numeric
	// https://cfdocs.org/listfind
	
	// Additional options with cfPHP ...
	// list="test,aaa,bbb,ccc" Find="AA" ... PartialFind returns 2, FullFind returns 0;
	// list="test,aaa,bbb,ccc" Find="AAA" ... PartialFind returns 2, FullFind returns 2;
	// InvertedSearch will take each element in the same list, and checks if they can 
	// 	be found in ... Find="AAAA is a long sentence" ... 
	//		PartialFind returns 2, FullFind returns 0;

	//echo "[$PartialFind][$delimiter][".UCASE($PartialFind)."]<br>\n";
	if(null===$PartialFind) $PF=false; else $PF=true;
	if(UCASE($PartialFind)==="FULL" or UCASE($PartialFind)==="FULLFIND") { $PF=false; } 
	else if(UCASE($PartialFind)==="PARTIAL") { $PF=true; } 
	else if($PartialFind==0) { $PF=false; } 
	else if($PartialFind==1) { $PF=true; } 
	
	if(null===$InvertedSearch) $IS=false; else $IS=true; 
	if(UCASE($InvertedSearch)==="INVERTED" or UCASE($InvertedSearch)==="INVERTEDSEARCH") { $IS=true; } 
	else if($InvertedSearch==0) { $IS=false; } 
	else if($InvertedSearch==1) { $IS=true; } 

	//echo "ListFindNoCase([list][$find][$delimiter][$PF][$IS])<br>\n";
	//echo "$list<br>\n";
	
	$words=ListToArray($list,$delimiter); //print_r($words);
	$found=0; $n=0;
	foreach($words as &$word) {	$n++;
		$word=UCASE($word);
		$find=UCASE($find);
		if(!$IS){
			// Normal function call ... Find="AA" in list="test,aaa,bbb,ccc"
			//echo "ListFind=[$find] IN [$word]<br>\n";
			if($PF){
				if(Find($find,$word)>0){ 
					$found=$n; break;
				}
			} else {
				$word=RemoveSurroundingQuotes($word);
				if($find===$word){
					$found=$n; break;
				}
			}
		} else {
			// Inverted search ... Find any of the elements in list="test,aaa,bbb,ccc"
			// in search-string Find="AAAA is a long sentence"
			$Sentence=ListToArray($find," ");
			if($PF){
				foreach($Sentence as &$sword) {
					$sword=UCASE(RemoveSurroundingQuotes($sword));
					if(Find($word,$sword)>0){ 
						$found=$n; break; break;
					}
				}
			} else {
				$word=RemoveSurroundingQuotes($word);
				foreach($Sentence as &$sword) {
					$sword=UCASE(RemoveSurroundingQuotes($sword));
					if($sword===$word){
						$found=$n; break; break;
					}
				}
			}
		}
	}
	return $found;
}


function ListGetAt($string,$pos,$delimiter){
	// Gets a list element at a specified position.
	// listGetAt(list, position [, delimiters [, includeEmptyValues]]) → returns string
	if($delimiter==="") $delimiter=",";
	$words=ListToArray($string,$delimiter); //print_r($words);
	if (array_key_exists($pos, $words)) return $words[$pos];
	else return "";
}

function ListFirst($string,$delimiter=","){
	// Gets the first element of a list.
	// listFirst(list [, delimiters]) → returns string
	if($delimiter==="") $delimiter=",";
	$words=ListToArray($string,$delimiter); //print_r($words);
	if(array_key_exists(1, $words)) return trim($words[1],$delimiter);
	else return false;
}

function ListLast($string,$delimiter){
	// Gets the last element of a list.
	// listLast(list [, delimiters, includeEmptyValues ]) → returns string
	//echo "ListLast($string,$delimiter)<br>\n";
	try{
		if($delimiter==="") $delimiter=",";
		$words=ListToArray($string,$delimiter); //print_r($words);
		return $words[ArrayLen($words)];
	} catch ( \Exception $e ) {
		//echo $e->getMessage();
		return "";
	}
}

function ListAppend($list,$delimiter=","){
	
}


?>