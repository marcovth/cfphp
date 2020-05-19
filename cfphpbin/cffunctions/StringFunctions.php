<?php

function Mid($string,$offset,$len){
	$from=$offset-1; if($from<0)return ""; 	// To prevent a negative number - Start at a specified position from the end of the string
	if($len<0)return "";					// To prevent a negative number - The length to be returned from the end of the string
	return substr($string,$from,$len);
}


function Len($string){
	return strlen($list);
}

function Find($find,$string,$start=NULL){
	if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	if($start>strlen($string)-1) return false;
	if(strlen($string)==0) return false; 
	if(strlen($find)==0) return false; 
	//$found=mb_strpos($string,$find,$start);
	//if($found===false) return false;
	//else return $found+1; // Returns CFML 1-based offset or false
	$found=0; //echo "Find=[$find] IN [$string]<br>\n";
	for($i=$start; $i<strlen(trim($string)); $i++){	
		$s=Mid($string,$i,strlen($find)); //echo "[$s=$find]";
		if($s===$find){ 
			$found=$i+1;
			//echo "*$s*";
			break;
		}
	}
	//echo "|$found|";
	return $found;
}

function FindNoCase($find,$string,$start=NULL){
	if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	if($start>strlen($string)-1) return false;
	if(strlen($string)==0) return false; 
	if(strlen($find)==0) return false; 
	$found=mb_stripos($string,$find,$start);
	if($found===false) return false;
	else return $found+1; // Returns CFML 1-based offset or false
}

function Replace($string,$substring,$replaceString="",$scope=NULL){
	//Replace(string, substring1, obj [, scope ])
	if($scope=="ALL"){ 
		$rpl=str_replace($substring,$replaceString,$string);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl; 
	} else if(NULL===$scope){ // default
		$scope=1; 
		$rpl=implode($replaceString, explode($substring, $string, 2)); //str_replace($substring,$replaceString,$string,$scope);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl;
	} else {
		$rpl=str_replace($substring,$replaceString,$string,$scope);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl;
	}
}

function ReplaceNoCase($string,$substring,$replaceString,$scope=NULL){
	//Replace(string, substring1, obj [, scope ])
	if($scope=="ALL"){ 
		$rpl=str_ireplace($substring,$replaceString,$string);
		//echo "ReplaceNoCase[$scope | $string | $rpl]<br>\n";
		return $rpl; 
	} else if(NULL===$scope){ // default
		$scope=1; 
		//$rpl=implode(UCASE($replaceString), explode($substring,UCASE($string),2)); 
		$rpl=str_ireplace($substring,$replaceString,$string,$scope);
		//echo "ReplaceNoCase[$scope | $string | $rpl]<br>\n";
		return $rpl;
	} else {
		$rpl=str_ireplace($substring,$replaceString,$string,$scope);
		//echo "ReplaceNoCase[$scope | $string | $rpl]<br>\n";
		return $rpl;
	}
}



function LCASE($string){
  $convert_to = array(
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
    "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
    "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
    "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
    "ь", "э", "ю", "я"
  );
  $convert_from = array(
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
    "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
    "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
    "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
    "Ь", "Э", "Ю", "Я"
  );

  return str_replace($convert_from, $convert_to, $string);
}

function UCASE($string){
  $convert_from = array(
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
    "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
    "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
    "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
    "ь", "э", "ю", "я"
  );
  $convert_to = array(
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
    "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
    "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
    "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
    "Ь", "Э", "Ю", "Я"
  );

  return str_replace($convert_from, $convert_to, $string);
}

function IsNumeric($string){
	if(is_numeric($string)) return true; else false;
}

function IsAlphaNumeric($string){
	
	//if(is_numeric($string)) return true; else false;
}
//if(strlen($string)===1 and !IsNumeric($string) and $string!==" " and $string!=="	") return "$".$string;
//	if (!IsNumeric(Mid($string,1,1)) and preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/',$string)) {




?>