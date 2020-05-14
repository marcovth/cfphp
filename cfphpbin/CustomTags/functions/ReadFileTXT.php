<?php

//ReadFileTXT.php

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