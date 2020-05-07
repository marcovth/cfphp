<?php

function Find($find,$string,$start=NULL){
	// return preg_match("/".$find."/",$string);  preg_match is giving me errors once in a while
	//cfFind(substring, string [, start ])
	if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	if($start>strlen($string)-strlen($find))$start=strlen($string)-strlen($find);
	$found=0;
	for($i=$start; $i<strlen($string)-strlen($find); $i++){
		$straw=MID($string,$i,strlen($find));
		if($straw===$find) $found=$i+1;  // Convert the position back to CFML offset
		break;
	}
	return $found;
}
function IsEndingCharVariables($char){
	//$charSet="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	return preg_match("/[ \\~`!@#$%^&*()\-+=\[\]{}\|\\:;\"\'<,>]/",$char); // removed "._" added "\\"
	//return preg_match($charSet,$char);
}

function FindNoCase($find,$string,$start=NULL){
	 return preg_match("/".$find."/i",$string);
	//if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	//if($start>strlen($string)-strlen($find))$start=strlen($string)-strlen($find);
	//$found=0;
	//for($i=$start; $i<strlen($string)-strlen($find); $i++){
	//	$straw=MID($string,$i,strlen($find));
	//	if(UCASE($straw)===UCASE($find)) $found=$i+1;  // Convert the position back to CFML offset
	//	break;
	//}
	//return $found;
}

function ArrayLen($array){
	// if(array_key_exists(0, $array)) return sizeof($array);
	// Which one is better? ...
	if(!empty($array)) return sizeof($array);
	//if(count($array)>0) return count($array);
	else return 0;
}

function Mid($string,$offset,$len){
	return substr($string,$offset-1,$len);
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
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl; 
	} else if(NULL===$scope){ // default
		$scope=1; 
		$rpl=implode(UCASE($replaceString), explode($substring,UCASE($string),2)); //str_replace($substring,$replaceString,$string,$scope);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl;
	} else {
		$rpl=str_ireplace($substring,$replaceString,$string,$scope);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl;
	}
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

function StrongPassword($pass){
	$errors = array();
	if (strlen($pass) < 8 || strlen($pass) > 16) {
		$errors[] = "Password should be min 8 characters and max 16 characters";
	}
	if (!preg_match("/\d/", $pass)) {
		$errors[] = "Password should contain at least one digit";
	}
	if (!preg_match("/[A-Z]/", $pass)) {
		$errors[] = "Password should contain at least one Capital Letter";
	}
	if (!preg_match("/[a-z]/", $pass)) {
		$errors[] = "Password should contain at least one small Letter";
	}
	if (!preg_match("/\W/", $pass)) {
		$errors[] = "Password should contain at least one special character";
	}
	if (preg_match("/\s/", $pass)) {
		$errors[] = "Password should not contain any white space";
	}

	if ($errors) {
		foreach ($errors as $error) {
			echo $error . "\n";
		}
		die();
	} else {
		echo "$pass => MATCH\n";
	}
}



?>