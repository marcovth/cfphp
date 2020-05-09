<?php


function DetectVariables($string,$Addtags){//,$type=null){ 									//echo "$Addtags";
	//$VariableEndingChars="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	$out=""; $InVariablePound=false; $InVariableDollar=false; $Variable=""; $word=""; $InFunction=false;
	$InAttributeValDQuote=false; $InAttributeValSQuote=false; $InHTMLcommendOut=false;
	if($Addtags==="yes") $Add=true; else $Add=false;									//echo "$Add";
	// if(null===$type) $type="rightside"; else $type="leftside";	
	//$string=ltrim($string); // trim and ltrim are cutting off strings prematurely !!!
	// if(trim($string)==="") return $string;
	// if($type==="leftside"){																echo "leftside";
		// $Keywords="true,false";
		// if(FindNoCase(trim($string),$Keywords)===false) return $string="$".$string;	
		// else return $string;
	// }
																											//echo "<br>\n";
	for($i=0; $i<strlen($string); $i++){																	//echo "$string[$i]";
		$c=$string[$i];	
		$cf_codon="@@@"; if(($i+2)<strlen($string)) $cf_codon=$string[$i].$string[$i+1].$string[$i+2];		//echo "[$cf_codon]";
		if($cf_codon==="<!-"){
			$InHTMLcommendOut=true;
			$word.="<";
		} else if($cf_codon==="-->"){ 
			$word.="-->"; 
			$i=$i+2;
			$InHTMLcommendOut=false; 
		} else if($InHTMLcommendOut){ 
			$word.=$c;
			$InVariablePound=false; $InVariableDollar=false; $InFunction=false;
			$InAttributeValDQuote=false; $InAttributeValSQuote=false; 
		} else {
			if($c==="\""){						
				if($InAttributeValDQuote){ // In Double
					// Ending double quote ... print text from in between double quotes ...
					$out.=$word; $word=""; // print word, start a new word
					$out.=$c;	// print double quote
					$InAttributeValDQuote=false;													//echo "(OutDQ)";
					$InAttributeValSQuote=false;
				} else if($InAttributeValSQuote){ // In Single
					// Double quote as part of in between single quote text ...
					$word.=$c;
				} else { // Not in Double and Not in Single Quotes ... Start of Double quote text ...
					$out.=$word.$c;  	// print double quote
					$word="";	// start a new word
					$InAttributeValDQuote=true;														//echo "(InDQ)";
					$InAttributeValSQuote=false;
				}  
			} else if($c==="'"){ 
				if($InAttributeValSQuote){ // In Single
					// Ending Single quote ... print text from in between Single quotes ...
					$out.=$word; $word=""; // print word, start a new word
					$out.=$c;	// print Single quote
					$InAttributeValSQuote=false;													//echo "(OutSQ)";
					$InAttributeValDQuote=false;
				} else if($InAttributeValDQuote){ // In Double
					// Double quote as part of in between Single quote text ...
					$word.=$c;
				} else { // Not in Double and Not in Single Quotes ... Start of Single quote text ...
					$out.=$word.$c;  	// print single quote
					$word="";	// start a new word
					$InAttributeValSQuote=true;														//echo "(InSQ)";
					$InAttributeValDQuote=false;
				} 
			} else if($c==="#"){
				//if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					if(!$InVariablePound){
						$out.=$word; // print previous word
						$word="$";   // start new word and replace first # for $
						$InVariablePound=true;														//echo "(In#)";
					} else {
						// Ending pound-sign ... print variable, remove last #
						if(strlen($word)>1){
							if($Addtags==="yes") 	$out.="<?php echo ".$word."; ?>"; 
							else  					$out.=$word; 
							$word="";	// start a new word
						} else $out.="#"; // Special-case ... In CFML ## is used to print a single # for html/javascript: Example ##leftslider { width:100%; } -> #leftslider { width:100%; }
						$InVariablePound=false; $InVariableDollar=false; $word="";					//echo "(Out#)";
					}
				//} else $out.="#"; // #-sign inside a quoted text string
			} else if($c==="$"){
				//if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					if(!$InVariableDollar){  // PHP-style $variable used in CFML code.
						$out.=$word; 	// print previous word
						$word="$";		// start a new word
						$InVariableDollar=true; $InVariablePound=false;								//echo "(In#)";
					}
				//} else $out.="$"; // $-sign inside a quoted text string
			} else if($c===","){ 
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// It's a comma separating arguments in a function ... print word
					if(IsNumeric($word)){ // No dollar signs in front of numers !
						$out.=$word.$c;
					} else if(!$InFunction){
						$out.=$word.$c;//."[!F]";
					} else if(strlen($word)>1){ // Making sure dollsr signs are not printed with empty words.
						if($Addtags==="yes"){
							if($InVariableDollar) 	$out.="<?php echo ".$word."; ?>$c"; 	// Dollar sign is already present.
							else 					$out.="<?php echo $".$word."; ?>$c";
						} else {					
							if($InVariableDollar) 	$out.=$word.$c; 						// Dollar sign is already present.
							else 					$out.="$".$word.$c;
						}
					} else $out.=",";	// Print the comma
					$word="";	// start a new word
					
					$InVariablePound=false; $InVariableDollar=false;
				} else {
					// It's a comma inside quoted text. Copy comma and move on ...
					$word.=",";	// copy over the comma
				}
			} else if($c===")" or $c==="]" or $c==="+" or $c==="-" or $c==="*"){
				if($c===")") $InFunction=false;
				
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// It's the end of a function ... print word
					if(IsNumeric($word)){ // No dollar signs in front of numers !
						$out.=$word.$c;//."_1";
					} else if(strlen(trim($word))>0 and Mid($word,1,1)!==" " and Mid($word,1,1)!=="	"){ // Making sure dollsr signs are not printed with empty words.
						if($Addtags==="yes"){
							if($InVariableDollar) 	$out.="<?php echo ".$word."; ?>$c"; 	// Dollar sign is already present.
							else 					$out.="<?php echo $".$word."; ?>$c";
						} else {					
							if($InVariableDollar) 	$out.=$word.$c;//."_2"; 						// Dollar sign is already present.
							else 					$out.="$".$word.$c;//."_3";
						}
					} else $out.=$word.$c;//."_4";
					$word="";	// start a new word
					$InVariablePound=false; $InVariableDollar=false;
				} else {
					// It's a bracket inside quoted text. Copy bracket and move on ...
					$word.=$c;//."_5";	// copy over the bracket
				}
			} else if($c==="("){
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// It's the end of a function name ... print word
					$out.=$word;
					$word="";	// start a new word
					$out.="(";	// Print the (
					$InVariablePound=false; $InVariableDollar=false;
					if($c==="(") $InFunction=true;
				} else {
					// It's a bracket inside quoted text. Copy bracket and move on ...
					$word.=",";	// copy over the bracket
				}
			/*} else if( ($c===" " or $i>=strlen($string)-1) ){
				if(ListLen($string," ")==1){ echo "%".ListFirst($string," ")."%";
					// Single word line == $
					$out.="$".$word;
				} else if($InVariablePound){
					$out.="$".$word.$c;
					$InVariablePound=false; $InVariableDollar=false; $word="";
				} else if($InVariableDollar){
					$out.=$word.$c;
					$InVariablePound=false; $InVariableDollar=false; $word="";
				} else {
					$out.=$word.$c; $word="";
				}*/
		
			} else {
				$word.=$c;
			} 
			
			if($i>=strlen($string)-1){
				// Make sure the last word is carried over when the line ends.
				$out.=$word;//."%";
			}
			/*
			if($i==strlen($string)-1){ // space or last char in the line ... //$c===" " or 
				if(!($InAttributeValDQuote or $InAttributeValSQuote) ){
					// It's the end of a function ... print word
					if(IsNumeric($word)){ // No dollar signs in front of numers !
						$out.=$word.$c;
					} else if(strlen($word)>1){ // Making sure dollsr signs are not printed with empty words.
						if($Addtags==="yes" and !trim($word)===""){
							if(!$InVariableDollar) 	$out.="<?php 6 echo ".$word."; ?>$c"; 	// Dollar sign is already present.
							else 					$out.="<?php 7 echo $".$word."; ?>$c";
						} else {					
							if(!$InVariableDollar) 	$out.=$word.$c; 						// Dollar sign is already present.
							else 					$out.="$".$word.$c;
						}
					} else $out.=$word;
					$InVariablePound=false; $InVariableDollar=false;
				} else {
					// It's a space inside quoted text. or Last char in line ... Copy and move on ...
					if($i==strlen($string)-1) $out.=$word.$c;
					else $word.=$c;	// copy over the bracket
				}
			}*/
		}
	}	
	//echo "### $string<br>\n*** $out<br>\n";	//echo "<br>\n";
	return $out;																
}


function IsVariable($string){
	global $cfFunctionNames, $phplanguagekeywords;
	if(FindNoCase($string,$phplanguagekeywords)>0 or FindNoCase($string,$cfFunctionNames)>0) return 1;
	else return 0;
}



?>