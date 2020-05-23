<?php $Struct2D=StructNew("Struct2D");//cfset ?>
<?php $Struct2D['KeyA']['innerKeyA1'] = "innerValueA1";//cfset ?>
<?php $Struct2D['KeyA']['KeyC']['innerKeyC1'] = "innerValueC1";//cfset ?>
<?php $Struct2D['KeyB']['innerKeyB1'] = "innerValueB1";//cfset ?>
<?php $Struct2D['KeyB']['innerKeyB2'] = "innerValueB2";//cfset ?>


<?php $student=StructNew("student");//cfset ?>
<?php $student['firstName'] = "Jane";//cfset ?>
<?php $student['lastName'] = "Smith";//cfset ?>
<?php $student['grades'] = array(91,78,87,"A");//cfset ?>
<?php $student['city'] = "New York";//cfset ?>
<?php $student['housenumber'] = 8;//cfset ?>


<?php echo $student['housenumber']; ?>    

<?php $myStruct=StructNew("myStruct");//cfset ?>
<?php $myStruct['first'] = "I am number one";//cfset ?>
<?php $myStruct['second'] = "I am number two";//cfset ?>
 <?php echo "<!--- // Don't use arrays (like the grades above) in combination with square-brackets used here !!! --->"; ?>

<?php $TestStruct2=StructNew("TestStruct2");//cfset ?>
<?php $TestStruct2['name']['last']['offical'] = "Google corp";//cfset ?>

<li>Name = <?php echo $TestStruct2['name']['last']['offical']; ?></li>