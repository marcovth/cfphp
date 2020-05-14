<?php

function RemoveAllQuotes($string){
	return str_replace(array("'",'"'),"",$string );
}
function RemoveSurroundingQuotes($string){
	$string=rtrim($string,'"'); $string=ltrim($string,'"');
	$string=rtrim($string,"'"); $string=ltrim($string,"'");
	return $string;
}

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
				$AttributeArr['AttributeName'][$nAtt]=RemoveSurroundingQuotes($AttributeName); //echo "$AttributeName==$AttributeVal\n";
				$AttributeArr['AttributeVal'][$nAtt]=RemoveSurroundingQuotes($AttributeVal);									
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

	$AttributeArr['AttributeName'][$nAtt]=RemoveSurroundingQuotes($AttributeName); //echo "$AttributeName==$AttributeVal\n";
	$AttributeArr['AttributeVal'][$nAtt]=RemoveSurroundingQuotes($AttributeVal);									
	//echo "<br>\n";
	//cfdump($AttributeArr);
	return $AttributeArr;
}


// ###### INCLUDE ALL THE TAGS TO BE TRANSLATED ...

$SingleLineTags=ListDirectory($GLOBALS["cf_webRootDir"]."/cfphpbin/SingleLineTags/",'/\.php/i', 'name', 1); //print_r($SingleLineTags);
foreach($SingleLineTags as $TagFile){
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/SingleLineTags/".$TagFile;
}

$BlockTags=ListDirectory($GLOBALS["cf_webRootDir"]."/cfphpbin/BlockTags/",'/\.php/i', 'name', 1); //print_r($BlockTags);
foreach($BlockTags as $TagFile){
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/BlockTags/".$TagFile;
}

$cSingleLineTags=ListDirectory($GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/SingleLineTags/",'/\.php/i', 'name', 1); //print_r($cSingleLineTags);
foreach($cSingleLineTags as $TagFile){
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/SingleLineTags/".$TagFile;
}

$cBlockTags=ListDirectory($GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/BlockTags/",'/\.php/i', 'name', 1); //print_r($cBlockTags);
foreach($cBlockTags as $TagFile){
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/BlockTags/".$TagFile;
}


?>