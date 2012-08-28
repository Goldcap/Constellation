<?php require 'includes/head.php'; ?>
    
    <?php require 'partials/header.php' ?>
    <link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <div id="content" class="content">

    <?php include 'partials/film-marquee.php'?>
  
    <div class="inner_container clearfix">
    <!-- SCREENINGLLIST -->
        <div class="clearfix">
        
            <div class="movies_group">
                <h2 class="content_title">Next Showtimes of <strong>Hesher</strong></h2> 
                <div class="film_block_list">
                <?php include 'partials/film-block.php'?>
                </div>
                <p class="button_wrap">
                    <span id="moreUpcoming" class="button button_white">Later Showtimes <span class="down_button_arrow"></span></span>
                </p>
            </div>
        
            <div class="movies_group movies_group_shadow">
                <h2 class="content_title">Featured Films</h2> 
                <div class="film_block_list">
                <?php include 'partials/film-block.php'?>
                <?php include 'partials/film-block.php'?>
                <?php include 'partials/film-block.php'?>
                </div>
            </div>
        </div> 
    <!-- SCREENINGLLIST -->
 </div>

    <div class="featured_footer">
        <div class="inner_container clearfix featured_footer_list">
            <h6 class="uppercase">Featured Film on Constellation</h6>
            <span class="previous"></span>
            <div class="featured_footer_list_container" id="featureFilmsSlider">
            <ul class="featured_footer_list_thumbs">
                <li><a href="#0">
                    <img width="50" height="76" src="http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg">
                    <span class="details">
                        <span class="title">Hesher</span>
                        <span class="upcoming_title uppercase">Next Showtime</span>
                        <span class="upcoming_time uppercase">9:30 PM</span>
                    </span>
                    </a>
                </li>
                <li><a href="#1">
                    <img width="50" height="76" src="http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg">
                    <span class="details">
                        <span class="title">Black Swan</span>
                        <span class="upcoming_title uppercase">Next Showtime</span>
                        <span class="upcoming_time uppercase">9:30 PM</span>
                    </span>
                    </a>
                </li>
                <li><a href="#2">
                    <img width="50" height="76" src="http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg">
                    <span class="details">
                        <span class="title">Black Swan</span>
                        <span class="upcoming_title uppercase">Next Showtime</span>
                        <span class="upcoming_time uppercase">9:30 PM</span>
                    </span>
                    </a>
                </li>
                <li><a href="#2">
                    <img width="50" height="76" src="http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg">
                    <span class="details">
                        <span class="title">Black Swan</span>
                        <span class="upcoming_title uppercase">Next Showtime</span>
                        <span class="upcoming_time uppercase">9:30 PM</span>
                    </span>
                    </a>
                </li>
            </ul></div>
            <span class="next"></span>
        </div>
    </div>

    <?php include 'partials/film-modal.php' ;?>
    <?php include 'includes/footer.php' ;?>    
    <?php include 'includes/footscripts.php' ;?>    
    <script src="/js/Showtime.js"></script>
    <script src="/js/ShowtimeCollection.js"></script>
    <script src="/js/FeaturedSlider.js"></script>
    
<script>
    $(function() {
        new ShowtimeCollection({url:'/service/upcoming-shows.php',type:'upcoming', button:'#moreUpcoming'});
        new FeatureSlider('#featureFilmsSlider', {next:'.next', previous: '.previous', url: '/service/featured-films.php'})
    });
</script>
</body> 
</html> 

