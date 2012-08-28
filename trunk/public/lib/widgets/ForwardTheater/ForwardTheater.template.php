<?php 
$twitterText = 'Join me at the event "'. ($event["screening_name"] !='' ? $event["screening_name"] : $event["screening_film_name"]).'" on @ConstellationTV. http://www.constellation.tv/event/' . $event['screening_unique_key'] ;
if($event['screening_twitter_text'] != ''){
  $twitterText = $event['screening_facebook_text'];
}

$facebookDescription = 'Constellation.tv presents: '. ($event["screening_name"] !='' ? $event["screening_name"] : $event["screening_film_name"]) .'. Join me in at this event.';
if($event['screening_facebook_text'] != ''){
  $facebookCaption = $event['screening_facebook_text'];
}elseif ($event["screening_user_full_name"] != ''){
  $facebookCaption = $event["screening_user_full_name"] . ' is live on Constellation presenting ' . $event["screening_film_name"] .'.';
}

?>
<style>
    body {
      background: #333!important;
      box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      -moz-box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      -webkit-box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      height: 100%;
    }
    .confirmation-dialog h1 {
           color: #000;
           font-size: 36px;
           line-height: 36px;
           font-family: "helveticaneuecondensed", helvetianeue, sans-serif;

    }
    .confirmation-dialog p {
      color: #555;
    }
    .confirmation-dialog p {margin: 10px 0;}
    .ajax-loader {margin: 10px auto;}
    .dialog-logo {position: relative; top: -20px; left: -5px; margin: 0 auto -20px;}
    .confirmation-dialog .hr {margin-bottom: 20px;}
    .button-block {display: block;}
    #button-theater {margin-bottom: 10px;}
</style>

<div class="dialog confirmation-dialog center">
    <img src="/images/checkout-logo.png" class="dialog-logo " />
      <div class="hr"></div>
            <h1>Thank You!</h1>
            <p>You now have a ticket to <?php echo $event["screening_name"] !='' ? $event["screening_name"] : $event["screening_film_name"]?>.</p>
            <p>If you registered via Facebook or email, you will be receiving a confirmation email, as well as a reminder six hours before the event starts.</p>
            <img id="loader-theater" src="//www.constellation.tv/images/bg/big-ajax-loader.gif" class="ajax-loader">
            <div id="button-theater" class="hide">
              <a href="/theater/<?php echo $event["screening_unique_key"];?>" class="button button-green button-block uppercase">Enter Theater</a>
            </div>
      <div class="hr"></div>
            <h4>Invite your Friends!</h4>
            <p>Use the Facebook &amp; Twitter buttons below to share this event with your friends and followers. </p>
      <div onclick="window.open('http://www.facebook.com/dialog/feed?app_id=185162594831374&link=<?php echo urlencode('http://www.constellation.tv/event/'.$event['screening_unique_key'])?>&name=<?php echo urlencode( 'Constellation.tv presents: ' . $film["screening_film_name"])?><?php echo isSet($facebookCaption) ? '&caption=' . urlencode($facebookCaption) : ''?>&description=<?php echo urlencode($facebookDescription)?>&picture=<?php echo urlencode('http://www.constellation.tv/uploads/screeningResources/'.$film["screening_film_id"].'/logo/small_poster'.$film["screening_film_logo"]);?>&redirect_uri=http%3A%2F%2Fwww.constellation.tv','_share_facebook','width=450,height=300'); return false;" class="button button-facebook button-facebook-invite button-block"><span class="icon-facebook"></span>Share on Facebook</div>   
      <div onclick="window.open('http://twitter.com/intent/tweet?text=<?php echo urlencode($twitterText)?>','_share_twitter','width=450,height=300'); return false;" class="button button-twitter button-block button-twitter-invite"><span class="icon-twitter"></span>Share on Twitter</div>

</div>
<script type="text/javascript"> 
// 'window.location.href="<?php echo $sf_request->getAttribute('dest'); ?>";'

require(['CTV/Controller/InviteRecord'],function(Invite){
  setTimeout ( function(){
    $('#loader-theater').hide();
    $('#button-theater').fadeIn();
  }, 5000 );
  new Invite({
    film: <?php echo $event["screening_film_id"];?>,
    screening: '<?php echo $event["screening_unique_key"];?>',
    source: 'confirmation'
  });

});
</script>
