<?php 

//CFset_CFargument_CFparam.php

function ParseCFset($AttributeLine,&$toPHPtranslation){
	// echo "CFSET $AttributeLine<br>\n";
	//$toPHPtranslation.="[CFSET $AttributeLine]";
	
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
	} else {
		echo "CFSET else $AttributeLine<br>\n";
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