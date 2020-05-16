<?php $myList=ListDirectory("myList",evaluate("expandPath(\"../\")"),"","name","ASC",0); //CFDIRECTORY ?>

<?php  $nrow=0; while($row = $myList->fetchArray()) { $CurrentRow=$nrow+1;for($ncol=0; $ncol< $myList->numColumns(); $ncol++) { ${$myList->columnName($ncol)}=$row[$ncol]; } $nrow++; $myList_Recordcount=$nrow; $Recordcount=$nrow; //CFOUTPUT ?>
    <li><?php echo $name; ?> | <?php echo $size; ?> | <?php echo $type; ?> | </li>
<?php }//cfoutput ?>
<li><?php echo $myList_Recordcount; ?></li>

<?php $SQL=""; ?><?php $SQL.="SELECT * FROM myList WHERE type='Dir'"; ?>

<?php $myList2 = cfQueryOfQuery($SQL); //CFQUERY ?>

<hr>

<?php  $nrow=0; while($row = $myList2->fetchArray()) { $CurrentRow=$nrow+1;for($ncol=0; $ncol< $myList2->numColumns(); $ncol++) { ${$myList2->columnName($ncol)}=$row[$ncol]; } $nrow++; $myList2_Recordcount=$nrow; $Recordcount=$nrow; //CFOUTPUT ?>
    <li><?php echo $name; ?> | <?php echo $size; ?> | <?php echo $type; ?> | </li>
<?php }//cfoutput ?>