<?php ;//cfset ?><?php echo $test[4]; ?>
<?php ;//cfset ?><?php echo $test[2]; ?>

<?php //CFSCRIPT 
$numbers = array (
  0 => 27, 1 => 24, 2 => 84, 3 => 43, 4 => 8, 5 => 51, 6 => 60, 7 => 86, 8 => 9, 9 => 48, 10 => 67, 11 => 20, 12 => 44, 13 => 85, 14 => 6, 15 => 63,
  16 => 41, 17 => 32, 18 => 64, 19 => 73, 20 => 43, 21 => 24, 22 => 15, 23 => 19, 24 => 9, 25 => 93, 26 => 88, 27 => 77, 28 => 11, 29 => 54,
);
//cfscript ?> 

<li>ArrayMedian=<?php echo ArrayMedian($numbers); ?></li><?php echo "<!--- expected 43.5 --->"; ?>
<li>ArraySum=<?php echo ArraySum($numbers); ?></li>
<li>ArrayMin=<?php echo ArrayMin($numbers); ?></li>
<li>ArrayMax=<?php echo ArrayMax($numbers); ?></li>
<li>ArrayAvg=<?php echo ArrayAvg($numbers); ?></li>
<li>ArraySum=<?php echo ArraySum($numbers); ?></li>

<?php echo ShowArrayTable($numbers); ?>