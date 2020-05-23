<?php

//StructNew.php

function ParseStructureAttributes($StructName,$AttributeLine){
	//echo "ParseStructureAttributes($StructName,$AttributeLine)<br>\n";
	// {firstName="Jane", lastName="Janes", grades=[91, 78, 87]}
	$AttributeLine=trim(RemoveSurroundingQuotes($AttributeLine));
	
	if(Mid($AttributeLine,1,1)==="["){
		// Crude method to allow for <cfset myStruct=[first:"I am number one",second="I am number two"]>
		// However, it might messup square-brackets inside quotes or for arrays.
		// I have tried to deal with this in the char-loop below, 
		// but it gave headaches with arrays like grades=[91, 78, 87, "A"].
		$AttributeLine=Replace($AttributeLine,"[","{","ALL");
		$AttributeLine=Replace($AttributeLine,"]","}","ALL");
		//echo "ReplaceBrackets($StructName,$AttributeLine)<br>\n";
	}
	
	$out=""; $InVariablePound=false; $InVariableDollar=false; 
	$word=""; $InFunction=false;
	$InAttributeValDQuote=false; $InAttributeValSQuote=false; 
	$InArray=false; $InStructure=false; 
	$VariableName=""; $MDVariables=array(); $nMDVariables=0;
	for($i=0; $i<strlen($AttributeLine); $i++){																	//echo "$string[$i]";
		$c=$AttributeLine[$i];	
		//echo "[$c][*$VariableName][$word]<br>\n";
		if($c==="{"){
			if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
				// "{" not part of a string
				if($InStructure){
					// Oh, oh, we are entering a multidimentional structure !
					if(trim($VariableName)!==""){
						$MDVariables[$nMDVariables]=trim($VariableName);
						//echo "&[".$MDVariables[$nMDVariables]."]<br>\n";
						$nMDVariables++;
					}
				}
				$InStructure=true;
			} else $word.=$c;
		} else if($c==="}"){
			if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
				// "}" not part of a string
				$word=trim($word);
				if($word!==""){
					$MDVariablesTXT="";
					if(!ArrayIsEmpty($MDVariables)){
						foreach($MDVariables as &$MD) {
							$MD=trim($MD); if(trim($MD)!=="") $MDVariablesTXT.="['".$MD."']";
						}
					}
					if(IsNumeric($word)) $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = $word;//cfset ?>\n";
					else $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = \"$word\";//cfset ?>\n";
				}
				ArrayDeleteLast($MDVariables); $nMDVariables--;
				$word=""; // print word, start a new word
				$VariableName="";
				$InStructure=false;
			} else $word.=$c;
		} else if($InStructure){
			//echo "[$c]";
			if($c==="=" or $c===":"){
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// "=" not part of a string, therefore assumed a Struct variable Name
					$VariableName=trim($word);
					$word="";
				} else $word.=$c;
			} else if($c==="\""){						
				if(!$InArray){
					if($InAttributeValDQuote){ // In Double
						// Ending double quote ... print text from in between double quotes ...
						$word=trim($word);
						if($word!==""){
							$MDVariablesTXT="";
							if(!ArrayIsEmpty($MDVariables)){
								foreach($MDVariables as &$MD) {
									$MD=trim($MD); if(trim($MD)!=="") $MDVariablesTXT.="['".$MD."']";
								}
							}
							if(IsNumeric($word)) $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = $word;//cfset ?>\n";
							else $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = \"$word\";//cfset ?>\n";
						}
						$word=""; // print word, start a new word
						$VariableName="";
						//$out.=$c;	// print double quote
						$InAttributeValDQuote=false;													//echo "(OutDQ)";
						$InAttributeValSQuote=false;
					} else if($InAttributeValSQuote and !$InArray){ // In Single
						// Double quote as part of in between single quote text ...
						$word.=$c;
					} else { // Not in Double and Not in Single Quotes ... Start of Double quote text ...
						//$out.=$word.$c;  	// print double quote
						$word="";	// start a new word
						$InAttributeValDQuote=true;														//echo "(InDQ)";
						$InAttributeValSQuote=false;
					}
				}
			} else if($c==="'"){ 
				if(!$InArray){
					if($InAttributeValSQuote and !$InArray){ // In Single
						// Ending Single quote ... print text from in between Single quotes ...
						$word=trim($word);
						if($word!==""){
							$MDVariablesTXT="";
							if(!ArrayIsEmpty($MDVariables)){
								foreach($MDVariables as &$MD) {
									$MD=trim($MD); if(trim($MD)!=="") $MDVariablesTXT.="['".$MD."']";
								}
							}
							if(IsNumeric($word)) $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = $word;//cfset ?>\n";
							else $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = \"$word\";//cfset ?>\n";
						}
						$word=""; // print word, start a new word
						$VariableName="";
						$InAttributeValSQuote=false;													//echo "(OutSQ)";
						$InAttributeValDQuote=false;
					} else if($InAttributeValDQuote and !$InArray){ // In Double
						// Double quote as part of in between Single quote text ...
						$word.=$c;
					} else { // Not in Double and Not in Single Quotes ... Start of Single quote text ...
						//$out.=$word.$c;  	// print single quote
						$word="";	// start a new word
						$InAttributeValSQuote=true;														//echo "(InSQ)";
						$InAttributeValDQuote=false;
					}
				}
			} else if($c===","){
				if(!($InAttributeValDQuote or $InAttributeValSQuote or $InArray) ){
					// "," not part of a string.
					$word=trim($word);
					if($word!==""){
						$MDVariablesTXT="";
						if(!ArrayIsEmpty($MDVariables)){
							foreach($MDVariables as &$MD) {
								$MD=trim($MD); if(trim($MD)!=="") $MDVariablesTXT.="['".$MD."']";
							}
						}
						if(IsNumeric($word)) $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = $word;//cfset ?>\n";
						else $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = \"$word\";//cfset ?>\n";
					}
					$word=""; // print word, start a new word
					$VariableName="";
				}else $word.=$c;
			} else if($c==="["){
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// "[" not part of a string. End of VariableName, beginning of array.
					$InArray=true;
					//$VariableName=trim($word); 
					$word="";
				} else $word.=$c;
			} else if($c==="]"){
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// "[" not part of a string. End of VariableName, beginning of array.
					$word=trim($word);
					$vals=ListToArray($word,","); //print_r($words);
					$valTxt="";
					$MDVariablesTXT="";
					if(!ArrayIsEmpty($MDVariables)){
						foreach($MDVariables as &$MD) {
							$MD=trim($MD); if(trim($MD)!=="") $MDVariablesTXT.="['".$MD."']";
						}
					}
					foreach($vals as &$val) {
						$val=trim($val);
						//echo "[$val]";
						if(IsNumeric($val)) $valTxt.="$val,";
						else { $valTxt.="\"$val\",";  } 
					} $valTxt=rtrim(trim($valTxt),',');
					if($word!=="") $out.="<?php \$".$StructName.$MDVariablesTXT."['".$VariableName."'] = array($valTxt);//cfset ?>\n";
					$InArray=false;
					$VariableName=""; $word="";
				} else $word.=$c;

			
			} else $word.=$c;	
		}
		
		
		
	}
	return $out;
}

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