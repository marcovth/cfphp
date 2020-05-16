<?php

//ListDirectory.php

function ListDirectory($qryName,$dir,$filter=null,$SortBy="name",$desc="ASC",$recurse=0){
	// Sorting by ... name,size,ctime,mtime,atime,created,modified,accessed,type,fullPath,RealPath
	
	$cf_qryName=RemoveSurroundingQuotes($qryName);
	$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);
	$cf_DB->exec("DROP TABLE IF EXISTS $cf_qryName");
	$cf_sql="CREATE TABLE $cf_qryName(name TEXT, size INT, ctime INT, mtime INT, atime INT, created TEXT, modified TEXT, accessed TEXT, type TEXT, fullPath TEXT, RealPath TEXT);";
	//echo "$cf_sql<br>\n";
	$cf_DB->exec($cf_sql);
	
	if(null===$filter) $filter="";

    $r = array(); $dh = @opendir($dir);
    if ($dh) {
        while (($fname = readdir($dh)) !== false) {
            $stat = FileStats("$dir/$fname"); //print_r($stat);
			$name=$stat["file"]["basename"];
			if(trim($name)!=="" and $name!=="." and $name!==".."){
				$IsDir=0; if($stat["filetype"]["is_dir"]==1) $IsDir=1;
				$IsFile=0; if($stat["filetype"]["is_file"]==1) $IsFile=1;
				$type=""; if($IsDir and !$IsFile) $type="Dir"; if(!$IsDir and $IsFile) $type="File";
				$fullPath=$stat["file"]["filename"];
				$RealPath=$stat["file"]["realpath"];
				$size=$stat["size"]["size"];
				//echo $stat["file"]["basename"]."|".$type."|".$fullPath."<br>\n";
				//$stat["filetype"]["is_file"]filename
				//[ctime][mtime][atime][created][modified][accessed]
				
				$sql="INSERT INTO $cf_qryName(name,size,ctime,mtime,atime,created,modified,accessed,type,fullPath,RealPath)";
				$sql.="values('$name',$size,".$stat["time"]["ctime"].",".$stat["time"]["mtime"].",".$stat["time"]["atime"].",'".$stat["time"]["created"]."','".$stat["time"]["modified"]."','".$stat["time"]["accessed"]."','$type','$fullPath','$RealPath');";
				//echo $sql."<br>\n";
				$cf_DB->exec($sql);
				
				
			}
        }
        closedir($dh);
		
		$rs="";
		$sql="SELECT * FROM $cf_qryName";
		if($stmt = $cf_DB->prepare($sql)){
			$rs = $stmt->execute();
			//echo "cfQueryOfQuery success";
			return $rs;
		}
		
		//return true;
    } //else return false;
    
}



function FileStats($file){
	//https://www.php.net/manual/en/function.stat.php 
	
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
	
	
    clearstatcache();
    $ss = @stat($file);
    if (!$ss) return false; //Couldnt stat file
    $ts = array(
        0140000 => 'ssocket',
        0120000 => 'llink',
        0100000 => '-file',
        0060000 => 'bblock',
        0040000 => 'ddir',
        0020000 => 'cchar',
        0010000 => 'pfifo'
    );

    $p = $ss['mode'];
    $t = decoct($ss['mode'] & 0170000); // File Encoding Bit
    $str = (array_key_exists(octdec($t) , $ts)) ? $ts[octdec($t) ]
    {
        0
    } : 'u';
    $str .= (($p & 0x0100) ? 'r' : '-') . (($p & 0x0080) ? 'w' : '-');
    $str .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x') : (($p & 0x0800) ? 'S' : '-'));
    $str .= (($p & 0x0020) ? 'r' : '-') . (($p & 0x0010) ? 'w' : '-');
    $str .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x') : (($p & 0x0400) ? 'S' : '-'));
    $str .= (($p & 0x0004) ? 'r' : '-') . (($p & 0x0002) ? 'w' : '-');
    $str .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x') : (($p & 0x0200) ? 'T' : '-'));

    $s = array(
        'perms' => array(
            'umask' => sprintf("%04o", @umask()) ,
            'human' => $str,
            'octal1' => sprintf("%o", ($ss['mode'] & 000777)) ,
            'octal2' => sprintf("0%o", 0777 & $p) ,
            'decimal' => sprintf("%04o", $p) ,
            'fileperms' => @fileperms($file) ,
            'mode1' => $p,
            'mode2' => $ss['mode']
        ) ,

        'owner' => array(
            'fileowner' => $ss['uid'],
            'filegroup' => $ss['gid'],
            'owner' => (function_exists('posix_getpwuid')) ? @posix_getpwuid($ss['uid']) : '',
            'group' => (function_exists('posix_getgrgid')) ? @posix_getgrgid($ss['gid']) : ''
        ) ,

        'file' => array(
            'filename' => $file,
            'realpath' => (@realpath($file) != $file) ? @realpath($file) : '',
            'dirname' => @dirname($file) ,
            'basename' => @basename($file)
        ) ,

        'filetype' => array(
            'type' => substr($ts[octdec($t) ], 1) ,
            'type_octal' => sprintf("%07o", octdec($t)) ,
            'is_file' => @is_file($file) ,
            'is_dir' => @is_dir($file) ,
            'is_link' => @is_link($file) ,
            'is_readable' => @is_readable($file) ,
            'is_writable' => @is_writable($file)
        ) ,

        'device' => array(
            'device' => $ss['dev'], //Device
            'device_number' => $ss['rdev'], //Device number, if device.
            'inode' => $ss['ino'], //File serial number
            'link_count' => $ss['nlink']//, //link count
            //'link_to' => ($s['type'] == 'link') ? @readlink($file) : ''
        ) ,

        'size' => array(
            'size' => $ss['size'], //Size of file, in bytes.
            'blocks' => $ss['blocks'], //Number 512-byte blocks allocated
            'block_size' => $ss['blksize'] //Optimal block size for I/O.
            
        ) ,

        'time' => array(
            'mtime' => $ss['mtime'], //Time of last modification
            'atime' => $ss['atime'], //Time of last access.
            'ctime' => $ss['ctime'], //Time of last status change
            'accessed' => 	@date("Y-m-d H:i:s", $ss['atime']) ,
            'modified' => 	@date("Y-m-d H:i:s", $ss['mtime']) ,
            'created' => 	@date("Y-m-d H:i:s", $ss['ctime'])
        ) ,
    );
			/*
			d - The day of the month (from 01 to 31)
			D - A textual representation of a day (three letters)
			j - The day of the month without leading zeros (1 to 31)
			l (lowercase 'L') - A full textual representation of a day
			N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)
			S - The English ordinal suffix for the day of the month (2 characters st, nd, rd or th. Works well with j)
			w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
			z - The day of the year (from 0 through 365)
			W - The ISO-8601 week number of year (weeks starting on Monday)
			F - A full textual representation of a month (January through December)
			m - A numeric representation of a month (from 01 to 12)
			M - A short textual representation of a month (three letters)
			n - A numeric representation of a month, without leading zeros (1 to 12)
			t - The number of days in the given month
			L - Whether it's a leap year (1 if it is a leap year, 0 otherwise)
			o - The ISO-8601 year number
			Y - A four digit representation of a year
			y - A two digit representation of a year
			a - Lowercase am or pm
			A - Uppercase AM or PM
			B - Swatch Internet time (000 to 999)
			g - 12-hour format of an hour (1 to 12)
			G - 24-hour format of an hour (0 to 23)
			h - 12-hour format of an hour (01 to 12)
			H - 24-hour format of an hour (00 to 23)
			i - Minutes with leading zeros (00 to 59)
			s - Seconds, with leading zeros (00 to 59)
			u - Microseconds (added in PHP 5.2.2)
			e - The timezone identifier (Examples: UTC, GMT, Atlantic/Azores)
			I (capital i) - Whether the date is in daylights savings time (1 if Daylight Savings Time, 0 otherwise)
			O - Difference to Greenwich time (GMT) in hours (Example: +0100)
			P - Difference to Greenwich time (GMT) in hours:minutes (added in PHP 5.1.3)
			T - Timezone abbreviations (Examples: EST, MDT)
			Z - Timezone offset in seconds. The offset for timezones west of UTC is negative (-43200 to 50400)
			c - The ISO-8601 date (e.g. 2013-05-05T16:34:42+00:00)
			r - The RFC 2822 formatted date (e.g. Fri, 12 Apr 2013 12:01:05 +0200)
			U - The seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
			*/

    clearstatcache();
    return $s;
}

/* Array(
[perms] => Array
  (
  [umask] => 0022
  [human] => -rw-r--r--
  [octal1] => 644
  [octal2] => 0644
  [decimal] => 100644
  [fileperms] => 33188
  [mode1] => 33188
  [mode2] => 33188
  )

[filetype] => Array
  (
  [type] => file
  [type_octal] => 0100000
  [is_file] => 1
  [is_dir] =>
  [is_link] =>
  [is_readable] => 1
  [is_writable] => 1
  )

[owner] => Array
  (
  [fileowner] => 035483
  [filegroup] => 23472
  [owner_name] => askapache
  [group_name] => grp22558
  )

[file] => Array
  (
  [filename] => /home/askapache/askapache-stat/htdocs/ok/g.php
  [realpath] =>
  [dirname] => /home/askapache/askapache-stat/htdocs/ok
  [basename] => g.php
  )

[device] => Array
  (
  [device] => 25
  [device_number] => 0
  [inode] => 92455020
  [link_count] => 1
  [link_to] =>
  )

[size] => Array
  (
  [size] => 2652
  [blocks] => 8
  [block_size] => 8192
  )

[time] => Array
  (
  [mtime] => 1227685253
  [atime] => 1227685138
  [ctime] => 1227685253
  [accessed] => 2008 Nov Tue 23:38:58
  [modified] => 2008 Nov Tue 23:40:53
  [created] => 2008 Nov Tue 23:40:53
  )
) */

?>