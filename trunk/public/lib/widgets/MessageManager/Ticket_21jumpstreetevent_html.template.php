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
        <td style="padding: 20px 0; text-align: center; background: #000">
          <img src="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/21jumpstreet/age-header.png" width="560" style="margin: auto"/>
        </td>
      </tr>
      <tr>
        <td valign="top" class="ctv_content_container" style="border-collapse: collapse;padding: 20px;">
          <table cellpadding="0" cellspacing="0" border="0" class="ctv_content">
            <tr>
              <td style="border-collapse: collapse;">
                <div class="ctv_description" style="margin-bottom: 20px;font-size: 18px;line-height: 24px;">
                  Congratulations! This is your ticket to High School Confidential the live online event with Jonah Hill and Channing Tatum presenting fan-submitted high school confessions and exclusive footage from their upcoming movie, 21 Jump Street.
                </div>
                                <div class="ctv_theater" style="margin: 10px 0;padding: 20px 0;border-bottom: solid 1px #c0c0c0;border-top: solid 1px #c0c0c0;text-align: center;">
                  <a href="http://<?php echo  sfConfig::get("app_domain");?>/theater/21jumpstlive" style="color: #2d98dc;font-size: 30px;line-height: 30px;font-weight: 200;text-decoration: none;">ENTER THE EVENT &raquo;</a>
                </div>
                <p style="margin: 1em 0;">The event will take place on <?php echo date("F dS",strtotime($film['screening_date'])) ;?> at <?php echo date("g:i A (T)",strtotime($film['screening_date'])) ;?> (<?php echo $film['screening_default_timezone_id'];?>). You will receive an email reminder 6 hours before the live event. </p>
                <p style="margin: 1em 0;">Share the details of the event with your friends, submit your high school stories, and vote for your favorites at www.constellation.tv/21jumpstreet. </p>
               
              </td><td style="border-collapse: collapse;">
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
      <tr>
        <td style="color:#eee">TS-AST-<?php echo time();?>-UTC::F20</td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</div>