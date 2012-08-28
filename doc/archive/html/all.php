<?php require 'includes/head.php'; ?>
<link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <?php require 'partials/header.php' ?>
    <div class="sub_head">
        <div class="inner_container clearfix">
            <h2 class="page_title">All Films</h2>
            <p class="sort_head">Sort By 
                <span id="toggleTime" class="button_toggle active"><span>Upcoming Showtimes</span></span>
                <span id="toggleAlpha" class="button_toggle"><span>A-Z</span></span>
            </p>
            <p class="sort_head sort_head_genre">Genre 
                <span id="toggleGenre" class="button_toggle"><span>All</span></span>
                <span class="toggle_options">
                    <span class="wrap">
                        <span class="genre_options">All</span>
                        <span class="genre_options">Classics</span>
                        <span class="genre_options">Comedy</span>
                        <span class="genre_options">Drama</span>
                        <span class="genre_options">Documentary</span>
                        <span class="genre_options">Family</span>
                        <span class="genre_options">Horror</span>
                    </span>
                </span>
                

            </p>
        </div>
    </div>
    
    <div id="content" class="content">
        <div class="inner_container clearfix">
            <div class="film_collection_container loading">
                <ul id="filmContainer" class="film_collection clearfix"></ul>
            </div>
            <div class="button_container clearfix">
                <span id="moreFilms" class="button_large more"><span>More Films</span></span></div>
            </div>
        
        </div>
           
    </div>

    

    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>    
    <script src="/js/all_films.js"></script>
    <script>
        AllFilms.init();
    </script>
    
    
</body> 
</html> 



