<?php

//StructNew.php

function CheckForStructureName($string){
	//echo "CheckForStructureName($string)<br>\n";
	if(FindNoCase("StructNew(",$string)>0) return "";
	//echo "***".$GLOBALS["cf_ActiveStructureNames"]."<br>\n";
	$string=trim($string);
	$words=ListToArray($GLOBALS["cf_ActiveStructureNames"]); //print_r($words);
	$found=0; $n=0;
	$var=ListFirst($string,".");
	$var=ltrim($var,"#"); $var=ltrim($var,"$"); $var=ltrim($var,"(");
	foreach($words as &$word){
		$word=trim($word);
		//echo "[$word] in [$var]<br>\n";
		if( (trim($word)!=="") and ListFindNoCase($var,$word,",","partial")>0){
			//echo "[$word] in [$var]<br>\n";
			//echo "Yes<br>\n";
			return $word;
		}
	}
	return "";
}

function StructNew($string){
	//echo "$string<br>\n";
	$string=Replace($string,',',"_","ALL");
	if(!ListFindNoCase($GLOBALS["cf_ActiveStructureNames"],$string)){
		$GLOBALS["cf_ActiveStructureNames"].=",".$string;
		${$string}=array(0=>20);
		return ${$string};
	}
	return false;
}



?>