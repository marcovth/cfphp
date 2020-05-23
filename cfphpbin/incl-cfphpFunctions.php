<?php

function FileExists($filePath){
	return file_exists($filePath);
}


function ListDirectoryArr($dir, $filter, $SortBy='name', $desc=0){
	// ##Files can be sorted on name and stat() attributes, ascending and descending:
	// name    file name
	// dev     device number
	// ino     inode number
	// mode    inode protection mode
	// nlink   number of links
	// uid     userid of owner
	// gid     groupid of owner
	// rdev    device type, if inode device *
	// size    size in bytes
	// atime   time of last access (Unix timestamp)
	// mtime   time of last modification (Unix timestamp)
	// ctime   time of last inode change (Unix timestamp)
	// blksize blocksize of filesystem IO *
	// blocks  number of blocks allocated
	//$r = ListDirectoryArr('./book/', '/^article[0-9]{4}\.txt$/i', 'ctime', 1); print_r($r);

    $r = array();
    $dh = @opendir($dir);
    if ($dh) {
        while (($fname = readdir($dh)) !== false) {
            if (preg_match($filter, $fname)) {
                $stat = stat("$dir/$fname"); //print_r($stat);
                $r[$fname] = ($SortBy == 'name')? $fname: $stat[$SortBy];
            }
        }
        closedir($dh);
        if ($desc) 	arsort($r);
        else  		asort($r);
    }
    return(array_keys($r));
}
//$r = ListDirectoryArr("C:/UwAmp/www/cfphp/CFMLtestPages/", '/\.cfm/i', 'ctime', 1); print_r($r);


// ###### INCLUDE ALL THE FUCTIONS ...

$cffunctions=ListDirectoryArr($GLOBALS["cf_webRootDir"]."/cfphpbin/cffunctions/",'/\.php/i', 'name', 1); //print_r($cffunctions);
foreach($cffunctions as $funcFile){
	
	$ffile = fopen($GLOBALS["cf_webRootDir"]."/cfphpbin/cffunctions/".$funcFile,"r");
	//echo "$funcFile<br>\n";
	if ($ffile) {
		while (($fline = fgets($ffile)) !== false) {
			//echo "[".stripos($fline,"function ")."]";
			if(is_numeric(stripos($fline,"function "))){
				// Watch out, stripos return 0 for the first offset.
				// These 0-based languages are idiot.
				$fline=str_ireplace("function ","",$fline);
				$words=explode("(",$fline);
				$FuncName=trim($words[0]);
				$GLOBALS["cf_FunctionNames"].=$FuncName.",";
				//echo "$FuncName<br>\n";
			}
		}
	}
	fclose($ffile);
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/cffunctions/".$funcFile;
}

$cfunctions=ListDirectoryArr($GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/functions/",'/\.php/i', 'name', 1); //print_r($cfunctions);
foreach($cfunctions as $funcFile){
	$ffile = fopen($GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/functions/".$funcFile,"r");
	//echo "$funcFile<br>\n";
	if ($ffile) {
		while (($fline = fgets($ffile)) !== false) {
			//echo "[".stripos($fline,"function ")."]";
			if(is_numeric(stripos($fline,"function "))){
				// Watch out, stripos return 0 for the first offset.
				// These 0-based languages are idiot.
				$fline=str_ireplace("function ","",$fline);
				$words=explode("(",$fline);
				$FuncName=trim($words[0]);
				$GLOBALS["cf_FunctionNames"].=$FuncName.",";
				//echo "$FuncName<br>\n";
			}
		}
	}
	fclose($ffile);
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/functions/".$funcFile;
}
$PHPfunctions="array";
$GLOBALS["cf_FunctionNames"].=$PHPfunctions;
//echo $GLOBALS["cf_FunctionNames"]."<br>\n";






?>