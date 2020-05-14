<?php


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
		$rs="";
		if($stmt = $cf_DB->prepare(trim($sql))){
			$rs = $stmt->execute();
			//echo "cfQueryOfQuery success";
			return $rs;
		} else {
			echo "cfQueryOfQuery failed";
			return false;
		}
	} else return false;
}

function ParseCFquery($AttributeLine,$InnerHTML,&$output){
	//echo "ParseCFqueryF";
	$InnerHTML = preg_replace('/\s+/', ' ', trim($InnerHTML));
	
	//<cfquery name="sortedNews" dbtype="query">
	$AttributeArr=ParseAttributeLine($AttributeLine." x");
	//cfdump($AttributeArr); echo "(".ArrayLen($AttributeArr['AttributeName']).")";
	$out="";
	$cfquery_name=""; $cfquery_dbtype=""; $cfquery_datasource="";
	for ($nAtt=0; $nAtt<ArrayLen($AttributeArr['AttributeName']); $nAtt++){ //echo "[".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]."]<br>\n";
		if($AttributeArr['AttributeName'][$nAtt] !== ""){
			     if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "NAME") 	$cfquery_name=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "DBTYPE") 	$cfquery_dbtype=trim($AttributeArr['AttributeVal'][$nAtt]);
			else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "DATASOURCE") $cfquery_datasource=trim($AttributeArr['AttributeVal'][$nAtt]);
			//else if(UCASE(trim($AttributeArr['AttributeName'][$nAtt]))=== "STEP") $cfloop_step=trim($AttributeArr['AttributeVal'][$nAtt]);
			else ; //$out.=" ".$AttributeArr['AttributeName'][$nAtt]."=".$AttributeArr['AttributeVal'][$nAtt]." ";
		}
	}
	//echo "name[$cfquery_name] dbtype[$cfquery_dbtype]<br>\n";
	
	if($cfquery_name!=="" and $cfquery_dbtype!=="" and UCASE($cfquery_dbtype)==="QUERY"){ //
		//echo "dbtype=QUERY<br>\n";
		//echo "$InnerHTML<br>\n";
		$SQL=ParseSQL($InnerHTML);
		//echo "$SQL<br>\n";
		$out.="<?php \$SQL=\"\"; ?>".$SQL."\n";
		$out.="<?php \$".$cfquery_name." = cfQueryOfQuery(\$SQL); //CFQUERY ?>\n";
	}
	
	if($cfquery_name!=="" and $cfquery_datasource!==""){ //
		$SQL=ParseSQL($InnerHTML);
		$out.="<?php \$SQL=\"\"; ?>".$SQL."\n";
		//echo "$cfquery_datasource<br>\n";
		$out.="<?php echo \"datasource not implemented yet\"; \n";
		//$out.=" \$".$cfquery_name." = cfQueryDB(\$SQL);";
		$out.=" //CFQUERY ?>\n";
	}
	
	$output.=$out;
	
	
	
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