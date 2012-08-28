<?php //dump($film);?>

<img src="/uploads/screeningResources/<?php echo $film["film_id"];?>/background/<?php echo $film["film_splash_image"];?>" />

<div class="hero-aside">
	<div class="hero-aside-details">
		<h1 class="uppercase"><?php echo $film["film_name"] ?></h1>
		<p>Runtime: <?php echo $film['film_running_time'] ?></p>
		<?php if(count($film["film_directors"]) >0):?>
		<p>Director: <?php $d = explode('|',$film["film_directors"][0]); echo $d[1] ?></p>
		<?php endif;?>
		<?php if(count($film["film_genre"]) >0):?>
		<p>Genres: <?php echo implode( ', ', $film["film_genre"]) ?></p>
		<?php endif;?>
	</div>
	<ul class="hero-aside-links">
		<li class="synopsis-click"><span class="hero-synopsis"></span> Synopsis</li>
		<?php if (!empty($film['film_review'])):?>
		<li class="review-click"><span class="hero-review"></span>Reviews</li>
		<?php endif;?>
		<?php if (!empty($film['film_trailer_file'])):?>
		<li class="trailer-click"><span class="hero-trailer"></span> Watch Trailer</li>
		<?php endif;?>
	</ul>
</div> 
<?php if(!empty($film['film_facebook']) || !empty($film['film_twitter']) || !empty($film['film_website'])):?>
<ul class="hero-social">
<?php if(!empty($film['film_facebook'])):?>
	<li class="hero-social-facebook"><a href="<?php echo $film['film_facebook']?>" target="_blank"></a></li>
<?php endif;?>
<?php if(!empty($film['film_twitter'])):?>
	<li class="hero-social-twitter" ><a href="<?php echo $film['film_twitter']?>" target="_blank"></a></li>
<?php endif;?>
<?php if(!empty($film['film_website'])):
	$http = array('http://', 'www.');
?>
	<li class="hero-social-web"><a href="<?php echo $film['film_website']?>" target="_blank"><?php echo str_replace($http, "", $film['film_website']);?></a></li>
<?php endif;?>
</ul>
	<!-- <div id="fb-root"></div>
    <script src="https://connect.facebook.net/en_US/all.js#appId=135843816505294&amp;xfbml=1"></script>
    	<fb:like href="https://<?php echo sfConfig::get("app_domain");?>/film/<?php echo $film["film_id"].$fbeacon;?>" send="false" layout="button_count" width="100" show_faces="false" font="arial"></fb:like>
		<a  href="https://twitter.com/share" class="twitter-share-button" 
        data-url="https://<?php echo sfConfig::get("app_domain");?>/film/<?php echo $film["film_id"].$tbeacon;?>" 
        data-counturl="https://<?php echo sfConfig::get("app_domain");?>/film/<?php echo $film["film_id"];?>" 
        data-text="Constellation presents <?php echo $film["film_name"];?> - <?php echo $film["film_synopsis"];?>" 
        data-related="constellation.tv:Constellation.tv"
        data-count="horizontal"></a> -->
<?php endif;?>
<script type="text/javascript">
var Film = <?php echo json_encode($film) ;?>;
$(document).ready(function() {

	new CTV.Trailer({},'.trailer-click')
	new CTV.Synopsis();
	new CTV.Film.Review({}, '.review-click');

});
</script>