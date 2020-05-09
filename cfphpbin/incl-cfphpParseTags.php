<?php
//require './cfphpbin/incl-cfphpFunctions.php';


function ParseAttributeLine($AttributeLine){
	//$Attributes=explode(" ",$AttributeLine);
	$nAtt=0; $AttributeArr=array();
	$InAttributeValDQuote=false; $InAttributeValSQuote=false;  
	$InAttributeName=true; $AttributeName="";
	$InAttributeVal=false;  $AttributeVal="";
	$LastChar=false;
	for ($i=0; $i<strlen(trim($AttributeLine)); $i++){							//echo "$AttributeLine[$i]";
		
		if($i==strlen(trim($AttributeLine))-1) $LastChar=true;
		
		if(!$InAttributeValDQuote and !$InAttributeValSQuote and ($AttributeLine[$i]===" " or $AttributeLine[$i]==="	" or $LastChar) ){  //or $i==0
			// Push previous attribute to attribute array ...
																				//echo "$AttributeName==$AttributeVal\n";
			//if(trim($AttributeVal)!=="" or $AttributeVal!=null){
				$AttributeArr['AttributeName'][$nAtt]=$AttributeName;			//echo "$AttributeName==$AttributeVal\n";
				$AttributeArr['AttributeVal'][$nAtt]=$AttributeVal;									
				$nAtt++;
			//}
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
			$AttributeName.=$AttributeLine[$i];									//echo "[".$AttributeLine[$i]."]";
		}
		
		if($InAttributeVal){
			$AttributeVal.=$AttributeLine[$i];									//echo "{".$AttributeLine[$i]."}";
		}
	
	}
	//if(trim($AttributeVal)!=="" or $AttributeVal!=null){
		$AttributeArr['AttributeName'][$nAtt]=$AttributeName;					//echo "$AttributeName==$AttributeVal\n";
		$AttributeArr['AttributeVal'][$nAtt]=$AttributeVal;									
	//}
	//if($AttributeName!==""){
	//	$AttributeArr['AttributeName'][$nAtt]=$AttributeName;
	//	$AttributeArr['AttributeVal'][$nAtt]=$AttributeVal;
	//																			echo "$AttributeName+$AttributeVal\n";
	//}
	//echo "<br>\n";
	//cfdump($AttributeArr);
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
	
	$output.=$out; $output.=";//cfset ?>";
}

function ParseCFloop($AttributeLine,&$output){
	//echo "[CFLOOP $AttributeLine]<br>\n";
	
	$AttributeArr=ParseAttributeLine($AttributeLine);
	//cfdump($AttributeArr); echo "(".ArrayLen($AttributeArr['AttributeName']).")";
	$out="<?php ";
	$cfloop_from=""; $cfloop_to=""; $cfloop_index=""; $cfloop_step="";
	for ($nAtt=0; $nAtt<ArrayLen($AttributeArr['AttributeName']); $nAtt++){ //echo "[".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]."]<br>\n";
		if($AttributeArr['AttributeName'][$nAtt] !== ""){
			     if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "FROM") 	$cfloop_from=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "TO") 	$cfloop_to=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "INDEX") 	$cfloop_index=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "STEP"){
				$cfloop_step=trim($AttributeArr['AttributeVal'][$nAtt]);			//echo "step=[".trim($AttributeArr['AttributeVal'][$nAtt])."]";
			} else ; //$out.=" ".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]." ";
		}
	}
	
	if($cfloop_from!=="" and $cfloop_to!=="" and $cfloop_index!==""){
		//echo "step=$cfloop_step";
		if($cfloop_step==="" or $cfloop_step==null) $cfloop_step=1;
		$index=DetectVariables($cfloop_index,"NO");
		$out.="for( $index=".DetectVariables($cfloop_from,"NO")."; $index<=".DetectVariables($cfloop_to,"NO")."; ".DetectVariables($cfloop_index,"NO")."=".DetectVariables($cfloop_index,"NO")."+$cfloop_step; ){";
		$out.="//CFLOOP ?>";
	}
	
	
	$output.=$out; //"[CFLOOP $AttributeLine]";
	
	
	

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