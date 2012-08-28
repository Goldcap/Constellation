<!-- Begin MailChimp Signup Form -->
<!--[if IE]>
<style type="text/css" media="screen">
#mc_embed_signup fieldset {position: relative;}
#mc_embed_signup legend {position: absolute; top: -1em; left: .2em;}
</style>
<![endif]--> 
<!--[if IE 7]>
<style type="text/css" media="screen">
.mc-field-group {overflow:visible;}
</style>
<![endif]-->

<div id="mc_embed_signup" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,.8);display: none;z-index: 10000;">
<form action="http://constellation.us2.list-manage.com/subscribe/post?u=5dcb972a18f2ca884af5f6bd9&amp;id=6c1902192e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" style="position: fixed;top: 10%;left: 50%;width: 50%;margin-left: -25%;font: normal 100% Arial, sans-serif;font-size: 10px;">
<fieldset style="-moz-border-radius: 4px;border-radius: 4px;-webkit-border-radius: 4px;border: 1px solid #ccc;padding-top: 1.5em;margin: .5em 0;background-color: #000000;color: #000;text-align: left;">
<legend style="white-space: normal;text-transform: capitalize;font-weight: bold;color: #000;background: #fff;padding: .5em 1em;border: 1px solid #1px solid #ccc_COLOR;-moz-border-radius: 4px;border-radius: 4px;-webkit-border-radius: 4px;font-size: 1.2em;"><span>join our mailing list</span></legend>
<div class="indicate-required" style="text-align: right;font-style: italic;overflow: hidden;color: #000;margin: 0 9% 0 0;">* indicates required</div>
<div class="mc-field-group" style="margin: 0 5% 0 5%;padding: 2px 0;clear: both;overflow: hidden;">
<label for="mce-EMAIL" style="display: block;margin: 15px 0 .3em 0;font-weight: bold;">Email Address <strong class="note-required">*</strong>
</label>
<input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL" style="margin-right: 1.5em;padding: .2em .3em;width: 90%;float: left;z-index: 999;">
</div>
<div id="mce-responses" style="float: left;top: -1.4em;padding: 0em .5em 0em .5em;overflow: hidden;width: 90%;margin: 0 5%;clear: both;">
<div class="response" id="mce-error-response" style="display: none;margin: 1em 0;padding: 1em .5em .5em 0;font-weight: bold;float: left;top: -1.5em;z-index: 1;width: 80%;background: FBE3E4;color: #D12F19;"></div>
<div class="response" id="mce-success-response" style="display: none;margin: 1em 0;padding: 1em .5em .5em 0;font-weight: bold;float: left;top: -1.5em;z-index: 1;width: 80%;background: #E3FBE4;color: #529214;"></div>
</div>
<div><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn" style="clear: both;margin: 1em 0 1em 5%;width: auto;display: block;"></div>
</fieldset>
<a href="#" id="mc_embed_close" class="mc_embed_close" style="background: transparent url(http://downloads.mailchimp.com/img/closebox.png) no-repeat;display: none;height: 30px;width: 30px;text-indent: -999em;position: absolute;top: 10px;right: -10px;">Close</a>
</form>
</div>
<script type="text/javascript">
var fnames = new Array();var ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';
try {
    var jqueryLoaded=jQuery;
    jqueryLoaded=true;
} catch(err) {
    var jqueryLoaded=false;
}
var head= document.getElementsByTagName('head')[0];
if (!jqueryLoaded) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
    head.appendChild(script);
    if (script.readyState && script.onload!==null){
        script.onreadystatechange= function () {
              if (this.readyState == 'complete') mce_preload_check();
        }    
    }
}
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = 'http://downloads.mailchimp.com/js/jquery.form-n-validate.js';
head.appendChild(script);
var err_style = '';
try{
    err_style = mc_custom_error_style;
} catch(e){
    err_style = 'margin: 1em 0 0 0; padding: 1em 0.5em 0.5em 0.5em; background: FFEEEE none repeat scroll 0% 0%; font-weight: bold; float: left; z-index: 1; width: 80%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: FF0000;';
}
var head= document.getElementsByTagName('head')[0];
var style= document.createElement('style');
style.type= 'text/css';
if (style.styleSheet) {
  style.styleSheet.cssText = '.mce_inline_error {' + err_style + '}';
} else {
  style.appendChild(document.createTextNode('.mce_inline_error {' + err_style + '}'));
}
head.appendChild(style);
setTimeout('mce_preload_check();', 250);

var mce_preload_checks = 0;
function mce_preload_check(){
    if (mce_preload_checks>40) return;
    mce_preload_checks++;
    try {
        var jqueryLoaded=jQuery;
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    try {
        var validatorLoaded=jQuery("#fake-form").validate({});
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    mce_init_form();
}
function mce_init_form(){
    jQuery(document).ready( function($) {
      var options = { errorClass: 'mce_inline_error', errorElement: 'div', onkeyup: function(){}, onfocusout:function(){}, onblur:function(){}  };
      var mce_validator = $("#mc-embedded-subscribe-form").validate(options);
      $("#mc-embedded-subscribe-form").unbind('submit');//remove the validator so we can get into beforeSubmit on the ajaxform, which then calls the validator
      options = { url: 'http://constellation.us2.list-manage1.com/subscribe/post-json?u=5dcb972a18f2ca884af5f6bd9&id=6c1902192e&c=?', type: 'GET', dataType: 'json', contentType: "application/json; charset=utf-8",
                    beforeSubmit: function(){
                        $('#mce_tmp_error_msg').remove();
                        $('.datefield','#mc_embed_signup').each(
                            function(){
                                var txt = 'filled';
                                var fields = new Array();
                                var i = 0;
                                $(':text', this).each(
                                    function(){
                                        fields[i] = this;
                                        i++;
                                    });
                                $(':hidden', this).each(
                                    function(){
                                        if (fields.length == 2) fields[2] = {'value':1970};//trick birthdays into having years
                                    if ( fields[0].value=='MM' && fields[1].value=='DD' && fields[2].value=='YYYY' ){
                                    this.value = '';
   } else if ( fields[0].value=='' && fields[1].value=='' && fields[2].value=='' ){
                                    this.value = '';
   } else {
                                       this.value = fields[0].value+'/'+fields[1].value+'/'+fields[2].value;
                                   }
                                    });
                            });
                        return mce_validator.form();
                    }, 
                    success: mce_success_cb
                };
      $('#mc-embedded-subscribe-form').ajaxForm(options);      
              $('#mc_embed_signup').hide();
        cks = document.cookie.split( ';' );
        var show=true;
        for(i=0;i<cks.length;i++){
            parts = cks[i].split('=');
            if( parts[0].indexOf('MCEvilPopupClosed')!= -1 ) show = false;
        }
        if (show){
            $('#mc_embed_signup a.mc_embed_close').show();
            setTimeout( function(){ $('#mc_embed_signup').fadeIn();} , 2000);
       $('#mc_embed_signup a.mc_embed_close').click(function(){ mcEvilPopupClose();});
        }

        $(document).keydown(function(e){
            if (e == null) { 
              keycode = event.keyCode;
            } else { 
              keycode = e.which;
            }
            if(keycode == 27){
                mcEvilPopupClose();
            }
        });

        function mcEvilPopupClose(){
            $('#mc_embed_signup').hide();
            var now = new Date();
            var expires_date = new Date( now.getTime() + 31536000000 );
            document.cookie = 'MCEvilPopupClosed=yes;expires=' + expires_date.toGMTString()+';path=/';
        }

    });
}
function mce_success_cb(resp){
    $('#mce-success-response').hide();
    $('#mce-error-response').hide();
    if (resp.result=="success"){
        $('#mce-'+resp.result+'-response').show();
        $('#mce-'+resp.result+'-response').html(resp.msg);
        $('#mc-embedded-subscribe-form').each(function(){
            this.reset();
    });
    } else {
        var index = -1;
        var msg;
        try {
            var parts = resp.msg.split(' - ',2);
            if (parts[1]==undefined){
                msg = resp.msg;
            } else {
                i = parseInt(parts[0]);
                if (i.toString() == parts[0]){
                    index = parts[0];
                    msg = parts[1];
                } else {
                    index = -1;
                    msg = resp.msg;
                }
            }
        } catch(e){
            index = -1;
            msg = resp.msg;
        }
        try{
            if (index== -1){
                $('#mce-'+resp.result+'-response').show();
                $('#mce-'+resp.result+'-response').html(msg);            
            } else {
                err_id = 'mce_tmp_error_msg';
                html = '<div id="'+err_id+'" style="'+err_style+'"> '+msg+'</div>';
                
                var input_id = '#mc_embed_signup';
                var f = $(input_id);
                if (ftypes[index]=='address'){
                    input_id = '#mce-'+fnames[index]+'-addr1';
                    f = $(input_id).parent().parent().get(0);
                } else if (ftypes[index]=='date'){
                    input_id = '#mce-'+fnames[index]+'-month';
                    f = $(input_id).parent().parent().get(0);
                } else {
                    input_id = '#mce-'+fnames[index];
                    f = $().parent(input_id).get(0);
                }
                if (f){
                    $(f).append(html);
                    $(input_id).focus();
                } else {
                    $('#mce-'+resp.result+'-response').show();
                    $('#mce-'+resp.result+'-response').html(msg);
                }
            }
        } catch(e){
            $('#mce-'+resp.result+'-response').show();
            $('#mce-'+resp.result+'-response').html(msg);
        }
    }
}

</script>
<!--End mc_embed_signup-->