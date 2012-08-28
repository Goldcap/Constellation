<div style="width:676px;height:263px;font-family:'Helvetica Neue',Helvetica,Arial,Verdana,sans-serif;color:#fff;background-image:url('http://<?php echo sfConfig::get("app_domain");?>/images/ticketbg_walmart.png');background-repeat:no-repeat;background-position:left top;background-color:#040027;">
	<div style="width:661px;padding:0 0 0 15px;overflow:hidden;">
		<img style="float:left;color:#aaa;margin-top:18px;width:141px;height:209px;display:block;" src="http://<?php echo sfConfig::get("app_domain");?>/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/small_poster<?php echo $film["film_logo"];?>" title="<?php echo $film["film_name"];?>" alt="<?php echo $film["film_name"];?>" />
		<div style="float:left;width:200px;height:214px;overflow:hidden;padding:16px 0 33px 17px;">
			<p style="margin:0;padding:0;font-size:28px;font-weight:bold;color:#9dcefb"><span style="border-bottom:solid 1px">ADMIT ONE</span></p>
			<p style="margin:0;padding:10px 0 0;font-size:22px;font-weight:bold;color:#9dcefb"><?php echo strtoupper($film["film_name"]) ?></p>
			<p style="margin:40px 0 0 3px;font-size:11px;line-height:20px;font-weight:bold;color:#fff;">DATE: <?php echo formatDate($item -> getScreeningDate(),"MDY");?></p>
			<p style="margin:0 0 0 3px;font-size:11px;line-height:20px;font-weight:bold;color:#fff;">TIME: <?php echo formatDate($item->getScreeningTime(),"time")?> (<?php echo formatDate($item->getScreeningTime(),"T") ?>)</p>
			<p style="margin:0 0 10px 3px;font-size:11px;line-height:20px;font-weight:bold;color:#fff;">EXCHANGE CODE: <?php echo $item->getScreeningUniqueKey();?></p>
			<a href="http://<?php echo sfConfig::get("app_domain");?>/theater/<?php echo $item->getScreeningUniqueKey();?>/<?php echo $ticket->getAudienceInviteCode();?>" style="margin-bottom:1px;font-size:16px;line-height:21px;color:#f79c33;font-weight:bold;text-decoration:none;">ENTER THE THEATER</a>
		</div>
		<div style="float:left;padding:80px 0 0 25px;">
			<img src="http://<?php echo sfConfig::get("app_domain");?>/images/family_movie_night.png" />
			<ul style="list-style-type:none;margin:60px 0 0;padding:0;overflow:hidden;">
				<li style="float:left;margin-right:3px;"><a href="http://<?php echo sfConfig::get("app_domain");?>/services/Ical/<?php echo $item->getScreeningUniqueKey();?>"><img src="http://<?php echo sfConfig::get("app_domain");?>/images/icon-calendar-larger.png" style="border:0;" alt="ICAL" width="18" height="18" /></a></li>
			</ul>
			<img style="margin-left:140px" src="http://<?php echo sfConfig::get("app_domain");?>/images/ticket_logo.png" />
		</div>
	
		</div>
</div>
<div style="margin-top:20px;font-family:'Helvetica Neue',Helvetica,Arial,Verdana,sans-serif;font-size:14px;">
	<table width="100%">
	   <tr>
	     <td>Name :</td>
	     <td>
       <?php if ($user -> getUserFullName() != ''){
          echo $user -> getUserFullName();
        } else {
          echo $user -> getUserFullName();
        }?>
      </td>
	   </tr>
	   <tr>
	     <td>Screening :</td>
	     <td>"<?php echo $film["film_name"];?>" on <?php echo formatDate($item->getScreeningDate(),"MDY")?> at <?php echo formatDate($item->getScreeningTime(),"time")?> (<?php echo formatDate($item->getScreeningTime(),"T") ?>)</td>
	   </tr>
	   <tr>
	     <td>Screening URL :</td>
	     <td>http://<?php echo sfConfig::get("app_domain");?>/theater/<?php echo $item->getScreeningUniqueKey();?>/<?php echo $ticket->getAudienceInviteCode();?></td>
	   </tr>
	</table>
</div>
