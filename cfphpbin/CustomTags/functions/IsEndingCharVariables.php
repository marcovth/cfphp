<?php

//IsEndingCharVariables.php

function IsEndingCharVariables($char){
	//$charSet="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	return preg_match("/[ \\~`!@#$%^&*()\-+=\[\]{}\|\\:;\"\'<,>]/",$char); // removed "._" added "\\"
	//return preg_match($charSet,$char);
}

?>