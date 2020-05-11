<?php

//$cf_sqliteversion = SQLite3::version();
//echo $cf_sqliteversion['versionString'] . "\n";
//echo $cf_sqliteversion['versionNumber'] . "\n";
//var_dump($cf_sqliteversion);

// <cfset news = queryNew("id,title", "integer,varchar")>
// <cfset queryAddRow(news)>
// <cfset querySetCell(news, "id", "1")>
// <cfset querySetCell(news, "title", "Dewey defeats Truman")>
// <cfset queryAddRow(news)>
// <cfset querySetCell(news, "id", "2")>
// <cfset querySetCell(news, "title", "Men walk on Moon")>
// <cfset writeDump(news)>

// <!--- run QofQ (query of query) --->
// <cfquery name="sortedNews" dbtype="query">
    // SELECT id, title FROM news
    // ORDER BY title DESC
// </cfquery>

function queryAddRow($qryName){
	$cf_qryName=trim(RemoveSurroundingQuotes($qryName));
	$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);
	$cf_DB->exec("INSERT INTO $cf_qryName DEFAULT VALUES");
}

function queryNew($qryName,$columns,$type=null){
	//echo "queryNew($qryName,$columns,$type)<br>\n";
	
	$cf_qryName=RemoveSurroundingQuotes($qryName);
	$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);

	//$cf_DBtest = $cf_DB->querySingle('SELECT SQLITE_VERSION()');
	//echo $cf_DBtest . "\n";
	$cf_DB->exec("DROP TABLE IF EXISTS $cf_qryName");
	$cf_sql="CREATE TABLE $cf_qryName(cfid INTEGER PRIMARY KEY";
	$columns=explode(",",$columns);
	$type=explode(",",$type);
	$n=0;foreach($columns as &$column){
		$cf_type="TEXT";
		if(array_key_exists($n,$type)){
				 if(UCASE(RemoveSurroundingQuotes($type[$n]))==="INTEGER") 	$cf_type="INT";
			else if(UCASE(RemoveSurroundingQuotes($type[$n]))==="FLOAT") 	$cf_type="REAL";
		}
		$cf_sql.=", ".RemoveSurroundingQuotes($column)." $cf_type";
		$n++;
	}
	$cf_sql.=")";
	//echo "$cf_sql<br>\n";
	$cf_DB->exec($cf_sql);
}

function querySetCell($qryName,$column,$value){
	$qryName=trim(RemoveAllQuotes($qryName));
	$column=trim(RemoveAllQuotes($column));
	$value=trim(RemoveAllQuotes($value));
	//echo "[$qryName][$column][$value]<br>\n";
	$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);
	$sql=""; $type="TEXT"; $res = $cf_DB->query("PRAGMA table_info($qryName)");
	while($row=$res->fetchArray(SQLITE3_NUM)) { if($row[1]===$column) $type=$row[2]; } 
	if($type==="INT" or $type==="REAL") $sql="UPDATE $qryName SET $column=$value WHERE cfid=(SELECT MAX(cfid) FROM $qryName);";
	else $sql="UPDATE $qryName SET $column=\"$value\" WHERE cfid=(SELECT MAX(cfid) FROM $qryName);";
	//echo "$sql<br>\n";
	$cf_DB->exec($sql);
}

function cfQueryOfQuery($sql){
	if(trim($sql)!==""){
		$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);
		$sql2 = $cf_DB->prepare(".trim($sql).");
		$rs = $sql2->execute();
		//$rs = $cf_DB->query(".$sql2.");
		return $rs;
	} else return false;
}

function ParseCFquery($AttributeLine,$InnerHTML,&$output){
	//echo "ParseCFqueryF";
	$InnerHTML2 = preg_replace('/\s+/', ' ', trim($InnerHTML));
	$InnerHTML3=ParseNestedTags($InnerHTML2);
	//$output.="[CFQUERY $AttributeLine]$InnerHTML3";
	
	//<cfquery name="sortedNews" dbtype="query">
	$AttributeArr=ParseAttributeLine($AttributeLine." x");
	//cfdump($AttributeArr); echo "(".ArrayLen($AttributeArr['AttributeName']).")";
	$out="<?php ";
	$cfquery_name=""; $cfquery_dbtype=""; //$cfloop_index=""; $cfloop_step="";
	for ($nAtt=0; $nAtt<ArrayLen($AttributeArr['AttributeName']); $nAtt++){ //echo "[".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]."]<br>\n";
		if($AttributeArr['AttributeName'][$nAtt] !== ""){
			     if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "NAME") 	$cfquery_name=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "DBTYPE") 	$cfquery_dbtype=trim($AttributeArr['AttributeVal'][$nAtt]);
			//else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "INDEX") $cfloop_index=trim($AttributeArr['AttributeVal'][$nAtt]);
			//else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "STEP") $cfloop_step=trim($AttributeArr['AttributeVal'][$nAtt]);
			else ; //$out.=" ".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]." ";
		}
	}
	//echo "name[$cfquery_name] dbtype[$cfquery_dbtype]<br>\n";
	
	if($cfquery_name!=="" and $cfquery_dbtype!=="" and UCASE($cfquery_dbtype)==="QUERY"){ //
		//echo "dbtype=QUERY<br>\n";
		$out.=" \$".$cfquery_name." = cfQueryOfQuery(\"".$InnerHTML3."\"); //CFQUERY ?>\n";
		
		
		//$out.="\t \$cf_DB = new SQLite3(\$GLOBALS[\"cf_DBfilePath\"]);\n";
		//$out.="\t \$".$cfquery_name." = \$db->query(\"".$InnerHTML3."\");\n";
		//$out.="//CFQUERY ?]\n";
		
		//$cf_from=FindNoCase(" FROM ",$InnerHTML);
		//if(!$cf_from==0){
		//	$cf_from=MID($InnerHTML,$cf_from+6,100);
		//	$cf_from=trim(ListFirst(trim($cf_from)," "));
		//	//echo "FROM[$cf_from]<br>\n";
		//	if($cf_from==="") $out.=" Error: query-FROM not found "; 
		//	else {
		//		$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);
		//		$cf_DB->exec($InnerHTML);
		//		$res = $db->query($InnerHTML);
		//	}
		//	//$out.=" $cf_from ";
		//} else $out.=" Error: query-FROM not found ";
		//$out.="for( $index=".DetectVariables($cfloop_from,"NO")."; $index$cf_direction".DetectVariables($cfloop_to,"NO")."; ".DetectVariables($cfloop_index,"NO")."=".DetectVariables($cfloop_index,"NO")."+$cfloop_step ){";
		//$out.="//CFQUERY ?]";
	}
	$output.=$out;
	
	
	//$query_famRemarks = "SELECT * FROM famRemarks WHERE hide=0 AND persID =".$row_person['persID'];
	//echo $query_famRemarks;
	//$famRemarks = mysql_query($query_famRemarks, $stamboom) or die("qryfamRemarks: ".mysql_error()."<hr>".$query_famRemarks);
	//$row_famRemarks = mysql_fetch_assoc($famRemarks);
	//$totalRows_famRemarks = mysql_num_rows($famRemarks);
	//$famID=$row_famRemarks['famID'][0];
	
	

}

function QueryOfQuery($rs, // The recordset to query
	$fields = "*", // optional comma-separated list of fields to return, or * for all fields
	$distinct = false, // optional true for distinct records
	$fieldToMatch = null, // optional database field name to match
	$valueToMatch = null) { // optional value to match in the field, as a comma-separated list

	// ### courtesy of http://www.tom-muck.com/blog/index.cfm?newsid=37 

	$newRs = Array();
	$row = Array();
	$valueToMatch = explode(",",$valueToMatch);
	$matched = true;
	mysql_data_seek($rs, 0);
	if($rs) {
		while ($row_rs = mysql_fetch_assoc($rs)){
			if($fields == "*") {
				if($fieldToMatch != null) {
					$matched = false;
					if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))){
						$matched = true;
					}
				}
				if($matched) $row = $row_rs;
				}else{
					$fieldsArray=explode(",",$fields);
					foreach($fields as $field) {
						if($fieldToMatch != null) {
							$matched = false;
						if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))){
							$matched = true;
						}
					}
					if($matched) $row[$field] = $row_rs[$field];
				}
			}
			if($matched)array_push($newRs, $row);
		};
		if($distinct) {
			sort($newRs);
			for($i = count($newRs)-1; $i > 0; $i--) {
				if($newRs[$i] == $newRs[$i-1]) unset($newRs[$i]);
			}
		}
	}
	mysql_data_seek($rs, 0);
	return $newRs;
}


?>