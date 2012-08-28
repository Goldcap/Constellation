<div class="inner_container clearfix">
    <h2 class="page_title">All Films</h2>
    <p class="sort_head">Sort By 
        <span id="toggleTime" class="button_toggle"><span>Upcoming Showtimes</span></span>
        <span id="toggleAlpha" class="button_toggle active"><span>A-Z</span></span>
		</p>
		<p class="sort_head sort_head_genre">Genre 
        <span id="toggleGenre" class="button_toggle"><span>All</span></span>
        <span class="toggle_options">
            <span class="wrap">
            		<span class="genre_options">All</span>
                <?php foreach($genres as $genre) {?>
		            <span class="genre_options"><?php echo $genre["genre_name"];?></span>
		            <?php }?>
            </span>
        </span>
    </p>
</div>
