<?php

//DeleteFile.php

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