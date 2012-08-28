<?php require 'includes/head.php'; ?>

<link href="/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/css/fileuploader.css" rel="stylesheet" type="text/css" />

    <?php require 'partials/header.php' ?>
    
    <div id="content" class="host_show content">
        <div class="inner_container clearfix">
    
            <div class="host_show_header">
                <h3>Account Settings</h3>

            </div>
            
            <div class="host_container">
                <div class="host_container_top"></div>
                <div class="host_container_center clearfix">
                    <div class="account_avatar_block">
                        <p class="uppercase label">Image</p>
                        <div class="preview">
                        	<img id="thumb" src="https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt/featured_still.jpg" width="150" height="150" />
                        </div>
						<div id="avatar-input" class="left">
						    <input type="file" name="avatar" id="avatar-upload"/>
						    <span class="button button_small">Choose File</span>
						    <span id="avatar_account_name">No File selected</span>
						</div>
    						
                    </div>
                    <div class="host_form account_form">
                    <form>
                        <div class="form_row">
                            <label for="first_name">Nickname</label>
                            <input type="text" name="username" id="first_name"/>
                        </div>
                        <div class="form_row">
                            <label for="last_name">First &amp; Last Name</label>
                            <input type="text" name="name" id="last_name"/>
                        </div>
                        <div class="form_row">
                            <label for="email">E-mail Address</label>
                            <input type="text" name="email" id="email" disabled/> <span class="link uppercase">Edit</span>
                        </div>
                        <div style="height:0; overflow:hidden;">
                        <div class="form_row">
                            <label class="sublabel" class="sublabel">Confirm</label>
                            <input type="text" name="password" id="password"/>
                            <p class="edit_email_link_wrap"><span class="link uppercase">Save Password</span></p>
                        </div>
                        <div class="form_row form_row_no_margin">
                            <label>Password</label>
                            <label for="password_current" class="sublabel">Current</label>
                            <input type="text" name="password_current" id="password_current"/>
                        </div>
                        <div class="form_row form_row_no_margin">
                            <label class="sublabel" class="sublabel">New Password</label>
                            <input type="text" name="password_new" id="password_new"/>
                        </div>
                        </div>
                        <div class="form_row">
                            <label class="sublabel" class="sublabel">Confirm</label>
                            <input type="text" name="password" id="password"/>
                            <p class="edit_email_link_wrap"><span class="link uppercase">Save Password</span></p>
                        </div>
                        <div class="form_row">
                            <label for="state">Timezone</label>
                            <div class="selectOveride">
                                <div class="displayDiv">Select your timezone</div>
                            <?php include 'partials/state.php' ?>
                            </div>
                        </div>
                        <div class="form_row">
                            <button type="submit" class="button_green button uppercase">Save Settings</button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="host_container_bottom"></div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php' ?>    
    <?php include 'includes/footscripts.php' ?>
    <script src="/js/fileuploader.js" type="text/javascript"></script>  
<script>

    function onSelectChange(event){
        var $select = $(event.target);
        var text = $('option:selected', $select).html();
        $select.prev().html(text);
    }
    
    $('select').each(function(select){
        var $select = $(this);
        $select.bind('change', onSelectChange);
        
    });
    var thumb = $('img#thumb');	


	
	$('#avatar-upload').bind('change', function(){
	    $('#avatar_account_name').html($(this).val());
	})
</script>

</body> 
</html> 
