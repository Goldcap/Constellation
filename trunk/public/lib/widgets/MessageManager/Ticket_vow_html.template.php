<?php 
  $theurl = "http://".sfConfig::get("app_domain")."/theater/".$item->getFkScreeningUniqueKey()."/".$item->getAudienceInviteCode();
?>
<table width="676" height="263" border="0" background="http://<?php echo sfConfig::get("app_domain");?>/images/ticket/constellation_ticket_bg.jpg">
  <tr>
    <td width="5"></td>
    <td width="148">
      <a href="<?php echo $theurl;?>"><img src="http://<?php echo sfConfig::get("app_domain");?>/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/purchase_email_poster<?php echo $film["screening_film_logo"];?>" border="0" /></a>
    </td>
    <td width="214" valign="top">
      <table height="263" width="204" border="0">
        <tr>
          <td height="10">&nbsp;</td>
        </tr>
        <tr>
          <td height="26">
            <a href="<?php echo $theurl;?>" style="text-decoration: none">
              <font color="#95BFF1" size="5" face="sans-serif">
              ADMIT ONE
              </font>
            </a>
          </td>
        </tr>
        <tr>
          <td height="10">
            <img src="http://<?php echo sfConfig::get("app_domain");?>/images/ticket/constellation_ticket_hr.png" />
          </td>
       </tr>
        <tr>
           <td height="55" valign="top">
            <a href="<?php echo $theurl;?>" style="text-decoration: none">
            <font color="#95BFF1" size="5" face="sans-serif">
              An Evening of Vows
            </font>
            </a>
          </td>
        </tr>
        <tr>
          <td height="70">
            &nbsp;&nbsp;
            <a href="<?php echo $theurl;?>" style="text-decoration: none">
            <font color="#ffffff" size="1" face="sans-serif">
            <strong>
            DATE: 02-09-2012<br />
            </strong>
            </font>
            </a>
            &nbsp;&nbsp;
            <a href="<?php echo $theurl;?>" style="text-decoration: none">
            <font color="#ffffff" size="1" face="sans-serif">
            <strong>
            TIME: 8:00 PM (America/New York)<br />
            </strong>
            </font>
            </a>
            &nbsp;&nbsp;
            <a href="<?php echo $theurl;?>" style="text-decoration: none">
            <font color="#ffffff" size="1" face="sans-serif">
            <strong>
            EXCHANGE CODE: <?php echo $item->getAudienceInviteCode();?>
            </strong>
            </font>
            </a>
          </td>
        </tr>
        <tr>
          <td>
          <a href="<?php echo $theurl;?>"><img src="http://<?php echo sfConfig::get("app_domain");?>/images/ticket/constellation_enter_theater.png" border="0" /></a>
          </td>
        </tr>
      </table>
    </td>
    <td width="216">
      <table height="263" width="216" border="0">
        <tr>
          <td height="130">
            <?php if ($film["screening_guest_image"] != '') {
            if (left($film["screening_guest_image"],4) == "http") {?>
              <img class="host" height="141" src="<?php echo $film["screening_guest_image"];?>" alt="host photo" />
            <?php } else { ?>
              <img class="host" height="141" src="http://<?php echo sfConfig::get("app_domain");?>/uploads/hosts/<?php echo $film["screening_guest_image"];?>" alt="host photo" />
            <?php }} elseif ($film["screening_still_image"] != '') {?>
              <img class="host" height="141" src="http://<?php echo sfConfig::get("app_domain");?>/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/screenings/film_screening_large_<?php echo $film["screening_still_image"];?>" />			
            <?php } else {?>
    			    <img src="http://<?php echo sfConfig::get("app_domain");?>/images/constellation_host.jpg" alt="screening host" height="120" />
    			  <? } ?>
          </td>
        </tr>
        <tr>
          <td height="12" valign="top">
            <font color="#95BFF1" size="1" face="sans-serif">
              <?php if($film["screening_user_full_name"] != '') {?><strong>HOST: <?php echo strtoupper($film["screening_user_full_name"]);?></strong><?php } ?>
            </font>
          </td>
        </tr>
        <tr>
          <td valign="top">
            <font color="#ffffff" size="1" face="sans-serif">
            <?php if ($film["screening_description"] != '') {?>
              <?php echo strip_tags($film["screening_description"],"<p>");?>
            <?php } else { ?>
              <?php echo substr($film["screening_film_info"],0,255);?><?php if (strlen($film["screening_film_info"]) > 255) { echo "..."; } ?>
            <?php } ?>
            </font>
          </td>
        </tr>
        <tr>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td></td>
  </tr>
</table>

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
	     <td>Ticket Price :</td>
	     <td>FREE</td>
	   </tr>
	   <tr>
	     <td>Screening :</td>
	     <td>"<?php echo $film["screening_film_name"];?>" on <?php echo formatDate($film["screening_date"],"MDY")?> at <?php echo formatDate($film["screening_date"],"time")?> <?php echo $film["screening_default_timezone_id"];?></td>
	   </tr>
	   <tr>
	     <td>Screening URL :</td>
	     <td>http://<?php echo sfConfig::get("app_domain");?>/theater/<?php echo $item->getFkScreeningUniqueKey();?>/<?php echo $item->getAudienceInviteCode();?></td>
	   </tr>
      <tr>
        <td colspan="2" style="color:#eee">TS-AST-<?php echo time();?>-UTC::F20</td>
      </tr>
	</table>
</div>
