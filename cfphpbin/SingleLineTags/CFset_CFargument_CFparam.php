<?php 

//CFset_CFargument_CFparam.php

function ParseCFset($AttributeLine,&$toPHPtranslation){
	// echo "CFSET $AttributeLine<br>\n";
	//$toPHPtranslation.="[CFSET $AttributeLine]";
	
	$FunctionFound=ListFindNoCase($GLOBALS['cf_FunctionNames'],$AttributeLine,",","Partial","InvertedSearch");
	//echo "CFSET $AttributeLine [$FunctionFound]<br>\n";
	
	
	$struct=CheckForStructureName($AttributeLine);								//echo "[$struct]";
	if(trim($struct)!==""){														//echo "ParseStructLine";
		$param=ListFirst($AttributeLine,"="); 									//echo "1) $param<br>";
		$AttributeLine=Replace($AttributeLine,"$param=",""); 					//echo "2) $AttributeLine<br>";
		$param=RemoveSurroundingQuotes(trim($param));
		$param=StripVariable($param);
		//echo "[$param]=[$AttributeLine]<br>\n";
		$StructName=ListFirst($param,".");
		$StructKeys=Replace($param,$StructName);
		$StructKeys=ltrim($StructKeys,".");
		$StructKeys="['".Replace($StructKeys,".","']['","ALL")."']";
		//DebugLine("StructName",$StructName);
		//DebugLine("StructKeys",$StructKeys);
		$AttributeLine=trim(RemoveSurroundingQuotes($AttributeLine));
	
		if(Find("{",$AttributeLine)>0 or Mid($AttributeLine,1,1)==="["){
			$toPHPtranslation.=ParseStructureAttributes($StructName,$AttributeLine);
		} else {
			$AttributeLine=DetectVariables($AttributeLine,"NO");
			$out="<?php ";
			if(IsNumeric($AttributeLine)) $out.="\$".$StructName.$StructKeys." = ".$AttributeLine;
			else $out.="\$".$StructName.$StructKeys." = \"".$AttributeLine."\"";
			$toPHPtranslation.=$out.";//cfset ?>";
		}
		
	} else if(FindNoCase("query",$AttributeLine)){
		if(FindNoCase("queryNew(",$AttributeLine)){
			$out="<?php ";															//echo "0) $AttributeLine<br>";
			$param=ListFirst($AttributeLine,"="); 									//echo "1) $param<br>";
			$AttributeLine=Replace($AttributeLine,"$param=",""); 					//echo "2) $AttributeLine<br>";
			$param=RemoveSurroundingQuotes(trim($param));
			$AttributeLine=ReplaceNoCase(trim($AttributeLine),"queryNew(",""); 		//echo "3) $AttributeLine<br>";
			$AttributeLine=rtrim($AttributeLine,")");
			$AttributeLine=ltrim($AttributeLine,"\"");
			$AttributeLine=rtrim($AttributeLine,"\"");								//echo "4) $AttributeLine<br>";
			$out.="queryNew(\"$param\",\"$AttributeLine\")";
			$toPHPtranslation.=$out.";//cfset ?>";
		}if(FindNoCase("queryAddRow(",$AttributeLine)){
			$AttributeLine=ReplaceNoCase(trim($AttributeLine),"queryAddRow(",""); 		//echo "3) $AttributeLine<br>";
			$AttributeLine=rtrim($AttributeLine,")");
			$AttributeLine=trim(RemoveSurroundingQuotes($AttributeLine));
			$out="<?php ";
			$out.="queryAddRow(\"$AttributeLine\")";
			$toPHPtranslation.=$out.";//cfset ?>";
		}if(FindNoCase("querySetCell(",$AttributeLine)){
			$AttributeLine=ReplaceNoCase(trim($AttributeLine),"querySetCell(",""); 		//echo "3) $AttributeLine<br>";
			$AttributeLine=rtrim($AttributeLine,")");
			$AttributeLine=trim(RemoveAllQuotes($AttributeLine));
			$AttributeLine=Replace($AttributeLine,", ",",","ALL");
			$AttributeLine=Replace($AttributeLine," ,",",","ALL");
			$AttributeLine=Replace($AttributeLine,",","\",\"","ALL");
			$out="<?php ";
			$out.="querySetCell(\"$AttributeLine\")";
			$toPHPtranslation.=$out.";//cfset ?>";
		}
	} else if(FindNoCase("StructNew(",$AttributeLine)){
		$param=trim(ListFirst($AttributeLine,"="));
		$param=RemoveSurroundingQuotes(trim($param));
		$param=StripVariable($param);
		//DebugLine("Param",$param);
		//if(Mid($param,1,1)!=="$") $param="\$".$param;
		StructNew($param);
		$out="<?php ";
		$out.="\$".$param."=StructNew(\"$param\")";
		$toPHPtranslation.=$out.";//cfset ?>";
	/* } else if($FunctionFound){
		// Find all the other known (cf)Functions ...
		echo "CFSET Function in $AttributeLine<br>\n";
		$out="<?php "; 
		$param=ListFirst($AttributeLine,"="); 										//echo "1) $param<br>";
		$AttributeLine=Replace($AttributeLine,"$param=",""); 						//echo "2) $AttributeLine<br>";
		if($param===$AttributeLine){
			$AttributeLine="";
			//$out.="param"; 
		} else {
			//$out.="param=Attributes";
		}
		$FunctionFoundInParam=array(); $nFunctionFoundInParam=0;
		$FunctionFoundInAttributes=array(); $nFunctionFoundInAttributes=0;
		$words=ListToArray($GLOBALS['cf_FunctionNames'],","); //print_r($words);
		foreach($words as &$word) {
			if(trim($word)!==""){
				$found=FindNoCase($word."(",$param);
				if($found>0){
					$nFunctionFoundInParam++;
					$FunctionFoundInParam[$found]=$word;
				}
				$found=FindNoCase($word."(",$AttributeLine);
				if($found>0){
					$nFunctionFoundInAttributes++;
					$FunctionFoundInAttributes[$found]=$word;
				}
			}
		}
		if(ArrayLen($FunctionFoundInParam)>1){
			ArraySort($FunctionFoundInParam,"STRUCTSORTKEY","DESC");
			foreach($FunctionFoundInParam as $key => $value) {
				echo "Key=" . $key . ", Value=" . $value."<br>\n";
				$fparams=Mid($param,$key+Len($value)+1,1000);
				$fparams=ListFirst($fparams,")");
				echo "<li>$fparams<br>\n";
			}
		}
		if(ArrayLen($FunctionFoundInAttributes)>1) ArraySort($FunctionFoundInAttributes,"STRUCTSORTKEY","DESC");
		echo "InParam-Functions=".ArrayLen($FunctionFoundInParam)."<br>\n";
		echo "InAttributes-Functions=".ArrayLen($FunctionFoundInAttributes)."<br>\n";
		//print_r($FunctionFoundInParam);
		//print_r($FunctionFoundInAttributes);
		
		
		//$param=DetectVariables($param,"NO"); 										//echo "3) $param<br>";
		//$AttributeLine=DetectVariables($AttributeLine,"NO");						//echo "4) $AttributeLine<br>";
																					//echo "[".Mid($param,1,1)."][$param]";
		//if(Mid($param,1,1)!=="$"){
		//	if(Find("\(",$param)>0) $out.=$param." = ".$AttributeLine; // function call
		//	else $out.="$".$param." = ".$AttributeLine; 				// parameter
		//} else $out.=$param." = ".$AttributeLine;
		
		$toPHPtranslation.=$out.";//cfset ?>";
		 */
	} else {
		//echo "CFSET else $AttributeLine<br>\n";
		$out="<?php "; 
		$param=ListFirst($AttributeLine,"="); 										//echo "1) $param<br>";
		$AttributeLine=Replace($AttributeLine,"$param=",""); 						//echo "2) $AttributeLine<br>";
		if($param===$AttributeLine) $AttributeLine="";
		$param=DetectVariables($param,"NO"); 										//echo "3) $param<br>";
		$AttributeLine=DetectVariables($AttributeLine,"NO");						//echo "4) $AttributeLine<br>";
																					//echo "[".Mid($param,1,1)."][$param]";
		if(Mid($param,1,1)!=="$"){
			if(Find("(",$param)>0){// function call
				$out.=$param;
				if(trim($AttributeLine)!=="") $out." = ".$AttributeLine; 
			} else $out.="$".$param." = ".$AttributeLine; 				// parameter
		} else $out.=$param." = ".$AttributeLine;
		
		$toPHPtranslation.=$out.";//cfset ?>";
	}
}

function ParseCFargument($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFARGUMENT $AttributeLine]";

}

function ParseCFparam($AttributeLine,&$toPHPtranslation){
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
	$toPHPtranslation.=$out; //"[CFPARAM $AttributeLine]";

}


?>