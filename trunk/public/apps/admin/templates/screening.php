<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html> 
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <title>The Lottery Theater</title> 
    
    <link rel="shortcut icon" href="/favicon.ico" /> 
    <link rel="stylesheet" type="text/css" media="screen" href="/css/skin.css" /> 
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" /> 
    <link rel="stylesheet" type="text/css" media="screen" href="/css/skin.css" /> 
    <link rel="stylesheet" type="text/css" media="screen" href="/js/jScrollPane/jScrollPane.css" /> 
    <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> 
    <script type="text/javascript" src="/js/screeningRoom/viewer.js"></script> 
    <script type="text/javascript" src="/js/flowplayer-3.2.4.min.js"></script> 
    <script type="text/javascript" src="/js/screeningRoom/videoPlayer.js"></script> 
    <script type="text/javascript" src="/js/screeningRoom/main.js"></script> 
    <script type="text/javascript" src="/js/screeningRoom/screeningCountdown.js"></script> 
    <script type="text/javascript" src="/js/jquery.countdown/jquery.countdown.js"></script> 
    <script type="text/javascript" src="/js/screeningRoom/updater.js"></script> 
    <script type="text/javascript" src="/js/jquery.ajax_head.js"></script> 
    <script type="text/javascript" src="/js/screeningRoom/makeContact.js"></script> 
    <script type="text/javascript" src="/js/jquery.json-2.2.min.js"></script> 
    <script type="text/javascript" src="/js/jquery-ui-1.8.custom.min.js"></script> 
    <script type="text/javascript" src="/js/jScrollPane/jquery.mousewheel.js"></script> 
    <script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script> 
    <script type="text/javascript" src="/js/jScrollPane/jScrollPane-1.2.3.js"></script> 
    <script type="text/javascript" src="/js/swfobject.js"></script> 
    <script type="text/javascript" src="/js/frontend/popup.js"></script> 
    <script type="text/javascript" src="/js/str_replace.js"></script> 
  	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="./css/ie6.css" /><![endif]--> 
  	<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="/css/ie7.css" /><![endif]--> 
  	
  </head> 
  <body class="screening v1"> 
  	<div id="page"> 
  		<div class="page_decorator"> 
       		<div class="page_wrap"> 
                                <div id="content"> 
                  	<div class="content_wrap"> 
		    		  	
<?php echo $sf_content; ?>

<div id="screening-ended-holder"> 
	<div id="screening-ended-message">This screening has now ended. Please feel free to review or recommend this movie to your friends!</div> 
</div> 
<div id="placement-overlay"> 
	<img src="/images/ajax-loader.gif" /> 
</div> 
	<!-- **********************************  FLOWPLAYER PLAYLIST ********************************* --> 
		<div class="clips petrol" style="display: none;"> 
		<!-- single playlist entry --> 
			<a href="flv:video" class="first"></a> 
			<a href="mp4:lottery.mp4" class="second"></a> 
		</div> 
	<!-- ******************************************************************************************** --> 
	<div id="video_screen"> 
	  <div class="video_screen_wrap"> 
	    <div id="video" class="nx_widget_video"> 
			
			<a id="fake-live-player" name="player-fake" class="nx_widget_player" style="width:470px; height: 290px;"></a> 
	       	<a id="player" name="player" class="nx_widget_player" href="#noone" style="width:470px; height: 290px;"></a> 
		  			  		<p id="player-placeholder">Your host has temporarily suspended the video feed.</p> 
		  			  	<div id="place-qanda-here"> 
		  				  	</div> 
		  	<div class="full-screen-button" id="full-screen-button"></div> 
		  	
		    <!-- <div id="video-overlay" class="overlay">
				<div id="video-about"></div>	
			</div> --> 
		  	
	    </div> 
	
	  </div> 
	</div> 
		
	<div id="sidebar"> 
	  <div class="sidebar-top"></div> 
	  <div class="sidebar_wrap"> 
	    <div id="filmmaker_panel" class="sidebar_panel"> 
	      <div class="sidebar_panel_decorator"> 
	        <div class="sidebar_panel_wrap"> 
	        				<div class="top">

				<h2>Film Info</h2>

				<a href="#" class="hide-chat">HIDE</a>

			</div>



<p id ='screening_film_info'>In a country where 58% of African American 4th graders are functionally illiterate, The Lottery uncovers a ferocious debate surrounding public education.  Following four families who enter their children into a school lottery, The Lottery reveals a turf war that is being waged over the future of America’s public schools.</p>	        </div> 
	      </div> 
	    </div> 
	 		
	    <div id="qanda_panel" class="sidebar_panel"> 
	      <div class="sidebar_panel_decorator"> 
	        <div class="sidebar_panel_wrap"> 
	        	          <div class="top">

			<h2>Ask a Question!</h2>

			<a href="#" class="hide-chat">HIDE</a>

		 </div>

		<script type="text/javascript">

			var qandasLeft = '4';

			var defaultImage = 'http://c2.cdn.constellation.tv/uploads/screeningResources/4/logo/small_poster4c344b45275b4.jpg';

		</script>



		<div id="webcam-feed" name="fake-player-holder" class="nx_widget_video" style="width:200px; height: 153px;">

                <img src="http://c2.cdn.constellation.tv/uploads/screeningResources/4/logo/small_poster4c344b45275b4.jpg" alt="Webcam not connected" style="max-width: 200px; max-height: 153px" />

		</div>

					<p id="webcam-feed-placeholder">Your host has temporarily suspended the video feed.</p>

				<div id="qanda-overlay" class="qanda-small-player"></div>

		<p class="qanda-submitted" style="display: none;">Thanks for submitting your question</p>

						<p id="qandaNo">You have 4 questions remaining.</p>

				<form id="form-chat" action="?chat" method="post" class="form-qanda">

					<fieldset class="data">

						<textarea id="fld-qanda" name="qanda"></textarea>

					</fieldset>

								</form>

				<div class="reply-button-holder" id="btn-qanda"><div class="reply-button" name="reply-button" >SUBMIT</div></div>

			        </div> 
	      </div> 
	    </div> 
	    <div id="chat_panel" class="sidebar_panel"> 
	        <div class="sidebar_panel_wrap_chat"> 
	        	<div id="aux-chat" class="chat_list" style="position: absolute; left: -9999px;">

</div>

<div id="aux-chat-2" class="chat_list" style="position: absolute; left: -9999px;">

</div>

          				<div class="top">

					<h2>Chat</h2>

					<a href="#" class="hide-chat">HIDE</a>

				</div>

          			          <div class="chat_list" id="chat_list">

															<div class="new-chat-item processed main-message " id="chat-post-3903"> 

										<div class="image-name me" title="Landis">

																							<p class="chat-post-body" title="Landis says: My Message is one of love!">

													<span class="login-type-3">

														<a title="Landis" class="message-star"href="#">Landis</a>: 

													</span>

													My Message is one of love!												</p>

																					</div>

									</div>

																         					 </div>

					<form id="form-chat" action="?chat" method="post"> 
					<fieldset class="data"> 
						<textarea id="fld-talk" name="talk">Start typing your message...</textarea> 
					</fieldset> 
				</form> 
				<div class="reply-button" id="btn-chat"> 
					<img src="/images/btn[post].png" width="76" height="18" alt="POST" /> 
				</div> 
			</div> 
		</div> 
	
       	<div id="aux_chat_measurement" class="chat_list" style="position: absolute; left: -9999px;"></div> 
	    	
	    <div id="private_panel" class="sidebar_panel"> 
	      <div class="sidebar_panel_wrap_private"> 
	        <div class="sidebar_panel_wrap"> 
	        </div> 
	      </div> 
	    </div> 
	   	<div id="help_panel" class="sidebar_panel"> 
	      <div class="sidebar_panel_decorator"> 
	        <div class="sidebar_panel_wrap"> 
	        				<div class="top"> 
				<h2>Help</h2> 
				<a href="#" class="hide-chat">HIDE</a> 
			</div> 
 
		<p>If your streaming is stuttering or freezing, try refreshing your browser.</p> 
		<p>If you still have trouble, try emptying your browser’s cache and re-starting your browser. You can re-enter the theater via the link on your ticket, or by clicking on the “My Showtimes” link beneath your login name on the Constellation website.</p> 
		<p>To ensure you have the best quality experience while the movie is playing, be sure to <u>close all other browser windows</u> (especially those with videos or media), and try to <u>run as few programs on your computer as possible</u>.</p> 
		<p>For immediate support on all other issues, please contact us at <a class="help-link" href="mailto:support@constellation.tv">support@constellation.tv</a>.</p>	
	        </div> 
	      </div> 
	    </div> 
	  </div> 
	<div class="sidebar-bottom"></div> 
 
</div> 
<div id="screening-overlay" style="display: block;"></div> 
<div id="screening-popup" style="display: block;"> 
	<p id="timer"><span id="countdown"> </span> &nbsp;before the start of <i>The Lottery</i>.</p> 
	
	<div id="theater-container"> 
		<img class="filmposter" src="http://c2.cdn.constellation.tv/uploads/screeningResources/4/logo/small_poster4c344b45275b4.jpg" width="74" height="111" alt="The Lottery poster" /> 
		<h3 id="how-to">THEATER HOW-TO</h3> 
			</div> 
	
	<!-- TODO: uncomment to display the PLAY TRAILER button (check https://dev.nexops.com/streber/index.php?go=taskView&tsk=12637 ) --> 
	<!-- <a id="btn-play" class="button" href="#"></a>  --> 
	
		
	<div id="host-container"> 
		<div class="info"> 
			<p id="title"><span>THE LOTTERY</span></p> 
			<p id="date"><span>Thursday December 9, 8:00pm EST</span></p> 
			
							<p id="hoste-by">Hosted by: Madeleine Sackler</p> 
						
			<p id="about"></p> 
		</div> 
		<img src="http://www.constellation.tv/uploads/hosts/715/thumb_4c7dba941c839.jpg" alt="host" width="79" height="70" />	</div> 
	
		<div id="invite_more_friends" > 
		<a href="/screeningRoom/ieTFNqei7Fu4hjV/join" target="_blank"> 
			INVITE MORE FRIENDS
		</a> 
	</div> 
		
	<div id="screening-share"> 
		<a id="on-twitter" title="Click to share this post on Twitter" class="btn-share" href="http://twitter.com/home?status=Join a screening of The Lottery using http://bit.ly/hmmVb8" target="_blank"><img src="/images/icon-twitter-larger[2].png" width="18" height="18" alt="share on twitter" /></a> 
		<a id="on-facebook" class="btn-share" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.constellation.tv%2FscreeningRoom%2FieTFNqei7Fu4hjV%2Fgz350gtqDT%2Ft%2FJoin+a+screening+of+The+Lottery" ><img src="/images/icon-fb-larger.png" width="18" height="18" alt="share on facebook" /></a> 
				<p id="after_sc_share">SHARE THIS SHOWTIME</p> 
	</div> 
</div>	
<div id="how-to-popup"> 
	<p id = "hide-how-to">HIDE</p> 
	<ol> 
		<li>Click the <img class="img-align" src="/images/icon-expand.png" alt=" " /> in the bottom left hand corner of this window to access the menu. From the menu you can chat with others in the theater, access the Q+A, and get info about the film or the host.</li> 
		<li>Click on the <img class="img-align" src="/images/icon-constellation.png" alt = "" /> and a map of stars should appear. Each star is a person in the theater. You're the red star. You are connected by lines to any friends you invited who attended the screening. Click on a star and a chat box will appear allowing you to talk directly to that person.</li> 
		<li>Q+A: If your host is leading a Q+A, the Q+A button will flash when the host's webcam goes live. Click on it and you will see the host and a box to ask them a question. You only get 5 questions so choose wisely! To leave the Q+A, click on any other menu button.</li> 
		<li>VIDEO: To make the movie full screen, click on the <img src="/images/icon_fullscreen_fp.png" alt=" " />  image in the lower right hand corner of the movie.</li> 
		<li>SOUND: To mute the film or adjust the volume, click on the <img src="/images/icon_sound_fp.png" alt=" " /> button to the left of the full-screen button or click anywhere on the picture.</li> 
	</ol> 
 
</div><script>

	var urlSaveReviewMessage = '/services/film/after';

	var urlSendRecommendation = '/services/film/recommend';

</script>

<div id="after-screening-popup">

	<div id="screening-info">

		<a id="hide-popup" href ="#" >HIDE</a>

		<p class="title">Review and Recommend</p>

		<div class="container">

		  <img class="filmposter" src="http://c2.cdn.constellation.tv/uploads/screeningResources/4/logo/small_poster4c344b45275b4.jpg" width="174" height="260" alt="The Lottery poster" />

		  <div class="did-you-enjoi">

		    <img class="did-you-text" src="/images/title[did-you-enjoi].png" width="274" height="72" alt="DID YOU ENJOI THE MOVIE?" />

		    <fieldset class="buttons">

          <p class="share"><span> SHARE ON: </span>

            <a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.constellation.tv%2FfilmSpecific%2Findex%2Ffilm_id%2F4%2Ft%2FJoin+a+screening+of+The+Lottery" target="_blank"><img src="/images/icon-facebook-medium.png" alt="facebook" width="32" height="32" /></a>

            <a href="http://twitter.com/home?status=Join a screening of The Lottery using http://bit.ly/cxuiUh" target="_blank"><img src="/images/icon-twitter-medium.png" alt="twitter" width="32" height="32" /></a>

          </p>

        </fieldset>

		  </div>

		</div>

				<a id="btn-recommend" class="button" href="javascript:void(0);"></a><br/>

		<a id="btn-write" class="button" href="javascript:void(0);"></a>

	</div>

	<div id="screening-review">

		<p class="title">WRITE A REVIEW</p>

		<form id="form-review" method="post" action="#">

			<textarea id="review" name="review"></textarea>

			<input id="post-review" type="submit" value="POST" />

		</form>

	</div>	

	<div id="recommendation">

		<p class="title">SEND AN EMAIL RECOMMENDATION TO:</p>

		<p id="explain">(separate with commas)</p> 

		<form id="form-emails" method="post" action="#">

			<textarea id="email-list" name="email-list"></textarea>

			<input id="send-recommendation" type="submit" value="SEND" />

		</form>

		<div class="recommendation_overlay">

			<img src="/images/ajax-loader.gif" alt="loading" />

		</div>

	</div>

	<div class="error-panel">

		<div class="errors"></div>

	</div>

	

</div>

<div id="end-qa-popup"> 
	<p id ="close">Close</p> 
	<p >You will not be able to re-start the Q+A. Are you sure?</p> 
	<a id="end-qa" href = "#">Yes</a><a id="close-end-qa-popup" href = "#">No</a> 
</div> 
                  	</div> 
                  </div> 
	    	</div> 
			<script type="text/javascript"> 
	var hostStarTemplate = '<a style="width: 25px; height: 25px; top: {insertTop}; left: {insertLeft}; background-image: url("../images/node-host.png")" name="star-{insertId}" title="{insertName}" id="star-{insertId}" href="#user_profile_panel_holder"></a>';
	var youStarTemplate = '<a style="width: 25px; height: 25px; top: {insertTop}; left: {insertLeft}; background-image: url("../images/node-you.png")" name="star-{insertId}" title="{insertName}" id="star-{insertId}" href="#user_profile_panel_holder"></a>';
	var generalStarTemplate = '<a style="width: 25px; height: 25px; top: {insertTop}; left: {insertLeft}; background-image: url("../images/node-activity.png")" name="star-{insertId}" title="{insertName}" id="star-{insertId}" href="#user_profile_panel_holder"></a>';
</script> 
<div class="arrow-toggle-area"> 
	<div> 
		<p>Click to Expand</p> 
		<a href="#none" class="active" id="arrow-toggle-panel"></a> 
	</div> 
</div> 
<div id="interactive_panel" class="interactive_panel interactive_collapse"> 
  <div class="interactive_panel_decorator"> 
    <a id="interactive_panel_toggle" href="#none"></a> 
    <p class="watching-now" >Watching now: <span id="online-seats">1</span></p> 
    <div class="interactive_panel_wrap  "> 
		  <div class="constellation_buttons "> 
		  	  <script type="text/javascript"> 
		  	  	var imagesUrl = "/images/";
		  	  </script> 
	          <a id="constellation_button" href="#constellation"><img src="/images/icon-constellation.png" alt="show constellation interface" width="30" height="29" /></a> 
			
			
			<!-- BUTTONS HOLDER --> 
			<div id="buttons_holder"> 
			
			 <!-- CHAT --> 
		      <div id="box_chat" class="interactive_box"> 
		        <div class="interactive_box_wrap"> 
		        	<div id="aux-chat" class="chat_list" style="position: absolute; left: -9999px;">

</div>

<div id="aux-chat-2" class="chat_list" style="position: absolute; left: -9999px;">

</div>

                    		<h2><b>CHAT</b></h2>

          								        </div> 
		      </div> 
			  
				
		      
		      <!-- FILM INFO --> 
		      <div id="box_filmmaker" class="interactive_box"> 
		        <div class="interactive_box_wrap"> 
		        				<h2><b>FILM INFO</b></h2>



		        </div> 
		      </div> 
		
						
					      <!-- Q&A --> 
			  <div id="box_qanda" class="interactive_box"> 
        		<div class="interactive_box_wrap"> 
					          <h2><b>Q&amp;A</b></h2>



				</div> 
      		  </div> 
						
			 <!-- REVIEW RECOMMEND --> 
			 <div id="review-recommend" class="review-recommend"> 
        		<div class="interactive_box_wrap"> 
        			<h2><b>REVIEW &amp; RECOMMEND</b></h2> 
        		</div> 
      		 </div> 
      		
      		 <!-- HELP -->  
			 <div id="box_help" class="interactive_box"> 
        		<div class="interactive_box_wrap"> 
								<h2><b>HELP</b></h2> 
 
		<p>If your streaming is stuttering or freezing, try refreshing your browser.</p> 
		<p>If you still have trouble, try emptying your browser’s cache and re-starting your browser. You can re-enter the theater via the link on your ticket, or by clicking on the “My Showtimes” link beneath your login name on the Constellation website.</p> 
		<p>To ensure you have the best quality experience while the movie is playing, be sure to <u>close all other browser windows</u> (especially those with videos or media), and try to <u>run as few programs on your computer as possible</u>.</p> 
		<p>For immediate support on all other issues, please contact us at <a class="help-link" href="mailto:support@constellation.tv">support@constellation.tv</a>.</p>	
				</div> 
      		 </div> 
      		 
      	</div><!-- END BUTTONS HOLDER --> 
 
          </div> 
      </div> 
 
      <div id="visualization" class="interactive_panel_content" style="background: url(http://www.constellation.tv/uploads/constellations/697/1291859170.png) 0 0 no-repeat; width: 930px; height: 230px; margin: -20px auto 0;"> 
		<div id="user_offline-popup" class="popup-container">

	<div class="popup-right">

		<div class="popup-content">

			<div class="x">

				<a href="#" class="popup-close">close X</a>

			</div>

			<div class="popup-body">

			<p>This user is not in the theater.</p>			</div>

		</div>

	</div>

</div>

        <div id="node_list"> 
											<a href="#user_profile_panel_holder" style="top: 57px; left: 276px;" name="constellation-715" class="host-star inactive" id="user-2127" title="Madeleine Sackler[host]"> 
								<img src="/images/host-star.png" /> 
							</a> 
											<a href="#user_profile_panel_holder" style="top: 198px; left: 634px;" name="constellation-732" id="user-2128" title="Landis" class="active"> 
								<img src="/images/node-you.png" /> 
							</a> 
				        </div> 
      </div> 
	  
		<div id="share"> 
			<div class="share_wrap"> 
				
				<a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.constellation.tv%2FfilmSpecific%2Findex%2Ffilm_id%2F4%2Ft%2FJoin+a+screening+of+The+Lottery" target="_blank"><img src="/images/icon-fb-larger.png" alt="facebook" width="18" height="18" /></a> 
				<a href="http://twitter.com/home?status=Join a screening of The Lottery using http://bit.ly/cxuiUh" target="_blank"><img src="/images/icon-twitter-larger.png" alt="twitter" width="18" height="18" /></a> 
				
				<!-- <p class="share">Share 
								</p> --> 
			</div> 
		</div> 
    </div> 
</div> 
  		</div> 
  	</div> 
  </body> 
</html> 
