<?php 

//CFset_CFargument_CFparam.php

function ParseCFset($AttributeLine,&$output){
	//$output.="[CFSET $AttributeLine]";
	
	if(FindNoCase("query",$AttributeLine)){
		if(FindNoCase("queryNew",$AttributeLine)){
			$out="<?php ";															//echo "0) $AttributeLine<br>";
			$param=ListFirst($AttributeLine,"="); 									//echo "1) $param<br>";
			$AttributeLine=Replace($AttributeLine,"$param=",""); 					//echo "2) $AttributeLine<br>";
			$param=RemoveSurroundingQuotes(trim($param));
			$AttributeLine=ReplaceNoCase(trim($AttributeLine),"queryNew(",""); 		//echo "3) $AttributeLine<br>";
			$AttributeLine=rtrim($AttributeLine,")");
			$AttributeLine=ltrim($AttributeLine,"\"");
			$AttributeLine=rtrim($AttributeLine,"\"");								//echo "4) $AttributeLine<br>";
			$out.="queryNew(\"$param\",\"$AttributeLine\")";
			$output.=$out.";//cfset ?>";
		}if(FindNoCase("queryAddRow",$AttributeLine)){
			$AttributeLine=ReplaceNoCase(trim($AttributeLine),"queryAddRow(",""); 		//echo "3) $AttributeLine<br>";
			$AttributeLine=rtrim($AttributeLine,")");
			$AttributeLine=trim(RemoveSurroundingQuotes($AttributeLine));
			$out="<?php ";
			$out.="queryAddRow(\"$AttributeLine\")";
			$output.=$out.";//cfset ?>";
		}if(FindNoCase("querySetCell",$AttributeLine)){
			$AttributeLine=ReplaceNoCase(trim($AttributeLine),"querySetCell(",""); 		//echo "3) $AttributeLine<br>";
			$AttributeLine=rtrim($AttributeLine,")");
			$AttributeLine=trim(RemoveAllQuotes($AttributeLine));
			$AttributeLine=Replace($AttributeLine,", ",",","ALL");
			$AttributeLine=Replace($AttributeLine," ,",",","ALL");
			$AttributeLine=Replace($AttributeLine,",","\",\"","ALL");
			$out="<?php ";
			$out.="querySetCell(\"$AttributeLine\")";
			$output.=$out.";//cfset ?>";
		}
	} else {
		$out="<?php "; 
		$param=ListFirst($AttributeLine,"="); 										//echo "1) $param<br>";
		$AttributeLine=Replace($AttributeLine,"$param=",""); 						//echo "2) $AttributeLine<br>";
		$param=DetectVariables($param,"NO"); 										//echo "3) $param<br>";
		$AttributeLine=DetectVariables($AttributeLine,"NO");						//echo "4) $AttributeLine<br>";
																					//echo "[".Mid($param,1,1)."][$param]";
		if(Mid($param,1,1)!=="$"){
			if(Find("\(",$param)>0) $out.=$param." = ".$AttributeLine; // function call
			else $out.="$".$param." = ".$AttributeLine; 				// parameter
		} else $out.=$param." = ".$AttributeLine;
		
		$output.=$out.";//cfset ?>";
	}
}

function ParseCFargument($AttributeLine,&$output){

	$output.="[CFARGUMENT $AttributeLine]";

}

function ParseCFparam($AttributeLine,&$output){
	// <cfparam name="name" type="numeric" default="0">
	//$name=ListFirst(trim($AttributeLine)," ");
	//$name=ListGetAt(trim($AttributeLine)," ");
																					//echo "{$name}";
	
	$AttributeArr=ParseAttributeLine($AttributeLine);
																					//cfdump($AttributeArr);
																					//echo ArrayLen($AttributeArr);
	$out="[CFPARAM ";
	for ($nAtt=0; $nAtt<=ArrayLen($AttributeArr); $nAtt++){
		if($AttributeArr['AttributeName'][$nAtt] !== ""){
			$out.=" ".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]." ";
		}
	}
	$output.=$out; //"[CFPARAM $AttributeLine]";

}


?>