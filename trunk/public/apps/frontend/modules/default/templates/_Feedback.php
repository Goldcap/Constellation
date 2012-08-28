<!-- FEEDBACK POPUP -->
<div class="pop_up" id="feedback_popup" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong><span id="feedback_result">Thanks for your feedback!</span></strong></div>
      	<span id="feedbody">
        <form id="feedback_form" name="feedback_form" action="/services/UserFeedback" method="POST" class="feedback_form" onSubmit="return false">
      	<br />
          <fieldset>
              <!--<div class="clearfix description">Constellation.tv is currently in beta.  We appreciate any and all feedback!</div>
              <div class="clearfix description">Have you had any problems using the site?  Please be as specific as possible, and please include your browser name and version number.</div>
              <div class="clearfix"><textarea id="feedback_problems" name="feedback_problems"></textarea></div>
              <div class="clearfix description">How did you hear about Constellation.tv?  </div>
              <div class="clearfix"><textarea id="feedback_heard" name="feedback_heard"></textarea></div>
              <div class="clearfix description">What brought you to the site today?</div>
              <div class="clearfix"><textarea id="feedback_brought" name="feedback_brought"></textarea></div>
              <div class="clearfix description">Any suggestions?</div>-->
              <div class="clearfix"><textarea id="feedback_suggestions" name="feedback_suggestions"></textarea></div>
              <input id="feedback" type="button" value="submit" class="btn_med-og" name="" /> 
          </fieldset>
        </form>
        </span>
    </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END FEEDBACK POPUP -->
