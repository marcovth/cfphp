<?php


function DebugLine($name,$string){
	echo $name."=[".$string."]<br>\n";
}

function StripVariable($Variable){
	$Variable=trim($Variable,"#");
	$Variable=trim($Variable,"$");
	$Variable=trim($Variable,"(");
	$Variable=trim($Variable,")");
	return $Variable;
	//$charSet="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	//return preg_match("/!@#$%^&*()\-+=\[\]{}\|\\:;\"\'<,>]/",$Variable); // removed "._" added "\\"
	//return preg_match($charSet,$char);
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


function IsEndingCharVariables($char){
	//$charSet="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	return preg_match("/[ \\~`!@#$%^&*()\-+=\[\]{}\|\\:;\"\'<,>]/",$char); // removed "._" added "\\"
	//return preg_match($charSet,$char);
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