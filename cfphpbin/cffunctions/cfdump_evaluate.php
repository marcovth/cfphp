<?php

//cfdump_evaluate.php



function cfdump($var){
	echo "<pre>".print_r($var)."</pre>";
}

function evaluate($code) {
	$file=md5("./tmp.txt");
	$tmp = fopen($file,"w");
	//echo "$tmp<br>\n";
	$code2="<?php ";
	//$code2.="echo \"evaluate: \".";
	$code2.="\$x=".$code."; return \$x; ?>";
    fwrite($tmp,$code2);
    $ret=include($file);
    fclose($tmp);
	unlink($file);
	//echo "evaluate:$ret<br>\n";
    return $ret;
}


?>