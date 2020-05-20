<?php

function ParseCFif($AttributeLine,&$toPHPtranslation){
	//$toPHPtranslation.="[CFIF $AttributeLine]";
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
	//if(null===$toPHPtranslation){
	//	return $out."){ ?]";
	//} else { 
		$toPHPtranslation.=$out; $toPHPtranslation.="){ ?>";
	//}
	
}

function ParseCFelse($AttributeLine,&$toPHPtranslation){

$toPHPtranslation.="<?php } else { ?>";

}

function ParseCFelseif($AttributeLine,&$toPHPtranslation){
	//$toPHPtranslation.="[CFELSEIF $AttributeLine]";
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
	$toPHPtranslation.=$out; $toPHPtranslation.="){ ?>";

}

?>