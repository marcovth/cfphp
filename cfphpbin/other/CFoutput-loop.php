<?php

$nrow=0; 
while($row = ${$cfoutput_query->fetchArray()}) {
	$CurrentRow=$nrow+1; //cfdump(\$row);
	//echo \$".$cfoutput_query."->columnName(\$ncol); 
	for($ncol=0; $ncol<${$cfoutput_query->numColumns()}; $ncol++) { 
		${$cfoutput_query->columnName($ncol)}=$row[$ncol]; 
	} 
	$nrow++; ${$cfoutput_query."_Recordcount"}=$nrow; $Recordcount=$nrow;
}


?>