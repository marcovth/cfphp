<?php for( $m=1; $m<=$nPoints; $m=$m+1; ){//CFLOOP ?>
	<?php $NextLong = getNextLong($plat,$plong,$azimuth,($m*50));//cfset ?>
	<?php $Nextlat = getNextLat($plat,$plong,$azimuth,($m*50));//cfset ?>
	<li> *** [ <?php echo $Nextlat; ?> | <?php echo $NextLong; ?> | <?php echo $azimuth; ?> ]
	<?php $n = DownloadImg($Nextlat,$NextLong,$azimuth,$n);//cfset ?>
[/CFLOOP]



<?php $n = $n+1;//cfset ?> This is HTML code after and inbetween a tag with a <?php echo $variable; ?> <?php $m = $m+10;//cfset ?> Some more HTML <?php echo $code; ?> ...

<?php $azimuth = Mid($line,Find("azimuth",$line),20);//cfset ?>
<?php $azimuth = ListgetAt($azimuth,2,">");//cfset ?>
<?php $azimuth = ListFirst($azimuth,"<");//cfset ?>
    
    
test test <!--- commented out --->more test.    
    
