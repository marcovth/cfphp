<?php
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
				// cfPHP starting tag selector ...
				include "./cfphpbin/incl-cfpfpParseSelectStartingTag.php";
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


?>