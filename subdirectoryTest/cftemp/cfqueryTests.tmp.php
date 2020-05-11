<?php queryNew("news","id,title", "integer,varchar");//cfset ?>
<?php queryAddRow("news");//cfset ?>
<?php querySetCell("news","id","1");//cfset ?>
<?php querySetCell("news","title","Dewey defeats Truman");//cfset ?>
<?php queryAddRow("news");//cfset ?>
<?php querySetCell("news","id","2");//cfset ?>
<?php querySetCell("news","title","Men walk on Moon");//cfset ?>


<?php queryNew("news2","id,title");//cfset ?>