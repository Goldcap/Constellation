<div id="invite_email_lb" class="pops pops_with_close" style="display:none">
  <div class="form_default form_invite_email">
      <h4 class="form_legend">Invite friends via e-mail</h4>
      <div class="form_row">
          <label>To</label>
          <textarea id="form_invite_email_to" name="to" class="form_invite_email_to"></textarea>
          <p class="sublabel">Comma seperated e-mail addresses</p>
      </div>
      <div class="form_row">
          <label>Subject</label>
          <input id="form_invite_email_subject" type="text" name="subject"/>
      </div>
      <div class="form_row">
          <label>Body <span id="invite-textbox-limit"></span></label>
          <textarea id="form_invite_email_body" name="body" class="form_invite_email_body"></textarea>
      </div>
      <div class="form_row">
				<div id="contacts-error-area" style="color: red"></div>			
      </div>
			<div class="form_row cleafix">
          <button id="btn-invite" type="submit" class="button button_orange uppercase right">Send Invitations</button>
      </div>
  </div>
  <div class="lb_close"></div>
</div>


<div id="reminder_lb" class="pops pops_with_close" style="display:none">
  <div class="form_default form_invite_email">
      <h4 class="form_legend">Send me a reminder</h4>
      <p class="sublabel">We'll send you a reminder 2 hours before the show</p>
      <div class="form_row">
          <label>Email</label>
          <input id="form_reminder_email" type="text" name="email"/>
      </div>
			<div class="form_row cleafix">
          <button id="reminder-button" type="submit" class="button button_orange uppercase right">Send Reminder</button>
      </div>
  </div>
  <div class="lb_close"></div>
</div>

<div id="invite_facebook_lb" class="pops pops_with_close invite_facebook_lb" style="display: none">
  <form action="#" id="invite_facebook">
	<div class="platform_dialog_header_container">
		<div class="platform_dialog_icon"></div>
		<div class="platform_dialog_header">Send your friends an invitation!</div>
		<a class="fb_dialog_close_icon"></a>
	</div>
	<div class="pam filterBoxAlignment uiBoxGray bottomborder">
		<table width="100%">
			<tr>
				<td width="60" valign="top">
					<div class="UIImageBlock_Content UIImageBlock_ICON_Content">Message:</div>
				</td>
				<td>
					<textarea id="fb_invite_message" style="width: 460px" rows="2" name="message"></textarea>
				</td>
			</tr>
		</table>
	</div>
  <div class="pam filterBoxAlignment uiBoxGray bottomborder">
		<div class="UIImageBlock clearfix request_preview">
			<img id="fb_share_user_image" alt="" src="https://fbcdn-photos-a.akamaihd.net/photos-ak-snc1/v43/202/185162594831374/app_1_185162594831374_1331.gif" class="uiProfilePhoto UIImageBlock_Image UIImageBlock_ICON_Image uiProfilePhotoLarge img">
				<div class="UIImageBlock_Content UIImageBlock_ICON_Content">
					<div class="fwb_parent">
						<span class="fwb">Preview of invitation:</span>
					</div>
					<div class="UIImageBlock clearfix mts request_preview">
						<img id="fb_share_preview_image" alt="" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/spacer.gif" class="uiProfilePhoto UIImageBlock_Image UIImageBlock_ICON_Image uiProfilePhotoMedium img">
						<div id="fb_share_preview_text" class="UIImageBlock_Content UIImageBlock_ICON_Content">Check Out Constellation.tv!</div>
					</div>
				</div>
		</div>
	</div>
	
	<div class="pam filterBox uiBoxGray topborder">
		<table>
			<tbody>
				<tr>
					<td>
					<div id="uvkl4e_9" class="uiSelector inlineBlock selector uiSelectorNormal uiSelectorDynamicLabel">
						<div class="wrap">
							<a rel="toggle" data-length="30" aria-haspopup="1" href="#" role="button" class="uiSelectorButton uiButton">
								<span class="uiButtonText all_friends">All Friends</span>
							</a>
							<!--<div class="uiSelectorMenuWrapper uiToggleFlyout">
								<div class="uiMenu uiSelectorMenu" role="menu">
									<ul class="uiMenuInner">
										<li data-label="All Friends" class="uiMenuItem uiMenuItemRadio uiSelectorOption checked"><a data-typeahead="1" href="#" aria-checked="true" tabindex="0" role="menuitemradio" class="itemAnchor"><span class="itemLabel fsm">All Friends</span></a></li>
										<li data-label="constellation.tv Users" class="uiMenuItem uiMenuItemRadio uiSelectorOption"><a data-listendpoint="/ajax/chooser/list/friends/app_user/?app_id=185162594831374&amp;is_game=0" data-typeahead="1" href="#" aria-checked="false" tabindex="-1" role="menuitemradio" class="itemAnchor"><span class="itemLabel fsm">constellation.tv Users</span></a></li>
										<li data-label="Friends to Invite" class="uiMenuItem uiMenuItemRadio uiSelectorOption"><a data-listendpoint="/ajax/chooser/list/friends/app_non_user/?app_id=185162594831374&amp;is_game=0" data-typeahead="1" href="#" aria-checked="false" tabindex="-1" role="menuitemradio" class="itemAnchor"><span class="itemLabel fsm">Friends to Invite</span></a></li>
										<li class="uiMenuSeparator"></li>
										<li data-label="Selected" class="uiMenuItem uiMenuItemRadio uiSelectorOption">
										<a href="#" aria-checked="false" tabindex="-1" role="menuitemradio" class="itemAnchor">
										<span class="itemLabel fsm">Selected</span>
										</a>
										</li>
									</ul>
								</div>
							</div>-->
						</div>
						
						<!--<select><option value=""></option>
						<option selected="1" value="default">All Friends</option>
						<option value="app_users">constellation.tv Users</option>
						<option value="app_non_users">Friends to Invite</option>
						<option value="selected">Selected</option>
						</select>-->
					</div>
					</td>
					<!--<td class="fullWidth">
						<div id="uvkl4e_5" class="uiTypeahead uiClearableTypeahead fbProfileBrowserTypeahead">
							<div class="wrap">
								<label for="uvkl4e_10" class="clear uiCloseButton">
									<input type="button" id="uvkl4e_10" onclick="var c = JSCC.get('j4ea5c00f0ee2a03709082113').getCore(); c.reset(); c.getElement().focus(); " title="Remove">
								</label>
								<input type="hidden" name="profileChooserItems" class="hiddenInput" autocomplete="off" value="{&quot;621192166&quot;:1}">
								<div class="innerWrap"><input type="text" title="Search..." value="Search..." spellcheck="false" onfocus="return wait_for_load(this, event, function() {JSCC.get('j4ea5c00f0ee2a03709082113').init([]);;;});" autocomplete="off" placeholder="Search..." class="inputtext textInput DOMControl_placeholder"></div>
							</div>
						</div>
					</td>-->
				</tr>
			</tbody>
		</table>
	</div>
	
	<div id="fb_in">
		<span id="fb_user_list">
			<!--<input type="checkbox" onclick="toggleChecked(this.checked)"> Select / Deselect All-->
			
		</span>
	</div>
	
	<div id="platform_dialog_bottom_bar" class="platform_dialog_bottom_bar">
		<div class="platform_dialog_bottom_bar_border_lr">
			<div class="platform_dialog_bottom_bar_border">
					<div class="platform_dialog_buttons">
						<label for="uvkl4e_7" id="ok_clicked" class="uiButton uiButtonConfirm uiButtonLarge"><input type="submit" id="uvkl4e_7" onclick="" name="ok_clicked" value="Send Requests"></label>
						<label for="uvkl4e_8" id="cancel_clicked" class="uiButton uiButtonLarge"><input type="submit" id="uvkl4e_8" onclick="" name="cancel_clicked" value="Cancel"></label>
					</div>
			</div>
		</div>
	</div>
	</form>
</div>

<div class="pops" id="preview-invite-popup" style="display: none">		
  <div class="close-bar">	
    <a id="close-preview-invite-popup">(close)</a>		
  </div>		
  <div class="invite-content">		
  </div>	
</div>

<div id="invite_screening"></div>
