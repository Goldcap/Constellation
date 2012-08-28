
<div class="bo-panel active clear">
<form method="post" action="/services/Account"> 

	<div class="form-fieldset">
		<div class="form-row clearfix">
			<label for="film_name ">Film Name</label>
			<div class="input">
				<input class="text-input span6 post-data" data-validators="notEmpty" data-name="Film Name" id="film_name" name="film_name" value="<?php echo $post["film_name"];?>"  />
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_name ">Film Short Name</label>
			<div class="input">
				<input class="text-input span6 post-data" id="film_name" data-validators="notEmpty" data-name="Film Short Name" name="film_name" value="<?php echo $post["film_short_name"];?>"  />
				<p class="sublabel">(used for the url)
			</div>
		</div>
		<div class="form-row hr clearfix"></div>
		<div class="form-row clearfix">
			<label for="film_name ">Runtime</label>
			<div class="input">
				<input class="text-input span2 post-data" id="film_name" name="film_running_time" data-validators="notEmpty" data-name="Runtime" value="<?php echo $post["film_running_time"];?>"  />
			</div>
		</div>
		<div class="form-row hr clearfix"></div>
		<div class="form-row clearfix">
			<label for="film_info ">Short Description</label>
			<div class="input">
				<textarea  height="100" class="text-input span6 post-data" id="film_info" data-validators="notEmpty" data-name="Short Description" name="film_info"><?php echo $post["film_info"];?></textarea>
				<p class="sublabel">(optional short description, 250 characters max - used for tooltips, and when shared to other sites)
				</p>
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_synopsis ">Synopsis</label>
			<div class="input">
				<textarea  style="height:100px" class="text-input span6 post-data" id="film_synopsis" data-validators="notEmpty" data-name="Synopsis" name="film_synopsis"><?php echo $post["film_synopsis"];?></textarea>
			</div>
		</div>
		<div class="form-row hr clearfix"></div>
		<div class="form-row clearfix">
			<label for="expiration_date_month">Genre</label>
			<div class="input">
				<ul class="input-list">
					<?php $i = 0;foreach($genre as $gen) {?>
					<li>
						<input type="checkbox" value="<?php echo $gen -> getGenreId();?>" class="" name="film_genre[]" id="film_genre_<?php echo $i;?>" <?php if (!empty($post["film_genre"]) && in_array($gen -> getGenreName(),$post["film_genre"])) { echo 'checked="checked"'; } ?>>
						<label for="film_genre_<?php echo $i;?>"><?php echo $gen -> getGenreName();?></label>
					</li>
					<?php $i++; } ?>
				</ul>
			</div>
		</div>
		<div class="form-row hr clearfix"></div>

		<div class="form-row clearfix">
			<label for="film_directors">Directors</label>
				<?php if (count($post["film_directors"]) > 0) {
				foreach($post["film_directors"] as $item) {
				$name = explode("|",$item);
				?>
				<div class="input">
					<input type="text" class="text-input span4" id="film_directors" name="film_directors[]" value="<?php echo end($name);?>"/>
				</div>
				<?php }} else { ?>
				<div class="input">
					<input type="text" class="text-input span4" id="film_directors" name="film_directors[]"/>
				</div>
				<?php } ?>	
				<div id="director-template-container" class="input"></div>
				<div class="input" style="margin-top: 6px;">
					<span class="button button_green button-medium" id="add-director">Add Another</span>
				</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_producers">Producers</label>
			<?php if (count($post["film_producers"]) > 0) {
			foreach($post["film_producers"] as $item) {
			$name = explode("|",$item);
			?>
			<div class="input">
				<input type="text" class="text-input span4" id="film_producers" name="film_producers[]" value="<?php echo end($name);?>"/>
			</div>
			<?php }} else { ?>
			<div class="input">
				<input type="text" class="text-input span4" id="film_producers" name="film_producers[]"/>
			</div>
			<?php } ?>			
			<div id="producer-template-container" class="input"></div>
			<div class="input" style="margin-top: 6px;">
				<span class="button button_green button-medium" id="add-producer">Add Another</span>
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_actors ">Actors</label>
			<?php if (count($post["film_actors"]) > 0) {
			foreach($post["film_actors"] as $item) {
			$name = explode("|",$item);
			?>
			<div class="input">
				<input type="text" class="text-input span4" id="film_actors" name="film_actors[]" value="<?php echo end($name);?>"/>
			</div>
			<?php }} else { ?>
			<div class="input">
				<input type="text" class="text-input span4" id="film_actors" name="film_actors[]"/>
			</div>
			<?php } ?>				
			<div id="actor-template-container" class="input"></div>
			<div class="input" style="margin-top: 6px;">
				<span class="button button_green button-medium" id="add-actor">Add Another</span>
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_writers ">Writers</label>
			<?php if (count($post["film_writers"]) > 0) {
			foreach($post["film_writers"] as $item) {
			$name = explode("|",$item);
			?>
			<div class="input">
				<input type="text" class="text-input span4" id="film_writers" name="film_writers[]" value="<?php echo end($name);?>"/>
			</div>
			<?php }} else { ?>
			<div class="input">
				<input type="text" class="text-input span4" id="film_writers" name="film_writers[]"/>
			</div>
			<?php } ?>		
			<div id="writer-template-container" class="input"></div>
			<div class="input" style="margin-top: 6px;">
				<span class="button button_green button-medium" id="add-writer">Add Another</span>
			</div>
		</div>		
		<div class="form-row hr clearfix"></div>
		<div class="form-row clearfix">
			<label for="film_splash_image ">Poster Image </label>
			<div class="input">

				<input class="text-input span1 post-data" id="film_still_image" type="file" name="film_name" value="<?php echo $post["film_still_image"];?>"  />
				<p class="sublabel">(189px Wide x 280px High)</p>
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_splash_image ">Splash Image </label>
			<div class="input">
				<input class="text-input span1 post-data" id="film_splash_image" type="file" name="film_splash_image" value="<?php echo $post["film_splash_image"];?>"  />
				<p class="sublabel">(940px Wide x 300px High)</p>
			</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_synopsis ">Trailer</label>
			<div class="input">
				<input class="text-input span1 post-data" id="trailer" type="file" name="film_trailer_file" value="<?php echo $post["film_trailer_file"];?>"  />
				<p class="sublabel">(file type accepted .mov or .mp4)</p>
			</div>
		</div>

	</div>

	<div class="form-row">
		<span class="link link-cancel left">Cancel</span>
		<button type="submit" id="film_submit" class="button button_green uppercase right">Save</button> 
		<!-- <input type="hidden" name="film_id" value="0" /> -->
		<input type="hidden" name="styroname" value="accountFilm" />
	</div>

</form>
</div>  



<script>
var onUploadSuccess = function(container, file){
	console.log(container, file)
}

$(function(){


	// $("#poster_image").makeAsyncUploader(
	new CTV.Uploader(
		{
		thumbWidth: 120,
		domNode: $("#film_still_image"),
			fieldName: "poster_image",
	    upload_url: "/services/ImageManager/film?constellation_frontend="+$("#session_id").html(),
	    file_types: '*.png;*.PNG;*.jpeg;*.JPEG;*.jpg;*.JPG',
	    file_size_limit: '2 MB',
	    filmId: <?php echo !empty($post['film_id']) ? $post['film_id'] : 'null' ;?>

	});
	new CTV.Uploader(
		{
		thumbWidth: 300,
		domNode: $("#film_splash_image"),
			fieldName: "splash_image",
	    upload_url: "/services/ImageManager/splash?constellation_frontend="+$("#session_id").html(),
	    file_types: '*.png;*.PNG;*.jpeg;*.JPEG;*.jpg;*.JPG',
	    file_size_limit: '2 MB',
	    filmId: <?php echo !empty($post['film_id']) ? $post['film_id'] : 'null' ;?>
	});
	new CTV.Uploader(
		{
		thumbWidth: 120,
		domNode: $("#trailer"),
			fieldName: "trailer",
	    upload_url: "/services/ImageManager/trailer?constellation_frontend="+$("#session_id").html(),
	    file_types: '*.mov;*.MOV;*.mp4;*.MP4',
	    file_size_limit: '20 MB',
	    filmId: <?php echo !empty($post['film_id']) ? $post['film_id'] : 'null' ;?>
	});	


	new CTV.AddTemplate({
		templateContainer: $('#director-template-container'),
		addButton: $('#add-director'),
		template: $('#director-template').html()
	});
	new CTV.AddTemplate({
		templateContainer: $('#producer-template-container'),
		addButton: $('#add-producer'),
		template: $('#producer-template').html()
	})
	new CTV.AddTemplate({
		templateContainer: $('#actor-template-container'),
		addButton: $('#add-actor'),
		template: $('#actor-template').html()
	})	
	new CTV.AddTemplate({
		templateContainer: $('#writer-template-container'),
		addButton: $('#add-writer'),
		template: $('#writer-template').html()
	})	
})
</script>


<script type="template/text" id="director-template">
	<div class="sub-input">
		<input type="text" class="text-input span4" name="film_directors[]"/>
	</div>
</script>
<script type="template/text" id="producer-template">
	<div class="sub-input">
		<input type="text" class="text-input span4" name="film_producers[]"/>
	</div>
</script>
<script type="template/text" id="actor-template">
	<div class="sub-input">
		<input type="text" class="text-input span4" name="film_actors[]"/>
	</div>
</script>
<script type="template/text" id="writer-template">
	<div class="sub-input">
		<input type="text" class="text-input span4" name="film_writers[]"/>
	</div>
</script>

</form>