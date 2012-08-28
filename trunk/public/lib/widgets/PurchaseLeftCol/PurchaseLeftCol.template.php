<?php 
$film = $vars["Purchase"]["column1"][0]["film"];
?>
<h2><?php echo $film["screening_film_name"];?></h2>
<div class="movie_poster">
<img class="poster" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/poster<?php echo $film["screening_film_logo"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still"  border="0"/>	</div>
<span class="arrow"><a href="">Play Trailer</a></span>

<p><strong>About the Film:</strong>
<?php echo $film["screening_film_info"];?></p>

<?php if (count($film["screening_film_directors"])) {?>
<p><strong>Director(s):</strong>
<?php foreach ($film["screening_film_directors"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>
<?php }?>

<?php if (count($film["screening_film_executive_producers"])) {?>          
<p><strong>Executive Producer(s):</strong>
  <?php foreach ($film["screening_film_executive_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["screening_film_co-executive_producers"])) {?>          
<p><strong>Co-Executive Producer(s): </strong>
<?php foreach ($film["screening_film_co-executive_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["screening_film_producers"])) {?>          
<p><strong>Producer(s):</strong>
<?php foreach ($film["screening_film_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>
                  
<?php if (count($film["screening_film_co-producers"])) {?>          
<p><strong>Co-Producer(s):</strong>
<?php foreach ($film["screening_film_co-producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["screening_film_associate_producers"])) {?>          
<p><strong>Associate Producer(s):</strong>
<?php foreach ($film["screening_film_associate_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>

<?php if (count($film["screening_film_writers"])) {?>          
<p><strong>Writer(s):</strong>
<?php foreach ($film["screening_film_writers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>
     
<?php if (count($film["screening_film_director_of_photography"])) {?>          
<p><strong>Director(s) of Photography:</strong>
<?php foreach ($film["screening_film_director_of_photography"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["screening_film_actors"])) {?>          
<p><strong>Actor(s):</strong>
<?php foreach ($film["screening_film_actors"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["screening_film_music"])) {?>          
<p><strong>Music:</strong>
<?php foreach ($film["film_music"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["screening_film_supported"])) {?>          
<p><strong>Supporting Cast and Crew:</strong>
<?php foreach ($film["screening_film_supported"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?> 

<p><strong>Production Company:</strong>
<?php echo $film["screening_film_production_company"];?>
</p>	
