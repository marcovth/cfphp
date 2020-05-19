
<?php $TestStruct=StructNew("TestStruct");//cfset ?>
<?php $TestStruct['name'] = "Apple corp";//cfset ?>
    
<?php $TestStruct2=StructNew("TestStruct2");//cfset ?>
<?php $TestStruct2['name']['last']['offical'] = "Google corp";//cfset ?>

<li><?php echo $TestStruct2.name.last.offical; ?></li>