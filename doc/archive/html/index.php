
<?php require 'includes/head.php'; ?>

<link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <?php require 'partials/header.php' ?>

    <?php require 'partials/hero.php' ?>


    <div id="content" class="content">
        <div class="inner_container clearfix">
        <div class="clearfix">
        
            <div class="movies_group">
                <h2 class="content_title">Next Showtimes</h2>
                <div class="film_block_list">
                <?php include 'partials/film-block.php'?>
                <?php include 'partials/film-block.php'?>
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
                <p class="button_wrap">
                    <span id="moreFeatured" class="button button_white">Later Showtimes <span class="down_button_arrow"></span></span>
                </p>
            </div>
        
        </div> 
        
                   
    </div>
    <?php include 'partials/film-modal.php' ;?>    

    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>    
    
    <!-- For the Top Banner -->
        <script src="js/slider.js"></script>
    
    
        <script type="text/javascript">
        
           var slider = new Slideshow(
           $('#hp-slideshow')
           ,{
                generatePagination: false,
                pagination: true,
                currentClass: 'active',
                play: 5000,
                hoverPause: true,
                url: '/service/feature-films-slide.php'
            });
        
<!--            console.log(slider)-->
        
        </script>
        <!-- End Fopr the Top Banner -->
    
    <script src="/js/Showtime.js"></script>
    <script src="/js/ShowtimeCollection.js"></script>
    
    
    
    
    <script>
        $(function() {
        new ShowtimeCollection({url:'/service/upcoming-shows.php',type:'upcoming', button:'#moreUpcoming'});
        new ShowtimeCollection({url:'/service/upcoming-shows.php',type:'upcoming', button:'#moreFeatured'});
        });
    </script>
    
</body> 
</html> 



