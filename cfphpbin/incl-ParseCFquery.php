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
	echo "[$qryName][$column][$value]<br>\n";
	$cf_DB = new SQLite3($GLOBALS["cf_DBfilePath"]);
	$cf_DB->exec("UPDATE $qryName SET ($column) VALUES(\"$value\") WHERE cfid=(SELECT MAX(cfid) FROM $qryName)");
//<cfset querySetCell(news, "id", "1")>
//<cfset querySetCell(news, "title", "Dewey defeats Truman")>
//$cf_DB->exec("INSERT INTO cars(name, price) VALUES('Audi', 52642)");
}

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