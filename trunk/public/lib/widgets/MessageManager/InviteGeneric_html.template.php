<div id="ctv_wrap" style="background: #ebebeb;height: 100%;padding: 20px;font-family: Helvetica, Arial, Sans-serif;font-size: 14px;line-height: 20px;color: #333;">
<table cellpadding="0" cellspacing="0" border="0" align="center">
	<tr>
		<td valign="top" style="border-collapse: collapse;"> 
		<table cellpadding="0" cellspacing="0" border="0" align="left" class="ctv_container" style="width: 620px;background: #ffffff;">
			<tr>
				<td valign="top" style="border-collapse: collapse;">
					<table cellpadding="0" cellspacing="0" border="0" align="left" class="ctv_header" style="background: #000;width: 620px;">
						<tr>
							<td style="border-collapse: collapse;"><a href="http://www.constellation.tv?rf=<?php echo $beacon;?>" style="color: #2d98dc;"><img src="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/email/header_logo.png" class="ctv_img_block" title="Constellation" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;display: block;border: none;margin-bottom: 10px;"></a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
			</tr><tr>
				<td valign="top" class="ctv_content_container" style="border-collapse: collapse;padding: 20px;">
					<table cellpadding="0" cellspacing="0" border="0" class="ctv_content">
						<tr>
							<td style="border-collapse: collapse;">
								<div class="ctv_block" style="padding: 20px;font-size: 14px;">
									<p class="ctv_p_m" style="margin: 1em 0;font-size: 18px;">Hi there,</p>
									<p style="margin: 1em 0;"><?php echo $name;?> has invited you check out <?php echo $film['film_name'];?><?php echo !empty($film['film_makers']) ? ", directed by " .$film['film_makers'] : '';?> on Constellation. </p>
									<?php if(!empty($message)):?>
									<p><?php echo $name;?> said:</p>
									<blockquote><p><?php echo $message;?></p></blockquote>
									<?php endif;?>
									<p style="margin: 1em 0;"><a href="http://www.constellation.tv/film/<?php echo $film["film_id"];?>?rf=<?php echo $beacon;?>" style="color: #2d98dc;">Click here</a> to learn more about the movie.</p>
 
									<p style="margin: 1em 0;">See you at the movies!</p> 
									<p style="margin: 1em 0;">Thanks,</p>
									 
									<p style="margin: 1em 0;">The Constellation Team</p>
								</div>
							</td><td style="border-collapse: collapse;">
							</td><td valign="top" style="border-collapse: collapse;">								
								<img src="http://constellation.tv/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/purchase_email_poster<?php echo $film["film_logo"];?>" align="left" style="margin: 40px 20px;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;display: block;">
							</td>							
						</tr>
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