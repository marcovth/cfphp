<?php

//Find_FindNoCase.php

function Find($find,$string,$start=NULL){
	if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	if($start>strlen($string)-1) return false;
	if(strlen($string)==0) return false; 
	if(strlen($find)==0) return false; 
	$found=mb_strpos($string,$find,$start);
	if($found===false) return false;
	else return $found+1; // Returns CFML 1-based offset or false
}

function FindNoCase($find,$string,$start=NULL){
	if(NULL===$start) $start=0; else if($start>0) $start=$start-1; // Index PHP 0 == CFML 1 etc; 
	if($start>strlen($string)-1) return false;
	if(strlen($string)==0) return false; 
	if(strlen($find)==0) return false; 
	$found=mb_stripos($string,$find,$start);
	if($found===false) return false;
	else return $found+1; // Returns CFML 1-based offset or false
}

?>