<?php
//error_reporting(-1); // reports all errors
//ini_set("display_errors", "1"); 

//ini_set('xdebug.max_nesting_level', 500);
//require './incl-cfphpParseTags.php';
//require './incl-cfphpFunctions.php';

require "./cfphpbin/incl-ParseCFquery.php";

function cfphpParser($cp_CFfile){

	$DebugLevel=1; // 1, 2 or 3

	$output=""; $InCFscript=false; $InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";
	$file = fopen($cp_CFfile,"r") or die;
	if ($file) {
		while (($line = fgets($file)) !== false) {
			
			//if(preg_match('/(\/cfscript)/', $line)) $InCFscript=false;
			if(FindNoCase("\/cfscript",$line))  $InCFscript=false;
			if(FindNoCase("\/CFQUERY",$line))  $InsideInnerHTML=false;
			
			
			if($InCFscript){
				// Line inside cfscript block ... copy over, don't even check ...
				$output.="$line";
			} else if($InsideInnerHTML){
				// Line inside InnerHTML block ... copy over, don't even check ...
				$InnerHTML.="$line";
			} else if(FindNoCase("\<cf",$line) or FindNoCase("\<\/cf",$line) ){ // preg_match('/(\<cf)/', $line) or preg_match('/(\<\/cf)/', $line) ) {
				// Line with a CF tag ...
				$InCFtag=false; $InTagName=false; $tagName=""; $InAttributeS=false; $InAttributeName=false; $AttributeName=""; $InAttributeVal=false; $AttributeVal="";
				$InAttributeValDQuote=false; $InAttributeValSQuote=false; $nAtt=0; $AttributeArr=array(); $AttributeLine="";
				$InCFEndtag=false; $EndTagName="";
				//echo $line;
				for ($i=0; $i<strlen($line); $i++){
					
					// ####  Really, really, weird ... "<cf" is detected, but uppercase "<CF" or "<cF" or "<Cf" is NOT !!! #### //
					// ####  This is my first item on the list for someone else to fix ...                                 #### //
					//$c1=""; $c2="";
					//try { $c1=$line[$i+1]; $c2=$line[$i+2];
					//} catch (Exception $e) { }
					//if($line[$i]==="<" and LCASE($line[$i+1])==="c" and LCASE($line[$i+2])==="f"){
					if($line[$i]==="<" and preg_match('/(c)/i', $line[$i+1]) and preg_match('/(f)/i', $line[$i+2])){
						$InCFtag=true; $InTagName=true;
						$i++;
					}
					$SkipChar=false;
					if($line[$i]===">" and $InCFtag and !$InAttributeValDQuote and !$InAttributeValSQuote){ 
						//if($DebugLevel>=2) echo "%$tagName%";
						if($tagName !== ''){
							     if(UCASE($tagName)==="CFSET")     		ParseCFset($AttributeLine,$output);
							else if(UCASE($tagName)==="CFLOOP") 		ParseCFloop($AttributeLine,$output);
							else if(UCASE($tagName)==="CFOUTPUT") 		ParseCFoutput($AttributeLine,$output);
							else if(UCASE($tagName)==="CFPARAM") 		ParseCFparam($AttributeLine,$output);
							else if(UCASE($tagName)==="CFIF") 			ParseCFif($AttributeLine,$output);
							else if(UCASE($tagName)==="CFELSE") 		ParseCFelse($AttributeLine,$output);
							else if(UCASE($tagName)==="CFELSEIF") 		ParseCFelseif($AttributeLine,$output);
							else if(UCASE($tagName)==="CFTRY") 			ParseCFtry($AttributeLine,$output);
							else if(UCASE($tagName)==="CFCATCH") 		ParseCFcatch($AttributeLine,$output);
							else if(UCASE($tagName)==="CFARGUMENT")		ParseCFargument($AttributeLine,$output);
							else if(UCASE($tagName)==="CFRETURN") 		ParseCFreturn($AttributeLine,$output);
							else if(UCASE($tagName)==="CFSCRIPT"){		ParseCFscript($AttributeLine,$output); $InCFscript=true; }
							else if(UCASE($tagName)==="CFQUERY"){
								//ParseCFquery($AttributeLine,$output);
								$InsideInnerHTML=true; $InnerHTML=""; $InnerHTMLTagAttributeLine=$AttributeLine; $SkipChar=true;
							} 
							else if(UCASE($tagName)==="CFDIRECTORY") 	ParseCFdirectory($AttributeLine,$output);
							else if(UCASE($tagName)==="CFFILE") 		ParseCFfile($AttributeLine,$output);
							else if(UCASE($tagName)==="CFINCLUDE") 		ParseCFinclude($AttributeLine,$output);
							else if(UCASE($tagName)==="CFFUNCTION") 	ParseCFfunction($AttributeLine,$output);
							else if(UCASE($tagName)==="CFABORT") 		ParseCFabort($AttributeLine,$output);
							else if(UCASE($tagName)==="CFFORM") 		ParseCFform($AttributeLine,$output);
							
							if($DebugLevel>=2) echo " &&& $tagName<br>\n"; 
							//for ($m=0; $m<$nAtt; $m++) {
							//	$AttributeName2=implode(" ",$AttributeArr[$m][0]);
							//	$AttributeVal2=implode(" ",$AttributeArr[$m][1]);
							//	echo "[$m] *** $AttributeName2 = $AttributeVal2 <br>\n"; 
							//}
						}
						$InCFtag=false; $InTagName=false; $tagName=""; $InAttributeS=false; $AttributeName=""; $InAttributeVal=false; $AttributeVal="";
						$InAttributeValDQuote=false; $InAttributeValSQuote=false; $nAtt=0; $AttributeArr=array(); $AttributeLine="";
						$SkipChar=true;
					}
					if($InTagName && $line[$i]===" "){ 
						$InTagName=false; 
						$InAttributeS=true;
						$InAttributeName=true;
						$AttributeName="";
						$AttributeLine="";
						$InAttributeValDQuote=false; $InAttributeValSQuote=false; 
						$i++;
						//echo "[ ]";
					}
					
					if($InAttributeS){
						if($InAttributeName && $line[$i]==="="){ 
							$AttributeVal="";
							$InAttributeName=false;
							$InAttributeVal=true;
							//$i++;
							//echo "[=]";
						}
						if($InAttributeVal && $line[$i]===" " and !$InAttributeValDQuote and !$InAttributeValSQuote){ 
							// Assign previous attribute to attribute array
							//if($AttributeName !== ''){
							//	$AttributeArr[$nAtt][0]=$AttributeName;
							//	$AttributeArr[$nAtt][1]=$AttributeVal;
							//	$nAtt++;
							//}
							//Start new Attribute
							$AttributeName="";
							$AttributeVal="";
							$InAttributeName=true;
							$InAttributeVal=false;
							//$i++;
							//echo "[ ]";
						}
						
						if($InAttributeVal && $line[$i]==='"'){
							if($InAttributeValDQuote) $InAttributeValDQuote=false;
							else $InAttributeValDQuote=true;
							//$i++;
							//echo '["]';
							
						}
						
						if($InAttributeVal && $line[$i]==="'"){ 
							if($InAttributeValSQuote) $InAttributeValSQuote=false;
							else $InAttributeValSQuote=true;
							//$i++;
							//echo "[']";
						}
						
						//if($InAttributeName) $AttributeName.=$line[$i];
						//if($InAttributeVal) $AttributeVal.=$line[$i];
						$AttributeLine.=$line[$i];
						
					}
					// ####  Really, really, weird ... "</cf" is detected, but uppercase "</CF" or "</cF" or "</Cf" is NOT !!! #### //
					// ####  This is my first item on the list for someone else to fix ...                                     #### //
					if($line[$i]==="<" and $line[$i+1]==="/" and LCASE($line[$i+2])==="c" and LCASE($line[$i+3])==="f"){
						$InCFEndtag=true; $EndTagName="";
						$i++;$i++;
					}
					if($line[$i]===">" and $InCFEndtag){
						if(UCASE($EndTagName)==="CFSCRIPT"){ $InCFscript=false; }
						
						if(UCASE($EndTagName)==="CFQUERY"){ 
							//echo "ParseCFqueryCall";
							ParseCFquery($InnerHTMLTagAttributeLine,$InnerHTML,$output);
							$InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";
						}
						
						//$output.="< }//$EndTagName >";
						$output.="[/".UCASE($EndTagName)."]";
						$InCFEndtag=false; $EndTagName="";
						//$i++;
						$SkipChar=true;
					}
					
					if($InCFEndtag){
						//echo $line[$i];
						if(!$SkipChar) $EndTagName.=$line[$i]; $SkipChar=false;
					} else if($InCFtag){
						//echo $line[$i];
						if($InTagName) $tagName.=LCASE($line[$i]);
						if($DebugLevel===3) echo "[$tagName]";
					} else if($InsideInnerHTML and !$SkipChar){
						$InnerHTML.=$line[$i];
					} else {
						// HTML after tag or inbetween tag and ending-tag ...
						if(!$SkipChar) $output.=$line[$i]; $SkipChar=false;
					}
					
					
				}
			} else {
				// HTML line ...
				$output.="$line";
			}
			
		}
		fclose($file);
	} else {
		// error opening the file.
	} 
	
	return $output;
}	

?>

