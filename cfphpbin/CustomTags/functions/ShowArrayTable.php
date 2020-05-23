<?php

function ShowArrayTable($ar){

   echo "<table width='100%' border='1' bordercolor='#6699CC' cellspacing='0' cellpadding='5'><tr valign='top'>";

      foreach ($ar as $k => $v ) {

         echo "<td align='center' bgcolor='#EEEEEE'>
           <table border='2' cellpadding='3'><tr><td bgcolor='#FFFFFF'><font face='verdana' size='1'>
              $k=$v
           </font></td></tr></table>";

           if (is_array($ar[$k])) {
              ShowArrayTable($ar[$k]);
         }

         echo "</td>";

      }

   echo "</tr></table>";

}
?>