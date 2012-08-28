<?php 
$isLoggedIn = ($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")));
?>

<div class="home-hero <?php echo $isLoggedIn ? 'home-hero-loggedin':''?>">
	<div class="inner clearfix">
		<span class="hero-logo"></span>

	    <?php if ($isLoggedIn) :?>
	    <ul class="home-hero-user right">
	          <li class="header_timezone acc_timezone">
	            <p><span class="uppercase">Timezone</span> <span id="timezone-select">(change)</span></p>
	            <p class="acc_timezone_link"><?php echo date("H:i:s A T");?></p>
	        </li>
	        <li class="header_user">
	            <div class="header_user_block" id="loggedUser">
	            		<?php if ($sf_user -> getAttribute('user_image')) {
										if (left($sf_user -> getAttribute('user_image'),4) == "http") {?>
	            			<span class="header_user_icon"><img id="photo-url" src="<?php echo $sf_user -> getAttribute('user_image');?>" height="22" /></span>
	                	<?php } else { ?>
	                	<span class="header_user_icon"><img id="photo-url" src="/uploads/hosts/<?php echo $sf_user -> getAttribute('user_id');?>/<?php echo $sf_user -> getAttribute('user_image');?>" height="22" /></span>
	                	<?php } ?>
									<?php } else { ?>
	                <span class="header_user_icon"><img id="photo-url" src="/images/icon-custom.png" height="22" /></span>
	                <?php } ?>
									<span class="header_user_name"><?php echo $sf_user -> getAttribute('user_username');?></span>
	            </div>
	            <ul class="user_info">
	                <li><a href="/account">Account Settings</a></li>
	                <li><a href="/account/showtimes">My Showtimes (<?php echo $total_purchases;?>)</a></li>
	                <li><a href="/logout?dest=<?php echo $LOGOUT_URL;?>">Log Out</a></li>
	            </ul>
	        </li>
        </ul>
    <?php else :?>
		<span class="main-login button button_blue button-medium right uppercase login-button">Log In</span>
    <?php endif; ?>

		<div class="home-hero-text clear clearfix">
			<p>Watch movies together.</br> Connect and chat with the stars in hosted showtimes.</p>
		    <?php if (!$isLoggedIn) :?>
			<span class="button button_green uppercase" onclick=" login.showpopupSignup();">Create Account <span style="font-size: 14px; line-height: 16px; display: block; color: #e0e0e0; text-align:center; position: relative; bottom: -6px"> It's Free!</span></span>
		    <?php endif;?>
		</div>
	</div>
</div>

<?php
	include_component('default', 
                        'LoginAltFree');
	include_component('default', 
                        'GrowlerAlt');
	include_component('default', 
                        'TimezoneAlt');
?>

