<?php $Struct2D=StructNew("Struct2D");//cfset ?>
<?php $Struct2D['innerKey1'] = "innerValue1";//cfset ?>
 Working on this.


<?php $student=StructNew("student");//cfset ?>
<?php $student['firstName'] = "Jane";//cfset ?>
<?php $student['lastName'] = "Smith";//cfset ?>
<?php $student['grades'] = array(91,78,87,"A");//cfset ?>
<?php $student['city'] = "Montreal";//cfset ?>
<?php $student['housenumber'] = 8;//cfset ?>


<?php echo $student['housenumber']; ?>    
    
<?php $TestStruct2=StructNew("TestStruct2");//cfset ?>
<?php $TestStruct2['name']['last']['offical'] = "Google corp";//cfset ?>

<li>Name = <?php echo $TestStruct2['name']['last']['offical']; ?></li>