<?php queryNew("news","id,title", "integer,varchar");//cfset ?>
<?php queryAddRow("news");//cfset ?><?php querySetCell("news","id","1");//cfset ?><?php querySetCell("news","title","Dewey defeats Truman");//cfset ?>
<?php queryAddRow("news");//cfset ?><?php querySetCell("news","id","2");//cfset ?><?php querySetCell("news","title","Men walk on Moon");//cfset ?>

<?php $SQL=""; ?><?php $SQL.="SELECT id, title FROM news ORDER BY title DESC"; ?>

<?php $sortedNews = cfQueryOfQuery($SQL); //CFQUERY ?>


<?php  $nrow=0; while($row = $sortedNews->fetchArray()) { $CurrentRow=$nrow+1;for($ncol=0; $ncol< $sortedNews->numColumns(); $ncol++) { ${$sortedNews->columnName($ncol)}=$row[$ncol]; } $nrow++; $sortedNews_Recordcount=$nrow; $Recordcount=$nrow; //CFOUTPUT ?>
    <li><?php echo $id; ?> [<?php echo $title; ?>] CurrentRow=<?php echo $CurrentRow; ?></li>
<?php }//cfoutput ?>
<p>
RecordCount= <?php echo $Recordcount; ?><br>
RecordCount= <?php echo "$sortedNews_Recordcount <br>"; ?>