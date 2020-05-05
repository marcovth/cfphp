<?php

//require './incl-cfphpFunctions.php';

function ParseAttributeLine($AttributeLine){
	//$Attributes=explode(" ",$AttributeLine);
	$nAtt=0; $AttributeArr=array();
	$InAttributeValDQuote=false; $InAttributeValSQuote=false;  
	$InAttributeName=false; $AttributeName="";
	$InAttributeVal=false;  $AttributeVal="";
	for ($i=0; $i<strlen(trim($AttributeLine)); $i++){
		if(!$InAttributeValDQuote and !$InAttributeValSQuote and ($AttributeLine[$i]===" " or $i==0) ){ 
			// Push previous attribute to attribute array ...
			if($AttributeLine[$i]===" "){
				$AttributeArr['AttributeName'][$nAtt]=$AttributeName;
				$AttributeArr['AttributeVal'][$nAtt]=$AttributeVal;
				//echo "$AttributeName+$AttributeVal\n";
				$nAtt++;
			}
			$InAttributeName=true;
			$InAttributeVal=false;
			$AttributeName=""; $AttributeVal="";
			$InAttributeValDQuote=false; $InAttributeValSQuote=false; 
			if($AttributeLine[$i]===" ") $i++;
			//echo "[ ]";
		}
		
		if($InAttributeName && $AttributeLine[$i]==="="){ 
			$AttributeVal="";
			$InAttributeName=false;
			$InAttributeVal=true;
			$i++;
			//echo "[=]";
		}
		
		if($InAttributeVal and $AttributeLine[$i]==='"'){
			if($InAttributeValDQuote) $InAttributeValDQuote=false;
			else $InAttributeValDQuote=true;
			//$i++;
			//echo '["]';
			
		}
		
		if($InAttributeVal and $AttributeLine[$i]==="'"){ 
			if($InAttributeValSQuote) $InAttributeValSQuote=false;
			else $InAttributeValSQuote=true;
			//$i++;
			//echo "[']";
		}
		
		
		if($InAttributeName){
			$AttributeName.=$AttributeLine[$i];
		}
		
		if($InAttributeVal){
			$AttributeVal.=$AttributeLine[$i];
		}
	
	}
	if($AttributeName!==""){
		$AttributeArr['AttributeName'][$nAtt]=$AttributeName;
		$AttributeArr['AttributeVal'][$nAtt]=$AttributeVal;
		//echo "$AttributeName+$AttributeVal\n";
	}
	return $AttributeArr;
}



function ParseCFset($AttributeLine,&$output){

	$output.="[CFSET $AttributeLine]";

}

function ParseCFloop($AttributeLine,&$output){

	$output.="[CFLOOP $AttributeLine]";

}

function ParseCFoutput($AttributeLine,&$output){

	$output.="[CFOUTPUT $AttributeLine]";

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
	$out.="]<br>\n";
	$output.=$out; //"[CFPARAM $AttributeLine]";

}

function ParseCFif($AttributeLine,&$output){

	$output.="[CFIF $AttributeLine]";

}

function ParseCFelse($AttributeLine,&$output){

	$output.="[CFELSE $AttributeLine]";

}

function ParseCFelseif($AttributeLine,&$output){

	$output.="[CFELSEIF $AttributeLine]";

}

function ParseCFtry($AttributeLine,&$output){

	$output.="[CFTRY $AttributeLine]";

}

function ParseCFcatch($AttributeLine,&$output){

	$output.="[CFCATCH $AttributeLine]";

}

function ParseCFargument($AttributeLine,&$output){

	$output.="[CFARGUMENT $AttributeLine]";

}

function ParseCFreturn($AttributeLine,&$output){

	$output.="[CFRETURN $AttributeLine]";

}


function ParseCFscript($AttributeLine,&$output){

	$output.="[CFSCRIPT $AttributeLine]";

}


function ParseCFdirectory($AttributeLine,&$output){

	$output.="[CFDIRECTORY $AttributeLine]";

}

function ParseCFfile($AttributeLine,&$output){

	$output.="[CFFILE $AttributeLine]";

}

function ParseCFinclude($AttributeLine,&$output){

	$output.="[CFINCLUDE $AttributeLine]";

}

function ParseCFfunction($AttributeLine,&$output){

	$output.="[CFFUNCTION $AttributeLine]";

}

function ParseCFabort($AttributeLine,&$output){

	$output.="[CFABORT $AttributeLine]";

}

function ParseCFform($AttributeLine,&$output){

	$output.="[CFFORM $AttributeLine]";

}















?>