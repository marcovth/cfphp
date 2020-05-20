<?php
	// Line with a CF tag ...
	$DebugLevel=0;
	$InCFtag=false; $InTagName=false; $tagName=""; $InAttributeS=false; $InAttributeName=false; $AttributeName=""; $InAttributeVal=false; $AttributeVal="";
	$InAttributeValDQuote=false; $InAttributeValSQuote=false; $nAtt=0; $AttributeArr=array(); $AttributeLine="";
	$InCFEndtag=false; $EndTagName=""; $InBetweenOrAfterCFTagsHTML=""; 
	//echo $line;
	//echo "<br>";
	for ($i=0; $i<strlen($line); $i++){
		$cf_start=Mid($line,$i+1,3);// echo "$cf_start ";
		//if($line[$i]==="<" and preg_match('/(c)/i', $line[$i+1]) and preg_match('/(f)/i', $line[$i+2])){
		if(UCASE($cf_start)==="<CF"){
			$InCFtag=true; $InTagName=true;
			$i++;
		}
		$SkipChar=false;
		if($line[$i]===">" and $InCFtag and !$InAttributeValDQuote and !$InAttributeValSQuote){ 
			//if($DebugLevel>=2) echo "%$tagName%";
			if($tagName !== ''){
				// cfPHP starting tag selector ...
				$toPHPtranslation.=DetectVariables($InBetweenOrAfterCFTagsHTML,"yes"); $InBetweenOrAfterCFTagsHTML=""; // In between two CF-tags HTML
				//include $GLOBALS["cf_webRootDir"]."/cfphpbin/incl-cfpfpParseSelectStartingTag.php";
				
				// ######### STARTING TAG SELECTION #########
				if(UCASE($tagName)==="CFSCRIPT"){	
					ParseCFscript($AttributeLine,$toPHPtranslation); $InCFscript=true; 
				} else if(UCASE($tagName)==="CFQUERY"){
					$InsideInnerHTML=true; $InnerHTML=""; 
					$InnerHTMLTagAttributeLine=$AttributeLine; $SkipChar=true;
				} else {
					try{ // Dynamic function call ...
						$func="Parse"."$tagName";
						$func($AttributeLine,$toPHPtranslation);
					} catch ( \Exception $e ) {
						
					}
				}
				
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
			
			if($InAttributeVal && $line[$i]==="\""){
				if($InAttributeValDQuote){
					$InAttributeValDQuote=false;
					$InAttributeValSQuote=false;
				} else {
					$InAttributeValDQuote=true;
				}
				//$i++;
				//echo '["]';
				
			}
			
			if($InAttributeVal && $line[$i]==="'"){ 
				if($InAttributeValSQuote){
					$InAttributeValSQuote=false;
					$InAttributeValDQuote=false;
				} else {
					$InAttributeValSQuote=true;
				}
				//$i++;
				//echo "[']";
			}
			
			//if($InAttributeName) $AttributeName.=$line[$i];
			//if($InAttributeVal) $AttributeVal.=$line[$i];
			$AttributeLine.=$line[$i];
			
		}
		
		$cf_end=Mid($line,$i+1,4);// echo "$cf_end ";
		if(UCASE($cf_end)==="</CF"){
			$InCFEndtag=true; $EndTagName="";
			$i++;$i++;
		}
		if($line[$i]===">" and $InCFEndtag){
			
			$toPHPtranslation.=DetectVariables($InBetweenOrAfterCFTagsHTML,"yes"); // HTML before ending-CF-tags = mostly whitespace
			$InBetweenOrAfterCFTagsHTML="";
			
			// ###############  END TAGS ###################
			if(UCASE($EndTagName)==="CFSCRIPT"){ 
				$InCFscript=false; 
				$toPHPtranslation.="<?php }//$EndTagName ?>";
			} else if(UCASE($EndTagName)==="CFQUERY"){ 
				//echo "ParseCFqueryCall";
				ParseCFquery($InnerHTMLTagAttributeLine,$InnerHTML,$toPHPtranslation);
				$InsideInnerHTML=false; $InnerHTML=""; $InnerHTMLTagAttributeLine="";
			} else {
				$toPHPtranslation.="<?php }//$EndTagName ?>";
			}
			// ###############  END TAGS ###################
			
			//$toPHPtranslation.="[/".UCASE($EndTagName)."]";
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
			// HTML after (closing) tag, or in between CF-tags ...
			if(!$SkipChar) $InBetweenOrAfterCFTagsHTML.=$line[$i]; $SkipChar=false;
		}
		
		
	}
	$toPHPtranslation.=DetectVariables($InBetweenOrAfterCFTagsHTML,"yes"); // After the last CF-tags HTML

?>