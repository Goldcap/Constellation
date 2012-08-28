<ul class="left">
    <li><img src="/images/verisign_seal.png" /></li> 
	  <li class="copy">copyright &copy; 2010 Constellation &nbsp;|</li> 
    <li><a href="/contact">contact</a> |</li> 
    <li><a href="/terms">terms &amp; conditions</a> |</li>
    <li><a href="/privacy">privacy policy</a> |</li>
    <li><a href="/purchase">purchase policy</a></li>
</ul>
<ul class="right"> 
    <li><a href="/how">how it works</a></li> 
    <li><a href="/faq">faqs</a></li>
    <li><a href="/about">about us</a></li>
    <?php if ($home) {?>
    <li class="face"><a href="http://www.facebook.com/ConstellationMedia" target="_new"><img src="/images/icon_face.gif" alt="" /></a></li>
    <li class="twit"><a href="http://twitter.com/share?url=<?php echo ('http://'.sfConfig::get("app_domain").$_SERVER["REQUEST_URI"]);?>&text=<?php echo $twitter_share;?>" target="_new"><img src="/images/icon_twit.gif" alt="" /></a></li>
    <?php } elseif ($share_show == 1) {?>
    <li class="face"><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode('http://'.sfConfig::get("app_domain").$_SERVER["REQUEST_URI"]);?>&t=<?php echo $fb_share;?>" target="_new"><img src="/images/icon_face.gif" alt="" /></a></li>
    <li class="twit"><a href="http://twitter.com/share?url=<?php echo ('http://'.sfConfig::get("app_domain").$_SERVER["REQUEST_URI"]);?>&text=<?php echo $twitter_share;?>" target="_new"><img src="/images/icon_twit.gif" alt="" /></a></li>
    <?php } ?>
</ul>
<?php if ($home) {?>
<div id="hometime">
  <!-- TIMEZONE -->
  <?php include_component('default', 
                          'Timezone')?>
  <!-- TIMEZONE -->	 	
</div>
<?php } ?>
