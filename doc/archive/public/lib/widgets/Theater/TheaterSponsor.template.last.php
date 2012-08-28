<!-- START MAIN CONTENT -->
<div id="prescreening_panel_main">
  <div class="prescreening_left sidepanel">
    <img class="poster" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/small_poster<?php echo $film["screening_film_logo"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still"  border="0"/>
  </div>
  <div class="prescreening_right sidepanel">
    <?php if ($film["screening_user_id"] > 0) {?>
      YOUR HOST:<br /><br />
      <strong><?php echo $film["screening_user_full_name"];?></strong><br /><br />
      <?php if ($film["screening_guest_image"] != '') {
        if (left($film["screening_guest_image"],4) == "http") {?>
        <img class="host" height="141" src="<?php echo $film["screening_guest_image"];?>" alt="host photo" />
      <?php } else { ?>
        <img class="host" height="141" src="/uploads/hosts/<?php echo $film["screening_guest_image"];?>" alt="host photo" />
      <?php }} elseif ($film["screening_still_image"] != '') {?>
        <img class="host" height="141" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/screenings/film_screening_large_<?php echo $film["screening_still_image"];?>" />			
      <?php } else {?>
        <?php if (($film["screening_sponsor_domain_template"] != '') && ($film["screening_sponsor_domain_image"] != '')) {?>
          <img src="/images/sponsor/<?php echo $film["screening_sponsor_domain_template"];?>/host_image_<?php echo $film["screening_sponsor_domain_image"];?>.png" />
        <?php } else { ?>
        <img class="host" height="141" src="/images/constellation_host.jpg" />			
        <?php } ?>
      <?php } ?>
    <?php }?>
  </div> 
  <div class="prescreening_center">
    <div class="filminfowrap">
      <span class="filminfo">
        <?php echo $film["screening_film_info"];?>
      </span>
    </div>
    <!--<div class="filminfowrap">
      <a class="btn_og screening_link" title="<?php echo $film["screening_unique_key"];?>">INVITE FRIENDS</a>
    </div>-->
  </div>
  <div class="clear clearfix"></div>
  
</div>
<!-- END MAIN CONTENT -->

<!-- START HOWTO POPUP -->
<div id="how-to-popup" style="display: none">
  <h2>How To View This Video</h2>
  <h4>HIDE</h4>  	
  <ol>  		
    <li>Click the 
    <img class="img-align" src="/images/icon-expand.png" alt=" " /> in the bottom left hand corner of this window to access the menu. From the menu you can chat with others in the theater, access the Q+A, and get info about the film or the host.
    </li>  		
    <li>Click on the 
    <img class="img-align" src="/images/icon-constellation.png" alt = "" /> and a map of stars should appear. Each star is a person in the theater. You're the red star. You are connected by lines to any friends you invited who attended the screening. Click on a star and a chat box will appear allowing you to talk directly to that person.
    </li>  		
    <li>Q+A: If your host is leading a Q+A, the Q+A button will flash when the host's webcam goes live. Click on it and you will see the host and a box to ask them a question. You only get 5 questions so choose wisely! To leave the Q+A, click on any other menu button.
    </li>  		
    <li>VIDEO: To make the movie full screen, click on the 
    <img src="/images/icon_fullscreen_fp.png" alt=" " />  image in the lower right hand corner of the movie.
    </li>  		
    <li>SOUND: To mute the film or adjust the volume, click on the 
    <img src="/images/icon_sound_fp.png" alt=" " /> button to the left of the full-screen button or click anywhere on the picture.
    </li>  	
  </ol>    
</div>
<!-- END HOWTO POPUP -->

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- SCREENING PANEL -->
<div id="video_panel" class="theater_panel panel" style="display: none">	    
     
    <div id="video_stream" class="nx_widget_player">
      <div id="movie_stream" class="nx_widget_player">
        <div class="screening_wrapper" style="display: none;">
        	<div class="screening_still" style="position: absolute; z-index: 10000; float: left;">
          <p><strong>You don't have the correct version of Adobe Flash installed, and cannot watch films on Constellation.TV <br />
					without Adobe Flash version 10.2 or newer. </strong><br /><br /></p>
					<p>Please download it at <br />
					<a href="http://get.adobe.com/flashplayer">http://get.adobe.com/flashplayer</a> <br />
					and return to watch this film.</p><br /><br /></p>
          <p><img src="http://wwwimages.adobe.com/www.adobe.com/images/shared/product_mnemonics/165x165/flashplayer_165x165.png" /></p>
          <!--<img src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/still/<?php echo $film["screening_film_still_image"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still" border="0" width="480" height="320" />-->
        	</div>
				</div>
        <span class="reqs" id="video">/services/Tokenizer/<?php echo $film["screening_film_id"];?>/map.smil</span>
        <span class="reqs" id="video_stream"></span>
        <span class="reqs" id="video-autoplay">true</span>
        <span class="reqs" id="video-port">45907</span>
        <span class="reqs" id="video-type">VOD</span>
      </div>
    </div>
</div>
<!-- END SCREENING PANEL -->

<?php } elseif (! isset($auth_msg)) { ?>
<!-- POST SCREENING PANEL -->
<div id="review_panel" class="theater_subpanel panel" style="display: none">	

  <h1>Review and Recommend</h1>
  
  <h4>hide >></h4>
  <img class="filmposter" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/small_poster<?php echo $film["screening_film_logo"];?>" width="60" alt="<?php echo $film["screeing_film_title"];?>" />		  
  <img class="did-you-text" src="/images/title[did-you-enjoi].png" width="274" height="72" alt="DID YOU ENJOY THE MOVIE?" />		    
  
  <p class="share">
    <a title="Click to share this post on Twitter" class="btn-share" href="http://twitter.com/home?status=Join a screening of <?php echo $film["screening_film_name"];?> using http://bit.ly/hmmVb8" target="_blank">
    <img src="/images/icon-twitter-larger[2].png" width="18" height="18" alt="share on twitter" />
    </a>
    <a class="btn-share" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode("http://".sfConfig::get("app_domain")."/screening/".$film["screening_unique_key"]."/gz350gtqDT/t/Join a screening of ".$film["screening_film_name"]);?>" >
    <img src="/images/icon-fb-larger.png" width="18" height="18" alt="share on facebook" />
    </a>
    <span class="share_inset">SHARE THIS SHOWTIME</span>  				
  </p>
  
  <a id="btn-send" class="button" href="javascript:void(0);"><button class="btn_medium" name="send_button">SEND A RECOMMENDATION</button></a>
  <a id="btn-write" class="button" href="javascript:void(0);"><button class="btn_medium" name="send_button">WRITE A REVIEW</button></a>	
	
	
  <div id="send_popup" style="display: none">
    <div id="send_form">
    <h2>SEND AN EMAIL RECOMMENDATION TO:</h2>
    <p id="sendmessage">(separate with commas)</p>  		
    <form id="sendform" method="post" action="#">			
      <textarea id="sendmessage" name="messagebody"></textarea>			
      <input type="submit" id="send-submit" class="btn_small" value="send" alt="POST" />
      <input type="hidden" name="user_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
      <input type="hidden" name="screening_id" value="<?php echo $film["screening_id"];?>" />
      <input type="hidden" name="method" value="send" />
    </form>
    </div>
    <div id="send_inbox" style="display: none">Thanks for sharing this screening. [ <a id="send_edit" href="javascript: void(0);">share more >></a> ]<br /><br /></div>
  </div>
  
  <div id="write_popup" style="display: none">
    <div id="write_form">
    <h2>WRITE A REVIEW</h2>
    <p id="writemessage">(Careful, this may be shown to other people)</p> 
    <form id="writeform" method="post" action="#">			
      <textarea id="writemessage" name="messagebody"><?php echo $review["audience_review"];?></textarea>	
      <input type="submit" id="write-submit" class="btn_small" value="submit" alt="POST" />
      <input type="hidden" name="user_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
      <input type="hidden" name="screening_id" value="<?php echo $film["screening_id"];?>" />
      <input type="hidden" name="method" value="write" />
    </form>
    </div>
    <div id="write_inbox" style="display: none">Thanks for sharing your thoughts. [ <a id="write_edit" href="javascript: void(0);">edit >></a> ]<br /><br /></div>
  </div>
  
</div>
<!-- END POST SCREENING PANEL -->
<?php } else { ?>
  <div id="noauth"></div>
  <script type="text/javascript">
    $(document).ready(function() {
      login.showpopup();
    });
  </script>
<?php }?>

<!-- START PROMOTIONS PANEL -->
<div id="promo_panel" class="theater_subpanel panel" style="display: none">  	
  
  <h1>Promotions</h1>
  
  <div class="slideshow" style="display:none">
    <?php if (count($promotions) > 0) { foreach ($promotions as $promo) { 
		 echo '<div class="promo" id="p'.$promo["promotion_id"].'" style="display: none" delay="'.($promo["promotion_duration"] * 1000).'">'.$promo["promotion_text"].'</div>';
	  }} ?>
	</div>
  
</div>
<!-- END PROMOTIONS PANEL -->

<!-- START Q AND A PANEL -->
<div id="qa_panel" class="theater_subpanel panel" style="display: none">  	
  
  <h1>Questions and Answers</h1>
  
  <h4>hide >></h4>
  
  <p class="how-to">
    <span id="qa_current_question">
      <div class="question" id="slide0">
        <div id="slide_quest0">The Q and A will start in a moment.</div>
        <div id="slide_answ0">Thanks for your patience...</div>
      </div>
    </span>
  </p>
  
  <p class="share">
    <a title="Click to share this post on Twitter" class="btn-share" href="http://twitter.com/home?status=Join a screening of <?php echo $film["screening_film_name"];?> using http://bit.ly/hmmVb8" target="_blank">
    <img src="/images/icon-twitter-larger[2].png" width="18" height="18" alt="share on twitter" />
    </a>
    <a class="btn-share" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode("http://".sfConfig::get("app_domain")."/screening/".$film["screening_unique_key"]."/gz350gtqDT/t/Join a screening of ".$film["screening_film_name"]);?>" >
    <img src="/images/icon-fb-larger.png" width="18" height="18" alt="share on facebook" />
    </a>
     <span class="share_inset">SHARE THIS SHOWTIME</span>  				
  </p>
  <?php if ($host == $film["screening_film_id"]) {?>
  <div id="answer_popup" style="display: none">
    <div id="answer_form">
    <h2>Answer the Current Question:</h2>
    <p id="answermessage">(Careful, this will be shown to other people once you hit submit)</p> 
    <form id="answerform" method="post" action="#">			
      <textarea id="answerbody" name="answerbody"></textarea>	
      <input type="image" id="answer-submit" src="/images/btn[post].png" width="76" height="18" alt="POST" />
      <input type="hidden" id="qa_question" name="qa_question" value="0" />
      <input type="hidden" name="method" value="answer" />
    </form>
    </div>
    <div id="answer_inbox" style="display: none">Your answer was recorded. [ <a id="write_edit" href="javascript: void(0);">edit >></a> ]<br /><br /></div>
  </div>
  
  <div id="screening" class="reqs"><?php echo $film["screening_id"];?></div>
  <?}?>
  
</div>
<!-- END Q AND A PANEL -->

<!-- START CLOSE Q AND A POPUP -->
<div id="end-qa-popup" style="display: none">  	
  <p id ="close">Close
  </p>  	
  <p >You will not be able to re-start the Q+A. Are you sure?
  </p>  	
  <a id="end-qa" href = "#">Yes</a>
  <a id="close-end-qa-popup" href = "#">No</a>  
</div>
<!-- END CLOSE Q AND A POPUP -->


<!-- START SCREENING ENDED PANEL -->
<div id="screening-ended-holder" class="theater_panel" style="display: none">  	
  <p id="screening-ended-message">This screening has now ended. Please feel free to review or recommend this movie to your friends!</p>  
</div>
<!-- END SCREENING ENDED PANEL -->

<!-- INVITE POPUP -->
<div class="pop_up" id="screening_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<!--<p><strong>
        <?php echo $film["screening_film_name"];?>
        </strong></p>-->
        <p><strong>invite friends via:</strong></p>
          <form id="invite" action="#" class="email_enter-form" onSubmit="return false">
              <fieldset>
                <div class="soc-app clearfix">
                  <input type="button" value="facebook" class="btn_small import_click" name="facebook" alt="facebook" />
                  <input type="button" value="twitter" class="btn_small import_click" name="twitter" alt="twitter" />
                  <input type="button" value="gmail" class="btn_small import_click" name="gmail" alt="gmail" />
                  <input type="button" value="yahoo" class="btn_small import_click" name="yahoo" alt="yahoo" />
                  <input type="button" value="hotmail" class="btn_small import_click" name="hotmail" alt="hotmail" />
                  <input type="button" value="aol" class="btn_small import_click" name="aol" alt="aol" />
         			  </div>
         			  <div class="soc-note clearfix">
                  <p><strong>or manually enter email addresses</strong></p>
                </div>
         			  
                <!-- CUSTOM IMPORT -->
                 <div id="import-contacts-container" style="display: none">		  	
                  
                  <h4 id="service_name"></h4>

                  <!-- INVITE LOGIN CONTAINER -->
                  <div class="login" action="#" method="post">		  		
                    
                    <fieldset>		  			
                      <input id="import-contacts-type" type="hidden" name="import-contacts[type]" value="" />					
                          <span id="provider-select" style="display:none;">
                            <select id="provider-alternate" name="provider">
                              <?php foreach ($oi_services as $type=>$providers)	 {?>
                          			<optgroup label='<?php echo $inviter->pluginTypes[$type];?>'>
                          			<?php foreach ($providers as $provider=>$details) {
                                  if (in_array($provider,array('gmail','yahoo','hotmail','aol'))) { continue; }?>
                          				<option value='<?php echo $provider;?>' <?php if ($_POST['provider_box']==$provider) { echo 'selected=\'selected\''; }?>><?php echo $details['name'];?></option>
                          			<?php } ?>
                                </optgroup>
                          		<?php } ?>
                          	</select>
                        	</span>
                          <input name="import-contacts[email]" type="text" class="text import_field" id="import-contacts-email" />						
                          <input name="import-contacts[password]" type="password" class="text import_field" id="import-contacts-password" />						
                          <input type="button" class="btn_tiny" id="btn-import" value="import" alt="import" />					
                      
                        <div id="contacts-error-area" style="color: red"></div>					
                     
                        <input name="import-contacts[provider]" type="hidden" class="text" id="import-contacts-provider" value="0" />
                        
                        <div id="contacts-loading-area"><img src="/images/ajax-loader.gif" alt="loading" /></div>	
                    
                    </fieldset>	
                    	  	
                  </div>	
                  
                </div>
                <!-- END CUSTOM IMPORT -->
                
              	    <div class="left">
              	      <ul id="fld-invites-container">
                        <li class="add_invite" title="add_invite"><div id="add_invite">Click To Add Email</div></li>
                      </ul>
                      <!--<textarea id="fld-invites" name="invite_emails" ></textarea>-->
                      <a id="text-contacts" class="accept-contacts" href="#"></a>
                    </div>
                    <div class="right clearfix">
                        <ul id="accepted-invites-container"></ul>
			              </div>
                    <div class="soc_message">     	
                      <p class="legend">Personalize your invite:</p>	        
                      <label id="invite-fld-greeting-label" for="fld-greeting">Add a personal message to your invite <span id="invite-textbox-limit">150 characters</span></label>	        
                      <textarea id="invite-fld-greeting" name="greeting" class="with-label host-invite" rows="2"></textarea>	      
                    </div>
                    <div class="soc_message">
                      <input type="button" id="btn-preview_invite" value="preview" class="btn_small mr_3" name="" />
                      <input type="button" id="btn-invite" value="send" class="btn_small" name="" />
                    </div>
                  <div class="clear"></div>
                  <!--<h2>Number of invites: <span id="number_invites">0</span></span></h2>-->
                  <input type="hidden" id="fb_session" value="" name="fb_session" />
                  <input type="hidden" id="tw_session" value="" name="tw_session" />
                  <input type="hidden" id="email_session" value="" name="email_session" />
             </fieldset>
          </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END INVITE POPUP -->

<div class="popup" id="preview-invite-popup" style="display: none">		
  <div class="close-bar">			
    <a id="close-preview-invite-popup">(close)</a>		
  </div>		
  <div class="invite-content">		
  </div>	
</div>

<div class="popup" id="inviting-popup" style="display: none">	
  <p align="center">Sending Invitations...<br />
  <img src="/images/ajax-loader.gif" alt="loading" />	
  </p>
</div>
