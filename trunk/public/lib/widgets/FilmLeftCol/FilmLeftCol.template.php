<?php
$program = $vars["FilmView"]["column1"][0]["program"]; 
$film = $vars["FilmView"]["column1"][0]["film"];
?>

<!-- FILM INFO -->
<?php include_component('default', 
                        'Filminfo',
                        array('film'=>$film,
                        'program'=>$program)
                        )?>
