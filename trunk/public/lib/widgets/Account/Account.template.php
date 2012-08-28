<?php if ($sf_user -> hasFlash('success')):?>
<div class="inner_container">
  <div class="success-block">
    <?php echo $sf_user -> getFlash('success');?>
  </div>
</div>
<?php elseif ($sf_user -> hasFlash('error')):?>
<div class="inner_container">
  <div class="error-block">
    <?php echo $sf_user -> getFlash('error');?>
  </div>
</div>

<?php endif; ?>


<div class="inner_container history_inner_container clearfix" id="profile">
    <h1 class="content_title"><a href="/profile/<?php echo $user_id;?>" class="right button button-blue button-medium">View Profile</a>My Profile</h1> 

      <div class="account-avatar left ">
          <div id="user_image_original_wrapper" class="preview swfuploader">
          	<?php if ($user_photo_url != '') :?>
            	<?php if (left($user_photo_url,4) == 'http') :?>
								<!-- <img name="user_image_original_preview" id="user_image_original_preview" src="<?php echo $user_photo_url;?>" width="150" height="150" /> -->
							<?php else :?>
              <?php $user_photo_url = '/uploads/hosts/' . $user_id .'/'. $user_photo_url ;?>
								<!-- <img name="user_image_original_preview" id="user_image_original_preview" src="/uploads/hosts/<?php echo $user_id;?>/<?php echo $user_photo_url;?>" width="150" height="150" /> -->
							<?php endif;?>
          	<?php else : ?>
              <?php $user_photo_url = 'https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt/featured_still.jpg' ;?>
          		<!-- <img name="user_image_original_preview" id="user_image_original_preview" src="https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt/featured_still.jpg" width="150" height="150" /> -->
          	<?php endif; ?>																		          	

            <input type="hidden" name="FILE_user_image_original" value="<?php echo $user_photo_url;?>" id="image-upload" >
					</div>
      </div>
      <div class="account-form right">
      <form method="POST" action="/account" id="account-form">
  <div class="form-fieldset">
          <div class="form-row">
              <label for="email">E-mail Address</label>
              <div class="input-static">
              <span class="input-static-text span4"><?php echo $user_email;?></span>
              <input type="hidden" name="user_email" id="user_email" value="<?php echo $user_email;?>" />
              <span class="link" id="update-email-button">(change e-mail)</span>
            </div>
          </div>
          <div class="clear sub-form-row"  id="email-sub-form" style="display: none">
            <div class="form-row">
              <label class="sublabel" class="sublabel">New E-mail Address</label>
              <div class="input">
              <input  class="text-input span4" type="text" name="user_email_new" id="user_email_new" disabled="disabled" />
            </div>
            </div>
          </div>
    <div class="form-row hr edit-break"></div>
          <div class="form-row">
              <label for="first_name">Nickname</label>
              <div class="input">
              <input class="text-input span4" type="text" name="user_username" id="user_username" value="<?php echo $user_username;?>" />
              </div>
          </div>
          <div class="form-row">
              <label for="last_name">First Name</label>
              <div class="input">
              <input  class="text-input span4" type="text" name="user_fname" id="first_name" value="<?php echo $user_fname;?>" />
            </div>
          </div>
          <div class="form-row">
              <label for="last_name">Last Name</label>
              <div class="input">
              <input  class="text-input span4" type="text" name="user_lname" id="last_name" value="<?php echo $user_lname;?>" />
            </div>
          </div>
          <div class="form-row">
              <label for="state">Timezone</label>
              <div class="input">
                <select id="tzSelectorPop" name="tzSelectorPop">
                <?php foreach (array_keys($zones) as $zone) {?>
                  <optgroup label="<?php echo strtoupper($zone);?>">
                    <?php foreach($zones[$zone] as $key => $atz) {?>
                      <option value="<?php echo $key;?>" <?php if ($current_timezone == $key) {?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $atz;?></option>
                    <?php } ?>
                  </optgroup>
                <?php } ?>
                </select>
              </div>
          </div>

<?php if(empty($user_fb_uid) && empty($user_t_uid)):?>
          <div class="form-row hr edit-break"></div>
          <div class="form-row">
              <label for="email">Password</label>
              <div class="input-static">
                  &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
              <input  class="text-input span4" type="hidden" name="user_email" id="user_email" value="<?php echo $user_email;?>" />
              <span class="link "  id="update-password-button">(change password)</span>
            </div>
          </div>
          <div class="clear sub-form-row" id="password-sub-form" style="display: none">
          <div class="form-row form_row_no_margin">
              <label>Current Password</label>
              <div class="input">
              <input  class="text-input span4" type="password" name="current_password" id="current_password" disabled="disabled"/>
            </div>
          </div>
          <div class="form-row form_row_no_margin">
              <label class="sublabel" class="sublabel">New Password</label>
    <div class="input">
              <input  class="text-input span4" type="password" name="new_password" id="new_password" disabled="disabled"/>
            </div>
          </div>
					<div class="form-row">
              <label class="sublabel" class="sublabel">Confirm Password</label>
            <div class="input">
              <input  class="text-input span4" type="password" name="new_password_confirm" id="new_password_confirm" disabled="disabled"/>
            </div>
          </div>
        </div>
        <?php endif;?>
          <div class="form-row">
              <label for="tagline">Tagline</label>
              <div class="input">
              <input  class="text-input span4" type="text" name="user_tagline" id="tagline" value="<?php echo $user_tagline;?>" />
            </div>
          </div>          <div class="form-row">
              <label for="email">About Me</label>
              <div class="input">
              <textarea name="user_bio" id="user_bio" class="text-input span4"><?php echo $user_bio;?></textarea>
            </div>
          </div>
          <div class="form-row">
              <label for="last_name">Facebook URL</label>
              <div class="input input-prefix-wrap">
                  <input class="text-input span6" type="text" name="user_facebook_url" id="user_facebook_url" value="<?php echo $user_facebook_url;?>" />
                  <span class="input-prefix" onclick="jQuery(this).prev().focus()">http://www.facebook.com/</span>
              </div>
          </div>
          <div class="form-row">
              <label for="last_name">Twitter URL</label>
              <div class="input input-prefix-wrap">
              <input  class="text-input span6" type="text" name="user_twitter_url" id="user_twitter_url" value="<?php echo $user_twitter_url;?>" />
                  <span class="input-prefix" onclick="jQuery(this).prev().focus()">http://www.twitter.com/</span>
              </div>
          </div>
          <div class="form-row">
              <label for="last_name">Website URL</label>
              <div class="input input-prefix-wrap">
              <input  class="text-input span6" type="text" name="user_website_url" id="user_website_url" value="<?php echo $user_website_url;?>" />
              <span class="input-prefix" onclick="jQuery(this).prev().focus()">http://</span>
              </div>              
          </div>

          <input type="hidden" name="styroname" value="account" />
      </div>
      <div class="clearfix">
              <button type="submit" class="button button-green  right uppercase">Save Settings</button>
            </div>
          </form>

  </div>
</div>
</div>
<script>

require(['CTV/Controller/Profile','/js/CTV.Uploader.js','/js/swfupload/swfupload.js'], function(profile){

    new profile();

    new CTV.Uploader ({
      domNode: $('#image-upload'),
      upload_url: "/services/ImageManager/user/<?php echo $sf_user->getAttribute('user_id');?>?constellation_frontend=<?php echo session_id();?>",
      debug: false,
      file_size_limit: '100 MB',
      isFilm: false,
      uploadFolder : 'hosts',
      thumbWidth: '160px',
      fieldName: 'FILE_user_image_original',
      dialogTitle: 'Select a File'
  });
});
// user/"+$("#user_id").html()+'?constellation_frontend='+$("#session_id").html()
</script>
<?php /*if ($sf_user -> hasFlash('error')) {?>
<script type="text/javascript">
	// $(document).ready(function(){
	// 	error.showError("error","<?php echo $sf_user -> getFlash('error');?>","");
	// });
</script>
<?php }*/ ?>
<!-- <div id="session_id" class="reqs"><?php echo session_id();?></div>
<div id="user_id" class="reqs"><?php echo $user_id;?></div> -->
