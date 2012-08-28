<html>
<link href="sdk.css" rel="stylesheet" type="text/css"/>
  <table class="api" width=400>
	        <tr>
      <td colspan="2">Crankey</td>
    </tr>
	   	<?php 
    		foreach($resArray as $key => $value) {
    			
    			echo "<tr><td> $key:</td><td>$value</td>";
    			}	
       			?>
  </table>
</html>
