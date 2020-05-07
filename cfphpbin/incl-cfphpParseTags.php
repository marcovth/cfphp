<?php
//require './cfphpbin/incl-cfphpFunctions.php';

function IsVariable($string){
	global $cfFunctionNames, $phplanguagekeywords;
	if(FindNoCase($string,$phplanguagekeywords)>0 or FindNoCase($string,$cfFunctionNames)>0) return 1;
	else return 0;
}


function DetectVariables($string,$Addtags){ 									//echo "$Addtags";
	//$VariableEndingChars="[~`!@#$%^&*()_\-+=\[\]{}\|\\:;\"\'<,>.]/"; 
	$out=""; $InVariable=false; $InVariableDollar=false; $Variable="";
	if($Addtags==="yes") $Add=true; else $Add=false;							//echo "$Add";
	//$string=ltrim($string); // trim and ltrim are cutting off strings prematurely !!!
	for($i=0; $i<strlen($string); $i++){										//echo "$string[$i]";
		$c=$string[$i];															//echo "$c".IsEndingCharVariables($c)."<br>\n";
		if(!$Add){ // default, only remove ## and print pre-pend $	
			if($string[$i]==="#"){
				if(!$InVariable){
					$out.="$";
					$InVariable=true;
				} else {
					// Do nothing == remove #
					$InVariable=false;
				}
			} else $out.=$string[$i];
			
		} else { // Add PHP tags to print out variables
			if($string[$i]==="#"){
				if(!$InVariable){
					$Variable.="$";
					$InVariable=true;
				} else {
					// print variable, remove #
					if(strlen($Variable)>1) $out.="<?php echo ".$Variable."; ?>"; else $out.="#";
					$InVariable=false; $InVariableDollar=false; $Variable="";
				}
			} else if($string[$i]==="$"){
				if(!$InVariableDollar){
					$Variable.="$";
					$InVariableDollar=true;
				}	
			} else if($InVariableDollar and IsEndingCharVariables($string[$i])){		//echo "$c".IsEndingCharVariables($c); // 
					// print variable
					if(strlen($Variable)>1) $out.="<?php echo ".$Variable."; ?>$c";
					$InVariableDollar=false; $InVariable=false; $Variable="";
			} else if($InVariable or $InVariableDollar){ $Variable.=$c;
			} else $out.=$c;
		}
	}																			//echo "<br>\n";
																				//echo "### $string<br>\n*** $out<br>\n";
	return $out;
}



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
	//$output.="[CFSET $AttributeLine]";
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
	
	$output.=$out; $output.=" ?>";
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
	$output.=$out; //"[CFPARAM $AttributeLine]";

}

function ParseCFif($AttributeLine,&$output){
	//$output.="[CFIF $AttributeLine]";
	$out="<?php if( ";
	$words=explode(" ",$AttributeLine);
	$n=0;
	foreach($words as &$word) {
		$n++;
		//if(!empty($word)){
			if(UCASE($word)==="IS") $out.="==";
			else if(UCASE($word)==="NOT") $out.="!";
			else if(UCASE($word)==="AND") $out.="and ";
			else if(UCASE($word)==="OR") $out.="or ";
			else if(UCASE($word)==="GT") $out.="> ";
			else if(UCASE($word)==="GTE") $out.=">= ";
			else if(UCASE($word)==="LT") $out.="< ";
			else if(UCASE($word)==="LTE") $out.="<= ";
			else if($n==1 and Mid($word,1,1)!=="$" and !Find("(",$word) and !IsVariable($word) ) $out.="$".$word." ";
			else $out.=DetectVariables($word,"no")." ";
		//}
		
	}
	unset($word);
	$output.=$out; $output.="){ ?>";
	
}

function ParseCFelse($AttributeLine,&$output){

$output.="<?php } else { ?>";

}

function ParseCFelseif($AttributeLine,&$output){
	//$output.="[CFELSEIF $AttributeLine]";
	$out="<?php } else if( ";
	$words=explode(" ",$AttributeLine);
	foreach($words as &$word) {
		//if(!empty($word)){
			if(UCASE($word)==="IS") $out.="==";
			else if(UCASE($word)==="NOT") $out.="!";
			else if(UCASE($word)==="AND") $out.="and ";
			else if(UCASE($word)==="OR") $out.="or ";
			else if(UCASE($word)==="GT") $out.="> ";
			else if(UCASE($word)==="GTE") $out.=">= ";
			else if(UCASE($word)==="LT") $out.="< ";
			else if(UCASE($word)==="LTE") $out.="<= ";
			else $out.=DetectVariables($word,"no")." ";
		//}
	}
	unset($word);
	$output.=$out; $output.="){ ?>";

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