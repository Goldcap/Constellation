<?php
	 if (! isset($ctext)) {
			$ctext  = "Constellation.tv presents: \"".$film["screening_film_name"]."\" viewed online for yourself or with your friends.";
	}
	if (! isset($clink)) {
			$clink  = 'http://'.sfConfig::get("app_domain").'/theater/'.$film["screening_unique_key"];
	}

$twitterText = 'I am in the #'. str_replace(" ", "",strtoupper($film['screening_film_name'])) .' theater live @constellationtv.'. (isSet($film['screening_film_twitter_tags']) ? ' ' . $film['screening_film_twitter_tags'] : '' ).' Join me here: ' . $clink . $fbeacon;

if($film['screening_unique_key'] == 'ChxLSNkfmUN9XFJ'){
	$twitterText = 'Live FREE online event with @DonaldMiller for @bluelikejazz  on 3/12 at 8:00 PM.  www.constellation.tv/bluelikejazz #bluelikejazz';
}

$facebookDescription = 'Join me in the theater';
if ($film["screening_user_full_name"] != '' && $film["screening_user_id"] != $sf_user["user_id"] ){
	$facebookCaption = $film["screening_user_full_name"] . ' is live on Constellation presenting ' . $film["screening_film_name"] .'.';
}

?>


<div id="footer" class="footer">
	<?php if(isSet($partner['partner_logo'])):?>
	<div  class="logo-partner use-tip" title="Exit Theater"><a href="<?php echo $partner["partner_url"];?>" target="_blank"><img src="<?php echo $partner["partner_logo"];?>" height="50" /></a></div>
	<?php else: 
	if ($film["screening_film_id"] == 119) {?>
	<a href="/" title="Exit Theater" class="logo use-tip"></a>	
	<?} else {?>
	<a href="/event/<?php echo $film["screening_unique_key"];?>" title="Exit Theater" class="logo use-tip"></a>
	<?php }
	endif;?>

	<div class="footer-aside">

<?php
	if (($sf_user["user_id"] > 0) && !empty($seat)) :
  	$uid = $userid = $sf_user["user_id"];
		$tid = $seat -> getAudienceInviteCode();
		$sid = $film["screening_unique_key"];
?> 
		<ul class="footer-extra footer-extra-help">
	<li>
		<a href="/help?uid=<?php echo $uid;?>&tid=<?php echo $tid;?>&sid=<?php  echo $sid;?>" onclick="window.open('/help?uid=<?php echo $uid;?>&tid=<?php echo $tid;?>&sid=<?php echo $sid;?>','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540'); return false" target="_help" class="support button-black button uppercase">Help</a>
	</li>
		</ul>
<?php else: ?>
		<ul class="footer-extra footer-extra-help">
	<li>
		<a href="/help" onclick="window.open('/help?uid=<?php echo $uid;?>&tid=<?php echo $tid;?>&sid=<?php echo $sid;?>','_help','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540'); return false" target="_help" class="support button-black button uppercase">Help</a>
	</li>
		</ul>
<?php endif;?>	
		<ul class="footer-extra footer-extra-social">

			<li class="button button-twitter">
				<span id="twitter-footer-button" onclick="window.open('http://twitter.com/intent/tweet?text=<?php echo urlencode($twitterText)?>','_share_twitter','width=450,height=300'); return false;" class="twitter use-tip" title="Share on Twitter"></span>
			</li>
			<li class="button button-facebook">
				<span id="facebook-footer-button"  onclick="window.open('http://www.facebook.com/dialog/feed?app_id=185162594831374&link=<?php echo urlencode($clink.$fbeacon)?>&name=<?php echo urlencode( 'Constellation.tv presents: ' . $film["screening_film_name"])?><?php echo isSet($facebookCaption) ? '&caption=' . urlencode($facebookCaption) : ''?>&description=<?php echo urlencode($facebookDescription)?>&picture=<?php echo urlencode('http://www.constellation.tv/uploads/screeningResources/'.$film["screening_film_id"].'/logo/small_poster'.$film["screening_film_logo"]);?>&redirect_uri=http%3A%2F%2Fwww.constellation.tv','_share_facebook','width=450,height=300'); return false;" class="facebook use-tip" title="Share on Facebook"></span>
			</li>		

	</ul>
		
	</div>
</div>