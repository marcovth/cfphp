<?php
		//echo UCASE($tagName)."\n";
         if(UCASE($tagName)==="CFSET"){     	ParseCFset($AttributeLine,$output);  		//echo "[CFSET $AttributeLine]\n"; 
	} else if(UCASE($tagName)==="CFLOOP"){ 		ParseCFloop($AttributeLine,$output);  		//echo "[CFLOOP $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFOUTPUT"){	ParseCFoutput($AttributeLine,$output);  	//echo "[CFOUTPUT $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFPARAM"){ 	ParseCFparam($AttributeLine,$output);  		//echo "[CFPARAM $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFIF"){ 		ParseCFif($AttributeLine,$output);  		//echo "[CFIF $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFELSE"){ 		ParseCFelse($AttributeLine,$output);  		//echo "[CFELSE $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFELSEIF"){ 	ParseCFelseif($AttributeLine,$output);  	//echo "[CFELSEIF $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFTRY"){		ParseCFtry($AttributeLine,$output);  		//echo "[CFTRY $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFCATCH"){ 	ParseCFcatch($AttributeLine,$output);  		//echo "[CFCATCH $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFARGUMENT"){	ParseCFargument($AttributeLine,$output);  	//echo "[CFARGUMENT $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFRETURN"){ 	ParseCFreturn($AttributeLine,$output);  	//echo "[CFRETURN $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFSCRIPT"){	ParseCFscript($AttributeLine,$output); $InCFscript=true; 
	} else if(UCASE($tagName)==="CFQUERY"){
		//ParseCFquery($AttributeLine,$output);
		$InsideInnerHTML=true; $InnerHTML=""; $InnerHTMLTagAttributeLine=$AttributeLine; $SkipChar=true;
	} else if(UCASE($tagName)==="CFDIRECTORY"){ ParseCFdirectory($AttributeLine,$output);  	//echo "[CFDIRECTORY $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFFILE"){ 		ParseCFfile($AttributeLine,$output);  		//echo "[CFFILE $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFINCLUDE"){ 	ParseCFinclude($AttributeLine,$output);  	//echo "[CFINCLUDE $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFFUNCTION"){ 	ParseCFfunction($AttributeLine,$output);  	//echo "[CFFUNCTION $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFABORT"){ 	ParseCFabort($AttributeLine,$output);  		//echo "[CFABORT $AttributeLine]<br>\n"; 
	} else if(UCASE($tagName)==="CFFORM"){ 		ParseCFform($AttributeLine,$output);  		//echo "[CFFORM $AttributeLine]<br>\n"; 
	}
	if($DebugLevel>=2) echo " &&& $tagName<br>\n"; 
	//for ($m=0; $m<$nAtt; $m++) {
	//	$AttributeName2=implode(" ",$AttributeArr[$m][0]);
	//	$AttributeVal2=implode(" ",$AttributeArr[$m][1]);
	//	echo "[$m] *** $AttributeName2 = $AttributeVal2 <br>\n"; 
	//}
							
?>