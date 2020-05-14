<?php

function ParseSQL($string){	
	
	
	if(FindNoCase("<cf",$string)>0){
		$SQL=""; $n=1;
		for($i=1; $i<strlen($string); $i++){																	//echo "$string[$i]";
			//$c=$string[$i];
			$TagStart=FindNoCase("<cf",$string,$i); 					//echo "[start=$TagStart]\n";
			if($TagStart==0)$TagStart=FindNoCase("</cf",$string,$i); 	//echo "[EndTagstart=$TagStart]\n";
			$InnerSQL=Mid($string,$i,($TagStart-$i));					//echo "[$InnerSQL]\n";
			if(trim($InnerSQL)!==""){
				$SQL.="<?php \$SQL.=\"$InnerSQL\"; ?>\n";
			}
			//if($i>0) $i=$TagStart;
			$TagEnd=FindNoCase(">",$string,$i); 						//echo "[end=$TagEnd]\n";
			$Tag=Mid($string,$TagStart,($TagEnd-$TagStart)+1);			
			if(trim($Tag)!==""){										//echo "[Tag=$Tag]";
				$Tag=ltrim($Tag,"\<"); $Tag=rtrim($Tag,"\>"); 			//echo "[Tag=$Tag]\n";
				if(UCASE(Mid($Tag,1,3))==="/CF"){ 
					$Tag=ltrim($Tag,"\/");								//echo "[EndTag=$Tag]\n";
					$SQL.="<?php }//$Tag ?>\n";
				} else {
					$TagName=ListFirst($Tag," ");
					$AttributeLine=trim(Replace($Tag,$TagName,""));		//echo "[Tag=$TagName][$AttributeLine]\n";
					$func="Parse"."$TagName";
					$func($AttributeLine,$SQL);
				}
			}
			if($TagEnd>0) $i=$TagEnd;
			$n=$i;
		}
		$InnerSQL=Mid($string,$n,$TagStart-$n);							//echo "[$InnerSQL]\n\n\n";
		if(trim($InnerSQL)!==""){
			$SQL.="<?php \$SQL.=\"$InnerSQL\"; ?>\n";
		}
		//echo "[$SQL]\n\n\n";
		
		return $SQL; //"CF".FindNoCase("<cf",$string);
	} else {
		return "<?php \$SQL.=\"$string\"; ?>\n";
	}
}



?>