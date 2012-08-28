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
        <td valign="top" class="ctv_hero_container" style="border-collapse: collapse;">
          <table cellpadding="0" cellspacing="0" border="0" class="ctv_hero" background="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/email/hero.jpg" bgcolor="#000000" style="width: 100%;color: #fff;">
            <tr>
              <td class="ctv_hero_poster" style="border-collapse: collapse;width: 160px;text-align: center;padding: 20px;">
                <img src="http://constellation.tv/uploads/screeningResources/<?php echo $film['film_id'];?>/logo/purchase_email_poster<?php echo $film['film_logo'];?>" align="left" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;display: block;">
              </td>
              <td class="ctv_hero_details" style="border-collapse: collapse;">
                <h2 class="ctv_hero_details_h2" style="color: #fff;font-size: 24px;line-height: 40px;margin: 10px 0;"><?php echo $film['film_name'];?></h2>
                <p class="ctv_hero_details_p" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;">
               Hosted by You</p>
                <p class="ctv_hero_details_p" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;">
               Now</p>
                <p class="ctv_hero_details_p" style="margin: 2px 0 0;font-size: 16px;line-height: 24px;color: #fff;font-weight: 200;">EXCHANGE CODE: <?php echo $item->getAudienceInviteCode();?> </p>
              </td></tr>
          </table>
        </td>
      </tr>
      <tr>
        <td valign="top" class="ctv_content_container" style="border-collapse: collapse;padding: 20px;">
          <table cellpadding="0" cellspacing="0" border="0" class="ctv_content">
            <tr>
              <td style="border-collapse: collapse;">
  
                <table class="ctv_purchase_summary">
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Name</th>
                    <td style="border-collapse: collapse;"><?php echo $order->getPaymentFirstName();?> <?php echo $order->getPaymentLastName();?></td>
                  </tr>
                  <?php if($order->getPaymentCardType() == 'Not A Valid' || $order->getPaymentAmount() ==0):?>
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Amount</th>
                    <td style="border-collapse: collapse;">Free</td>
                  </tr>
                  <?php else:?>
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Card Type</th>
                    <td style="border-collapse: collapse;"><?php echo $order->getPaymentCardType();?></td>
                  </tr>
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Card Type</th>
                    <?php if( $order->getPaymentCardType() =='Paypal'):?>
                    <?php elseif( $order->getPaymentCardType() =='American Express'):?>
                    <td style="border-collapse: collapse;">****-*****-*<?php echo $order->getPaymentLastFourCCDigits();?></td>
                    <?php else:?>
                    <td style="border-collapse: collapse;">****-****-****-<?php echo $order->getPaymentLastFourCCDigits();?></td>
                    <?php endif;?>
                  </tr>
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Amount</th>
                    <td style="border-collapse: collapse;">$<?php echo $order->getPaymentAmount();?></td>
                  </tr>
                  <?php endif;?>
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Screening</th>
                    <td style="border-collapse: collapse;"><?php echo $film['film_name'];?>" Now</td>
                  </tr>
                  <tr>
                    <th style="width: 120px;text-align: right;padding-right: 10px;">Screening URL</th>
                    <td style="border-collapse: collapse;"><a href="<?php echo  sfConfig::get("app_domain");?>/theater/<?php echo $item->getFKScreeningUniqueKey()?>" style="color: #2d98dc;">http://www.constellation.tv/theater/<?php echo $item->getFKScreeningUniqueKey();?></a></td>
                  </tr>                 
                </table>
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
    </table>
    </td>
  </tr>
</table>
</div>