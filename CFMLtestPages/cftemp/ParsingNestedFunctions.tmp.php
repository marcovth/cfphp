<?php $string = "hELLO";//cfset ?>
<?php $test = ArrayNew("1,4,55,'zz'");//cfset ?><?php echo $test[4]; ?>
<?php arrayInsertAt($test,2,6);//cfset ?><?php echo $test[2]; ?>