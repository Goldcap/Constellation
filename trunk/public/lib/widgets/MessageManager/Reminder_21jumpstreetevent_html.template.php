<?php  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");?>
<div id="ctv_wrap" style="background: #ebebeb;height: 100%;padding: 20px;font-family: Helvetica, Arial, Sans-serif;font-size: 14px;line-height: 20px;color: #333;">
<table cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td valign="top" style="border-collapse: collapse;"> 
    <table cellpadding="0" cellspacing="0" border="0" align="left" class="ctv_container" style="width: 620px;background: #ffffff;">
      <tr>
        <td valign="top" style="border-collapse: collapse;">
          <table cellpadding="0" cellspacing="0" border="0" align="left" class="ctv_header" style="background: #000;width: 620px;">
            <tr>
              <td style="border-collapse: collapse;"><a href="http://www.constellation.tv?rf=4f1445b5df7ea6-82388721" style="color: #2d98dc;"><img src="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/email/header_logo.png" class="ctv_img_block" title="Constellation" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;display: block;border: none;margin-bottom: 10px;"></a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="border-collapse: collapse;">
          <div class="ctv_reminder" style="padding: 20px;font-size: 30px;line-height: 34px;text-align: center;">
           A friendly reminder that your showtime of <?php echo $screening['screening_film_name'];?> begins at <?php echo date("g:i A (T)",strtotime($screening['screening_date'])) ;?> today.
          </div>
        </td>
      </tr>
      <tr>
        <td valign="top" class="ctv_content_container" style="border-collapse: collapse;padding: 20px;">
          <table cellpadding="0" cellspacing="0" border="0" class="ctv_content" style="width: 100%;">
            <tr>
              <td style="border-collapse: collapse;">

                <div class="ctv_theater" style="margin: 10px 0;padding: 20px 0;border-bottom: solid 1px #c0c0c0;border-top: solid 1px #c0c0c0;text-align: center;">
                  <a href="http://<?php echo  sfConfig::get("app_domain");?>/theater/<?php echo $screening['screening_unique_key'] ;?>" style="color: #2d98dc;font-size: 30px;line-height: 30px;font-weight: 200;text-decoration: none;">ENTER THE EVENT &raquo;</a>
                </div>
            </td></tr>
          </table>
        </td>
      </tr>
      <tr>
        <td valign="top" class="ctv_hero_container" style="border-collapse: collapse;">
           <table cellpadding="0" cellspacing="0" border="0" class="ctv_hero" background="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/email/hero.jpg" bgcolor="#000000" style="width: 100%;color: #fff;">
            <tr>
              <td class="ctv_hero_poster" style="border-collapse: collapse;width: 160px;text-align: center;padding: 20px;">
                <img src="http://constellation.tv/uploads/screeningResources/<?php echo $screening['screening_film_id'];?>/logo/purchase_email_poster<?php echo $screening['screening_film_logo'];?>" align="left" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;display: block;">
              </td>
              <td class="ctv_hero_details" style="border-collapse: collapse;">
                <h2 class="ctv_hero_details_h2" style="color: #fff;font-size: 24px;line-height: 40px;margin: 10px 0;"><?php echo $screening['screening_film_name'];?></h2>
                <?php if($screening['screening_user_full_name'] != ''):?>
                <p class="ctv_hero_details_p ctv_hero_details_host" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;margin-bottom: 20px;"><img src="<?php echo getShowtimeUserAvatar($film,"medium")?>
                " width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;display: inline;margin-right: 10px;vertical-align: middle;">Hosted by <?php echo $screening['screening_user_full_name'];?></p>
                <?php endif;?>
                <p class="ctv_hero_details_p" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;">
                <?php echo date("l, F dS Y",strtotime($screening['screening_date'])) ;?></p>
                <p class="ctv_hero_details_p" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;"><?php echo date("g:i A (T)",strtotime($screening['screening_date'])) ;?> (<?php echo $screening['screening_default_timezone_id'];?>)</p>
                <p class="ctv_hero_details_p" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;">EXCHANGE CODE: <?php echo $item->getAudienceInviteCode();?> </p>
                                  
              </td></tr>
          </table>
        </td>
      </tr>

      <tr>
        <td valign="top" style="border-collapse: collapse;">
          <table cellpadding="0" cellspacing="0" border="0" align="left" class="ctv_footer" background="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/email/footer_shadow.png" width="100%">
            <tr>
              <td style="border-collapse: collapse;">
                <div class="ctv_footer_links" style="margin: 10px;padding: 10px 0;font-size: 12px;text-align: center;">
                [!--UNSUBSCRIBE--] | [!--PROFILE--]
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</div>