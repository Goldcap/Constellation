

<?php require 'includes/head.php'; ?>
    
    <?php require 'partials/header.php' ?>
    <link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <div id="content" class="content">

    <?php include 'partials/film-marquee.php'?>
  
    <div class="inner_container clearfix">
    <!-- SCREENINGLLIST -->
        <div class="clearfix">
        
            
            <h4 class="no_show">There are currently no showtimes for this film! <br/> Why don’t you <a href="">host a showtime?</a></h4>
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

