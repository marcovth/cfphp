<?php

function Find($find,$string){
	return preg_match("/".$find."/",$string);
}

function FindNoCase($find,$string){
	//echo "Find";
	return preg_match("/".$find."/i",$string);
}

function ArrayLen($array){
	// if(array_key_exists(0, $array)) return sizeof($array);
	// Which one is better? ...
	if(!empty($array)) return sizeof($array);
	//if(count($array)>0) return count($array);
	else return 0;
}

function Mid($string,$offset,$len){
	return substr($string,$offset,$len);
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
	if(array_key_exists(0, $words)) return $words[0];
	else return "";
}

function ListLast($string,$delimiter){
	// ListGetAt(list, position [, delimiters,  includeEmptyFields])
	//echo "ListLast";
	if($delimiter==="") $delimiter=",";
	$words=explode($delimiter,$string);
	return $words[ArrayLen($words)-1];
}

function Replace($string,$substring,$replaceString,$scope=NULL){
	//Replace(string, substring1, obj [, scope ])
	if($scope=="ALL") return str_replace($substring,$replaceString,$string);
	else if(NULL===$scope){ $scope=1; return str_replace($substring,$replaceString,$string,$scope); } // default
	else return str_replace($substring,$replaceString,$string,$scope);
}

function ReplaceNoCase($string,$substring,$replaceString,$scope=NULL){
	//Replace(string, substring1, obj [, scope ])
	if($scope=="ALL") return str_ireplace($substring,$replaceString,$string);
	else if(NULL===$scope){ $scope=1; return str_ireplace($substring,$replaceString,$string,$scope); } // default
	else return str_ireplace($substring,$replaceString,$string,$scope);
}

function cfdump($var){
	echo "<pre>".print_r($var)."</pre>";
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

function getUserIpAddress(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function ReadFileTXT($FilePath){
	$out="";
	$file = fopen($FilePath,"r");
	if ($file) {
		while (($line = fgets($file)) !== false) {
			$out.=$line;
		}
		return $out;
		fclose($file);
	} else {
		return "";
	} 
}

?>