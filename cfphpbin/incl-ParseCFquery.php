<?php


function ParseCFquery($AttributeLine,$InnerHTML,&$output){
	//echo "ParseCFqueryF";
	$InnerHTML2 = preg_replace('/\s+/', ' ', trim($InnerHTML));
	$InnerHTML3=ParseNestedTags($InnerHTML2);
	$output.="[CFQUERY $AttributeLine]$InnerHTML3";
	
	//$query_famRemarks = "SELECT * FROM famRemarks WHERE hide=0 AND persID =".$row_person['persID'];
	//echo $query_famRemarks;
	//$famRemarks = mysql_query($query_famRemarks, $stamboom) or die("qryfamRemarks: ".mysql_error()."<hr>".$query_famRemarks);
	//$row_famRemarks = mysql_fetch_assoc($famRemarks);
	//$totalRows_famRemarks = mysql_num_rows($famRemarks);
	//$famID=$row_famRemarks['famID'][0];
	
	

}




?>