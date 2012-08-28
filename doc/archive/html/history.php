<?php require 'includes/head.php'; ?>
<link href="/css/styles.css" rel="stylesheet" type="text/css" />
    
    <?php require 'partials/header.php' ?>
    
    <div class="sub_nav clearfix">
        <div class="inner_container clearfix">
            <ul class="sub_nav_list left">
                <li class="active"><a href="/showtimes/all">All</a></li> 
                <li><a href="/showtimes/attending">Attending</a></li>
                <li><a href="/showtimes/hosting">Hosting</a></li>
            </ul>
            <ul class="sub_nav_list right">
                <li><a href="/showtimes/history">History</a></li>
            </ul>
        </div>
    </div>
    
    <div id="content" class="content">
        <div class="inner_container clearfix">
                <div class="movies_group">
                    <h2 class="content_title">History</h2> 
                    <div class="film_block_list">
                    <?php include 'partials/film-history-block.php'?>
                    <?php include 'partials/film-history-block.php'?>
                    <?php include 'partials/film-history-block.php'?>
                    </div>
                </div>
            
        </div>
       
    

    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>    

</body> 
</html> 



