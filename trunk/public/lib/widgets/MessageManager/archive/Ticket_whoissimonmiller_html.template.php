<div style="width:676px;height:263px;font-family:'Helvetica Neue',Helvetica,Arial,Verdana,sans-serif;color:#fff;background-image:url('http://<?php echo sfConfig::get("app_domain");?>/images/ticketbg_walmart.png');background-repeat:no-repeat;background-position:left top;background-color:#040027;">
	<div style="width:661px;padding:0 0 0 15px;overflow:hidden;">
		<img style="float:left;color:#aaa;margin-top:18px;width:141px;height:209px;display:block;" src="http://<?php echo sfConfig::get("app_domain");?>/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/small_poster<?php echo $film["film_logo"];?>" title="<?php echo $film["film_name"];?>" alt="<?php echo $film["film_name"];?>" />
		<div style="float:left;width:200px;height:214px;overflow:hidden;padding:16px 0 33px 17px;">
			<p style="margin:0;padding:0;font-size:28px;font-weight:bold;color:#9dcefb"><span style="border-bottom:solid 1px">ADMIT ONE</span></p>
			<p style="margin:0;padding:10px 0 0;font-size:22px;font-weight:bold;color:#9dcefb"><?php echo strtoupper($film["film_name"]) ?></p>
			<p style="margin-top: 90px"><a href="http://truthbetold.constellation.tv/film/<?php echo $film["film_id"];?>" style="margin-bottom:1px;font-size:16px;line-height:21px;color:#f79c33;font-weight:bold;text-decoration:none;">ENTER THE THEATER</a></p>	
		</div>
		<div style="float:left;padding:20px 0 0 25px;">
			<div style="padding-left: 35px"><img src="http://<?php echo sfConfig::get("app_domain");?>/images/family_movie_night2.png" /></div>
			<div style="padding:5px 0 5px 35px;font-size:24px;color:#f79c33;font-weight:bold;text-decoration:none;">CODE: <?php echo $code ?></div>
			<div style="margin:20px 0 0 140px"><img src="http://<?php echo sfConfig::get("app_domain");?>/images/ticket_logo.png" /></div>
		</div>
	</div>
</div>
<div>
<table>
  <tr>
    <td>
      <strong>Welcome to Family Movie Night!<br /><br />
      ROBYN LIVELY presents "Who Is Simon Miller?" on Monday, July 25 at 8pm EST, and on Tuesday July 26 at 8pm EST in a live Q+A during and after the movie!</strong> 
    </td>
  </tr>  
  <tr>
    <td>
    Here's what to do to join the screenings (or to watch the movie on-demand): 
    </td>
  </tr>
  <tr>
    <td>
      <ol>
        <li>Click "Enter the Theater" on the ticket above, or go to <a href="http://constellation.tv/whoissimonmiller">http://constellation.tv/whoissimonmiller</a>.</li>
        <li>When you get to the theater page, click on the "Get Ticket Now" button to join Robyn's screening, or "Watch By Request" to view on-demand.</li>
        <li>Enter the code you see printed on your ticket when prompted. You will be directed into the Constellation virtual movie theater, where the film will start playing when the countdown reaches "0".  In the theater you can chat with other participants both during and after the movie.</li>
      </ol>
    </td>
  </tr>
  <tr>
    <td>
      <i>Note that your ticket will only be valid from 7/25 at 10am EST through 8/5 at 10pm EST.</i>
    </td>
  </tr>
  <tr>
    <td>
      <i>Problems or questions? Send an email to <a href="support@constellation.tv">support@constellation.tv</a>. Thanks!</i>
    </td>
  </tr>
  <tr>
    <td style="color:#eee">TS-AST-<?php echo time();?>-UTC::F34</td>
  </tr>
</table>

</div>
