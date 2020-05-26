<?php

//CFdirectory_CFfile_CFinclude.php

function ParseCFdirectory($AttributeLine,&$toPHPtranslation){
	//$toPHPtranslation.="[CFDIRECTORY $AttributeLine]";
	// echo "[CFDIRECTORY $AttributeLine]<br>\n";
	// action="list" directory="#expandPath("./")#" recurse="false" name="myList"
	
	$AttributeArr=ParseAttributeLine($AttributeLine." x");
	
	$cf_1=""; $cf_2=""; $cf_3=""; $cf_4=""; $cf_5="";
	for ($nAtt=0; $nAtt<ArrayLen($AttributeArr['AttributeName']); $nAtt++){ //echo "[".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]."]<br>\n";
		if($AttributeArr['AttributeName'][$nAtt] !== ""){
			     if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "ACTION") 	$cf_1=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "DIRECTORY") 	$cf_2=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "NAME") 		$cf_3=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "FILTER") 	$cf_4=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "RECURSE")  	$cf_5=trim($AttributeArr['AttributeVal'][$nAtt]);
		}
	}
	//echo "[$cf_1][$cf_2][$cf_3][$cf_4][$cf_5]<br>\n";
	
	$out="";
	if(UCASE(trim($cf_1))==="LIST" and $cf_2!=="" and $cf_3!==""){
		if(trim($cf_5)!=="" and ( UCASE(trim($cf_5))==="TRUE" or UCASE(trim($cf_5))==="YES" ) ) $recurse=1;
		else $recurse=0;
		
		//echo $cf_2." 0<br>\n";
		$path=DetectVariables($cf_2,false);
		$path=Replace($path,"'","","ALL");
		//echo $path." 1<br>\n";
		$path=addslashes($path);
		//echo $path." 2<br>\n";
		//$path=evaluate($path);
		//echo $path." 3<br>\n";
		//$path=expandPath("./");
		//echo $path." 4<br>\n";
		$out="<?php \$$cf_3=ListDirectory(\"".$cf_3."\",evaluate(\"".$path."\"),\"".$cf_4."\",\"name\",\"ASC\",$recurse); //CFDIRECTORY ?>";
	}
	$toPHPtranslation.=$out;

}



function ParseCFfile($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFFILE $AttributeLine]";

}

function ParseCFinclude($AttributeLine,&$toPHPtranslation){

	$toPHPtranslation.="[CFINCLUDE $AttributeLine]";

}


?>