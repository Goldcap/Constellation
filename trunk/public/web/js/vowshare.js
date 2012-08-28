// JavaScript Document
var vowshare = {
	
	init: function() {
		$(".share-vow").click(function(e){
			 $(".share_container").fadeIn();
			modal.modalIn(vowshare.close)
		});
	
	   $("#FILE_vow_element_original").bind('change', vowshare.onChange)
        $("#FILE_vow_element_original").makeAsyncUploader({
            upload_url: "/services/ImageManager/vow?constellation_frontend="+$("#session_id").html(),
            flash_url: '/js/swfupload/swfupload.swf',
            button_image_url: '/images/pages/thevow/upload-button.png',
            width: '180',
            height: '34',
            button_text_left_padding: 0,
            button_text_top_padding: 8,
  			    button_text: '<span class="buttonText">Choose File</span>',
            button_text_style: '.buttonText { width: 180px; text-align:center; height: 34px; margin-top: 6px; color: #ffffff; line-height:34px; font-size: 14px; font-family: helvetica, arial, sans-serif; } .buttonAnother { width: 180px; text-align:center; height: 34px; margin-top: 6px; color: #ffffff; line-height:34px; font-size: 14px; font-family: helvetica, arial, sans-serif;}',   
            debug: false,
            file_size_limit: '20 MB'
        });

	    $('#vow_element').bind('focus', function(){	$(".vow_error").html('')})
	},
	onChange: function(e){
		console.log($(e.target).val())	
	},
	
	doSubmit: function() {
		var submit = true;
		$(".vow_error").html('')
		if (! vowshare.confirmDescription() || !vowshare.confirmAsset()) {
			submit = false;
		}
		if (submit) {
			var args = {"filename": $("input[name='FILE_vow_element_original_filename']").val(),
                  "asset": $("input[name='FILE_vow_element_original_guid']").val(),
                  "description": $("#vow_element").val()};
									
			$.ajax({url: "/services/VowUpload", 
	            data: $.param(args), 
	            type: "POST", 
	            cache: false, 
	            dataType: "json", 
	            success: function(response) {
	            	if(response.success == 'Failure') { 
						$(".vow_error").html(response.message);
	            	} else {
		            	$(".vow_upload_content").hide();
		            	$('.vow_upload_success').show();	            		
	            	}

	            }, error: function(response) {
	               console.log("ERROR:", response);
	            }
	    	});
		}
		return false;
	},
	
	confirmDescription: function() {
		if (!$("#vow_element").val()) {
		  $(".vow_error").html('Please describe your vow, and/or upload a file.');
		  return false;
		} else {
			return true;
		}
	},
	
	confirmAsset: function() {
	  //If the fields are both full
		if (!$("#vow_element").val()) {
		  $(".vow_error").html('Please describe your vow, and/or upload a file.');
		  return false;
		} else {
		  return true;
		}
	},
	close: function(){
    $(".share_container").fadeOut();
		modal.modalOut(function(){})
	}
}


$(document).ready(function(){
	if (!window.console) window.console = {};
 	if (!window.console.log) window.console.log = function() {};
  
	vowshare.init();
});
