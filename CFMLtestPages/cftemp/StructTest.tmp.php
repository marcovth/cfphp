<?php $student=StructNew("student");//cfset ?>
<?php $student[''] =  {firstName="Jane", lastName="Janes", grades=[91, 78, 87]} ;//cfset ?>
    
<?php $TestStruct2=StructNew("TestStruct2");//cfset ?>
<?php $TestStruct2['name']['last']['offical'] = "Google corp";//cfset ?>

<li>Name = <?php echo $TestStruct2['name']['last']['offical']; ?></li>
<li><?php echo $TestStruct['name']; ?></li>