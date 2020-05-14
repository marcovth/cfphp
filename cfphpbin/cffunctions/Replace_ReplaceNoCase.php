<?php

//Replace_ReplaceNoCase.php


function Replace($string,$substring,$replaceString,$scope=NULL){
	//Replace(string, substring1, obj [, scope ])
	if($scope=="ALL"){ 
		$rpl=str_replace($substring,$replaceString,$string);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl; 
	} else if(NULL===$scope){ // default
		$scope=1; 
		$rpl=implode($replaceString, explode($substring, $string, 2)); //str_replace($substring,$replaceString,$string,$scope);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl;
	} else {
		$rpl=str_replace($substring,$replaceString,$string,$scope);
		//echo "{Replace$scope | $string | $rpl]";
		return $rpl;
	}
}

function ReplaceNoCase($string,$substring,$replaceString,$scope=NULL){
	//Replace(string, substring1, obj [, scope ])
	if($scope=="ALL"){ 
		$rpl=str_ireplace($substring,$replaceString,$string);
		//echo "ReplaceNoCase[$scope | $string | $rpl]<br>\n";
		return $rpl; 
	} else if(NULL===$scope){ // default
		$scope=1; 
		//$rpl=implode(UCASE($replaceString), explode($substring,UCASE($string),2)); 
		$rpl=str_ireplace($substring,$replaceString,$string,$scope);
		//echo "ReplaceNoCase[$scope | $string | $rpl]<br>\n";
		return $rpl;
	} else {
		$rpl=str_ireplace($substring,$replaceString,$string,$scope);
		//echo "ReplaceNoCase[$scope | $string | $rpl]<br>\n";
		return $rpl;
	}
}





?>