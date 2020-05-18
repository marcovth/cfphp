<?php


function ArrayLen($array){
	// Which one is better? ...
	//if(count($array)>0) return count(reset($array));
	if(!empty($array)) return sizeof($array);
	else return 0;
}

?>