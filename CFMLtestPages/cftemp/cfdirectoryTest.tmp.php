[CFDIRECTORY action="list" directory="#expandPath("./")#" recurse="false" name="myList"]

<?php 
    <li><?php echo $name; ?></li>
<?php }//cfoutput ?>