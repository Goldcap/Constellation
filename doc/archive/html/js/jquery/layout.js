
$(document).ready(function(){
       
    $('#colorpickerHolder1').ColorPicker({
			flat: true,
			color: '#ed0e7f',
		  onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#colorSelector1 div').css('backgroundColor', '#' + hex);
				$('#maintext').css('color', '#' + hex);
				$('#mainbg').css('backgroundColor', '#' + hex);
			}
		});
		$('#colorpickerHolder1>div').css('position', 'absolute');
		var widt1 = false;
		$('#colorSelector1').bind('click', function() {
			$('#colorpickerHolder1').stop().animate({height: widt1 ? 0 : 173}, 500);
			widt1 = !widt1;
		});
		
		$('#colorpickerHolder2').ColorPicker({
			flat: true,
			color: '#ed0e7f',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#colorSelector2 div').css('backgroundColor', '#' + hex);
				$('.comingtext').css('color', '#' + hex);
			}
		});
		$('#colorpickerHolder2>div').css('position', 'absolute');
		var widt2 = false;
		$('#colorSelector2').bind('click', function() {
			$('#colorpickerHolder2').stop().animate({height: widt2 ? 0 : 173}, 500);
			widt2 = !widt2;
		});
		
		if ($(".template-photo-upload").length > 0) {
	    $(".template-photo-upload").click(function() {
	      $('<a href="#template-photo-popup">Show</a>').fancybox({
	        overlayShow: true,
	        hideOnContentClick: false,
	        height: 120,
	        width: 490,
	        // have to set this up after we do the pop-up, as fancybox copies over the contents of the div into a new one
	        callbackOnShow: function() {
	          // set up the form for ajax submission
	          $("#fancy_content .template-photo-form").ajaxForm({
	            complete: function( response ) { 
	              $("#theback").css('background-image','url("'+response.responseText+'")');
	            }
	          });
	          // automatically submit the form when the file changes
	          $("#fancy_content .template-photo-file").change(function() {
	            $("#fancy_content .template-photo-form").submit();
	          });
	        }
	      }).click();
	    });
	 }
	 
	  $('.edit_text').editable('/utility/post', { 
         cancel    : 'Cancel',
         submit    : 'OK',
         indicator : '<img src="/images/indicator.gif">',
         tooltip   : 'Click to edit...'
     });
    
    $('.edit_area').editable('/utility/post', { 
         type      : 'textarea',
         cancel    : 'Cancel',
         submit    : 'OK',
         indicator : '<img src="/images/indicator.gif">',
         tooltip   : 'Click to edit...'
     });
     
     if ($("#dosave").length > 0) {
	    $("#dosave").click(function() {
	     //$('#krajina', window.opener.document).val(krajina1); 
        var themce = window.opener.tinyMCE.getInstanceById('text');
        themce.setContent($('#wrapper').html());
        if (confirm("Do you want to close the window?")) {
          window.close();
        }
      }); 
	  }
});

