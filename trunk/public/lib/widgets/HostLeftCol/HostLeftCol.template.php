<?php 
$film = $vars["Host"]["column1"][0]["film"];
//$screenings = $vars["Host"]["col02"][0]["screenings"];
?> 

<!-- FILM INFO -->
<?php include_component('default', 
                        'Filminfo',
                        array('film'=>$film)
                        )?>
