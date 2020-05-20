<?php

function ParseCFloop($AttributeLine,&$toPHPtranslation){
	//echo "[CFLOOP $AttributeLine]<br>\n";
	
	$AttributeArr=ParseAttributeLine($AttributeLine." x");
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
		if($cfloop_to>$cfloop_from) $cf_direction="<="; else $cf_direction=">=";
		$out.="for( $index=".DetectVariables($cfloop_from,"NO")."; $index$cf_direction".DetectVariables($cfloop_to,"NO")."; ".DetectVariables($cfloop_index,"NO")."=".DetectVariables($cfloop_index,"NO")."+$cfloop_step ){";
		$out.="//CFLOOP ?>";
	}
	
	
	$toPHPtranslation.=$out; //"[CFLOOP $AttributeLine]";
	
	
	

}

?>