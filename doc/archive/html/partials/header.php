<div id="masterhead" class="masterhead" role="banner">
    <div class="headline">
    <div class="logo_alt"><a title="Return to the home page" href="/"></a></div>
    <ul class="header_nav">
        <li><a href="/all.php">Browse All Movies</a></li>
        <li><a href="/">My Showtimes</a></li>
    </ul>
    
    <div class="searchbar_alt"><!--<input type="text" class="search_alt" />--></div>
    
    <ul class="logo_alt_set">
    <?php $isLoggedIn = true;?>
    <?php if($isLoggedIn):?>
        <li class="header_timezone acc_timezone">
            <p><span class="uppercase">Timezone</span> <span id="timezone-select">(change)</span></p>
            <p class="acc_timezone_link">13:25:55 PM EDT</p>
        </li>
        <li class="header_user">
            <div class="header_user_block" id="loggedUser">
                <span class="header_user_icon"><img src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png" height="22" width="22" /></span>
                <span class="header_user_name">gordoncc</span>
            </div>
            <ul class="user_info">
                <li><a href="">Account Settings</a></li>
                <li><a href="">Log Out</a></li>
            </ul>
        </li>
    <?php else :?>
        <li><a href="#" class="main-login">Log In</a></li>
    <?php endif;?>
    </ul>
    
    </div>
</div>
