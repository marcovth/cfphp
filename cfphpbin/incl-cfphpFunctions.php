<?php

function FileExists($filePath){
	return file_exists($filePath);
}


function Find($find,$string,$start=NULL){
	if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	if($start>strlen($string)-1) return false;
	if(strlen($string)==0) return false; 
	if(strlen($find)==0) return false; 
	$found=mb_strpos($string,$find,$start);
	if($found===false) return false;
	else return $found+1; // Returns CFML 1-based offset or false
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

function IsEndingCharVariables($char){
	//$charSet="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	return preg_match("/[ \\~`!@#$%^&*()\-+=\[\]{}\|\\:;\"\'<,>]/",$char); // removed "._" added "\\"
	//return preg_match($charSet,$char);
}

function IsNumeric($string){
	if(is_numeric($string)) return true; else false;
}

function IsAlphaNumeric($string){
	
	//if(is_numeric($string)) return true; else false;
}
//if(strlen($string)===1 and !IsNumeric($string) and $string!==" " and $string!=="	") return "$".$string;
//	if (!IsNumeric(Mid($string,1,1)) and preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/',$string)) {



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

function Mid($string,$offset,$len){
	$from=$offset-1; if($from<0)return ""; 	// To prevent a negative number - Start at a specified position from the end of the string
	if($len<0)return "";					// To prevent a negative number - The length to be returned from the end of the string
	return substr($string,$from,$len);
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

function DeleteFile($filePath){
	$tmp = dirname(__FILE__);
	if (strpos($tmp, '/', 0)!==false) {
		define('WINDOWS_SERVER', false);
	} else {
		define('WINDOWS_SERVER', true);
	}
	$deleteError = 0;
	if (!WINDOWS_SERVER) {
		if (!unlink($filePath)) $deleteError = 1;
	} else {
		$lines = array();
		exec("DEL /F/Q \"$filePath\"", $lines, $deleteError);
	}
	if ($deleteError) {
		echo 'file delete error '.WINDOWS_SERVER;
	}
}


?>