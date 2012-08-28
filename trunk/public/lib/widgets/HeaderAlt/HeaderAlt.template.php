<?php 
$isLoggedIn = ($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")));
?>
<div class="headline">
    <div class="logo_alt"><a title="Return to the home page" href="/"></a></div>
    <ul class="header-nav">
        <li><a class="button  button-black button-medium" href="/#events">Find Events</a></li>
		<?php if ($isLoggedIn) :?>
		<li><a class="button button-black button-medium" href="/account/showtimes">My Events <?php /*if ($sf_user -> isAuthenticated()) {?><span class="showtime_count"><?php echo $total_purchases;?></span><?php } */?></a></li>
		<?php endif; ?>
        <li><a class="button  button-black button-medium" href="/help<?php echo $sf_user["user_id"] > 0 ? '?uid='.$sf_user["user_id"]: '' ?>" onclick="window.open('/help?uid=<?php echo $uid;?>','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=640'); return false" target="_blank" id="help">Help</a></li>
        <li class="header-nav-fb"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.constellation.tv&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=dark&amp;font=arial&amp;height=21&amp;appId=185162594831374" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe></li> 
    </ul>
        <?php if ($isLoggedIn) :?>
    <div class="header-user" id="header-user">
        <img class="header-user-avatar" src="<?php echo getSessionAvatar($sf_user);?>" height="32" width="32" />
        <div class="header-user-info">
            <p class="header-user-info-name"><?php echo $sf_user -> getAttribute('user_username');?></p>
            <p class="header-user-info-timezone">Timezone: <?php echo date("T (O)");?></p>
        </div> 
        <ul id="header-nav" class="header-user-nav">
            <li><a href="/account">My Profile</a></li>
            <li><a href="/account">Update Timezone</a></li>
            <li><a href="/account/showtimes">My Events</a></li>
            <li><a href="/logout?dest=<?php echo $LOGOUT_URL;?>">Log Out</a></li>
        </ul>
    </div>
    <?php else :?>
    <div class="right header-login-wrap">
        <span class="button button-medium button-blue auth-signup">Sign Up</span>
        <span class="or">Or</span>
        <span class="button button-medium button-blue auth-login">Log In</span>
    </div>
    <?php endif; ?>    
</div>
    

<script>
require(['CTV/Controller/User'], function(User){
    new User({
        isLoggedIn: <?php echo $isLoggedIn ? 'true':'false';?>,
        hasLogginError: <?php echo $sf_user -> hasFlash('error-login')? 'true': 'false';?>,
        hasSignupError: <?php echo $sf_user -> hasFlash('error-signup')? 'true': 'false';?>
    });
});
</script>