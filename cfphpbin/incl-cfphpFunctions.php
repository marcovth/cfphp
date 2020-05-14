<?php

function FileExists($filePath){
	return file_exists($filePath);
}

function ListDirectory($dir, $filter, $SortBy='name', $desc=0){
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
	//$r = ListDirectory('./book/', '/^article[0-9]{4}\.txt$/i', 'ctime', 1); print_r($r);

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
//$r = ListDirectory("C:/UwAmp/www/cfphp/CFMLtestPages/", '/\.cfm/i', 'ctime', 1); print_r($r);


// ###### INCLUDE ALL THE FUCTIONS ...

$cffunctions=ListDirectory($GLOBALS["cf_webRootDir"]."/cfphpbin/cffunctions/",'/\.php/i', 'name', 1); //print_r($cffunctions);
foreach($cffunctions as $TagFile){
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/cffunctions/".$TagFile;
}

$cfunctions=ListDirectory($GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/functions/",'/\.php/i', 'name', 1); //print_r($cfunctions);
foreach($cfunctions as $TagFile){
	include $GLOBALS["cf_webRootDir"]."/cfphpbin/CustomTags/functions/".$TagFile;
}







?>