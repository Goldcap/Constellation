<div style="width:676px;height:263px;font-family:'Helvetica Neue',Helvetica,Arial,Verdana,sans-serif;color:#fff;background-image:url('http://<?php echo sfConfig::get("app_domain");?>/images/ticketbg_walmart.png');background-repeat:no-repeat;background-position:left top;background-color:#040027;">
	<div style="width:661px;padding:0 0 0 15px;overflow:hidden;">
		<img style="float:left;color:#aaa;margin-top:18px;width:141px;height:209px;display:block;" src="http://<?php echo sfConfig::get("app_domain");?>/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/small_poster<?php echo $film["film_logo"];?>" title="<?php echo $film["film_name"];?>" alt="<?php echo $film["film_name"];?>" />
		<div style="float:left;width:200px;height:214px;overflow:hidden;padding:16px 0 33px 17px;">
			<p style="margin:0;padding:0;font-size:28px;font-weight:bold;color:#9dcefb"><span style="border-bottom:solid 1px">ADMIT ONE</span></p>
			<p style="margin:0;padding:10px 0 0;font-size:22px;font-weight:bold;color:#9dcefb"><?php echo strtoupper($film["film_name"]) ?></p>
			<p style="margin-top: 90px"><a href="http://<?php echo sfConfig::get("app_domain");?>/film/<?php echo $film["film_id"];?>" style="margin-bottom:1px;font-size:16px;line-height:21px;color:#f79c33;font-weight:bold;text-decoration:none;">ENTER THE THEATER</a></p>	
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
      <strong>Welcome to Family Movie Night, with Nathan Kress presenting GAME OF YOUR LIFE on Monday, 11/21, at 8pm EST.</strong> 
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
        <li>Click "Enter the Theater" on the ticket above, or go to <a href="http://www.constellation.tv/gameofyourlife">http://www.constellation.tv/gameofyourlife</a>.</li>
        <li>When you get to the page, click on the "Join" button next to the showtime of your choice.</li>
        <li>Enter the code you see printed on your ticket.  You will be directed into the Constellation virtual movie theater, where the film will start playing when the countdown reaches "0".</li>
      </ol>
    </td>
  </tr>
  <tr>
    <td>
      <i>Note that your ticket will only be valid beginning on Monday, 11/21.</i>
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
