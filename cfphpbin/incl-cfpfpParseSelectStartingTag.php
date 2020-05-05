<?php
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
							
?>