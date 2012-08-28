<?php if (isset($films)) { echo(json_encode(array("screeningList"=>$films))); } else { ?>
        
<!-- SCREENING CAROUSEL -->
<?php include_component('default', 
                        'ShortCarousel',
												array('screenings'=>$carousel,'name'=>$film_alt_name))?>
<!-- SCREENING CAROUSEL -->
<?php } ?>
