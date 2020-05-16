<?php

function ParseCFoutput($AttributeLine,&$output){
	//$output.="[CFOUTPUT $AttributeLine]";
	
	$AttributeArr=ParseAttributeLine($AttributeLine." x");
	$out="<?php ";
	$cfoutput_query=""; //$cfloop_to=""; $cfloop_index=""; $cfloop_step="";
	for ($nAtt=0; $nAtt<ArrayLen($AttributeArr['AttributeName']); $nAtt++){ //echo "[".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]."]<br>\n";
		if($AttributeArr['AttributeName'][$nAtt] !== ""){
			     if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "QUERY") 	$cfoutput_query=trim($AttributeArr['AttributeVal'][$nAtt]);
			//else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "TO") 	$cfloop_to=trim($AttributeArr['AttributeVal'][$nAtt]);
			//else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "INDEX") 	$cfloop_index=trim($AttributeArr['AttributeVal'][$nAtt]);
			//else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "STEP")   $cfloop_step=trim($AttributeArr['AttributeVal'][$nAtt]);
			else ; //$out.=" ".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]." ";
		}
	}
	
	if($cfoutput_query!=="" ){ //and $cfloop_to!=="" and $cfloop_index!==""){
		$out.=" \$nrow=0; while(\$row = \$".$cfoutput_query."->fetchArray()) { \$CurrentRow=\$nrow+1;"; //cfdump(\$row);echo \$".$cfoutput_query."->columnName(\$ncol); 
		$out.="for(\$ncol=0; \$ncol< \$".$cfoutput_query."->numColumns(); \$ncol++) { \${\$".$cfoutput_query."->columnName(\$ncol)}=\$row[\$ncol]; } \$nrow++; \$".$cfoutput_query."_Recordcount=\$nrow; \$Recordcount=\$nrow; ";
		$out.="//CFOUTPUT ?>";
	}
	$output.=$out;
}

?>