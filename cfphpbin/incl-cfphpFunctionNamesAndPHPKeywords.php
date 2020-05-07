<?php
$cfFunctionNames="";
$cp_ffile = fopen("./cfphpbin/incl-cfphpFunctions.php","r");
if ($cp_ffile) {
	while(($fline=fgets($cp_ffile))!==false) {
		if( FindNoCase("function ",$fline)>0){
			$fname=ListFirst($fline,"("); $fname=ListLast($fname," "); 	//echo "$fname ";
			$cfFunctionNames=$cfFunctionNames.",".$fname;
		}
	} fclose($cp_ffile);
} else ;// error opening the file.

$phplanguagekeywords="true,false";

?>