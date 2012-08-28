// JavaScript Document
var colorme = {
	
	user:null,
	post:null,
	posl:null,
	icon:null,
	userElement:null,
	animating:false,
	status:null,
	lpos: 0,
	rpos: null,
	tpos: 0,
	bpos: null,
	imagesize: null,
	imagesmall: 28,
	imagelarge: 50,
	scrollsize: 64,
	currentwidth: 0,
	
	init: function() {
		
		colorme.imagesize = colorme.imagelarge;
		colorme.setChatSize();
		colorme.shortBox();
		colorme.user = $("#userid").html();
		//console.log(colorme.user);
		
		//console.log("Color Me Init");
		colorme.getCurrentUsers( true );
		if (colorme.user != 0) {
      $(".color_icon").click(function(){
				colorme.icon = $(this).attr("class").replace("color_icon ","");
				//if (colorme.status != colorme.icon) {
					colorme.animateIcon(colorme.user,colorme.icon);
					colorme.status = colorme.icon;
					var args = {body:"colorme:"+colorme.icon};
					$.postJSON("/services/chat/post", args, colorme.finishPost );
				//}
			});
			//return;
	  }
    
		$("#user_wrap_left").click(function(){
			
			if (colorme.lpos == 0) {
				return;
			}
			$("#user_wrap_right img").attr("src","/images/alt1/chat_right.png");
			
			$("#theater_icons").animate({
				left: '+='+colorme.scrollsize
		  }, 100, function() {
		  	colorme.lpos--;
		  	if (colorme.lpos == 0) {
					$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
		  	}
			});
				
			//console.log($("#theater_icons").width());
		});
		
		$("#user_wrap_right").click(function(){
			//console.log(colorme.lpos + " " + colorme.rpos);
			//console.log(colorme.imagesize);
			
			if ((colorme.imagesize == colorme.imagesmall) && (colorme.lpos == (colorme.rpos / 2) - 1)) {
				console.log("Reached Right Limit"); 
				return;
			} else if (colorme.lpos == colorme.rpos - 1) {
				return;
			}
			$("#user_wrap_left img").attr("src","/images/alt1/chat_left.png");
			
			$("#theater_icons").animate({
				left: '-='+colorme.scrollsize
		  }, 100, function() {
		  	colorme.lpos++;
		  	if ((colorme.imagesize == colorme.imagesmall) && (colorme.lpos == (colorme.rpos / 2) - 1)) {
					$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
				} else if (colorme.lpos == colorme.rpos + 1) {
					$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
				}
		  });
			//console.log($("#theater_icons").width());
		});
		
		$(window).resize(function () { colorme.resize() });
      
	},
	
	getCurrentUsers: function( init ) {
		
		$.ajax({url:'/services/Screenings/colorme?screening='+$("#screening_id").html(), 
		      type: "GET", 
		      cache: false, 
		      dataType: "json", 
		      success: function(response) {
		        if ((response != null) && (response.users != undefined)) {
		        for (var i = 0; i < response.users.length; i++) {
		        	if ($("#user_wrap_"+response.users[i].userid).length == 0) {
		          	$(".userblock #theater_icons").append('<div class="theater_icon" id="user_wrap_'+response.users[i].userid+'"><img class="colorme_user" id="user_image_'+response.users[i].userid+'" src="'+response.users[i].image+'" alt="'+response.users[i].username+'" width="'+colorme.imagesize+'" /><div class="tooltip" style="display:none">'+response.users[i].username+'</div></div>');
		        	}
						}
		        $(".userblock .theater_icon img" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
		        }
		        if ($("#user_wrap_"+$("#userid").html()).length > 0) {
			      	//console.log("Found User");
							var theposition = $("#user_wrap_"+$("#userid").html()).position();
							colorme.post = theposition.top;
							colorme.posl = theposition.left;
							colorme.rpos = $(".theater_icon").length;
						} else {
							//console.log("No User" + $("#userid").html());      
						}
						colorme.boxSwitch();
						colorme.resize(); 
						$(".userblock").fadeIn(100); 
		      }, error: function(response) {
		          console.log("ERROR:", response);
		      }
		});
		window.setTimeout(colorme.getCurrentUsers, 10000);
	},
	
	animateIcon: function( user, icon ) {
		if (colorme.animating == false) {
			colorme.animating = true;
			$("#theater_icons #user_wrap_"+user).removeClass('happy');
			$("#theater_icons #user_wrap_"+user).removeClass('sad');
			$("#theater_icons #user_wrap_"+user).removeClass('wow');
			$("#theater_icons #user_wrap_"+user).removeClass('none');
			$("#theater_icons #user_wrap_"+user).removeClass('heart');
			$("#theater_icons #user_wrap_"+user).removeClass('quest');
			
			$("#theater_icons #user_wrap_"+user).removeClass('happy_small');
			$("#theater_icons #user_wrap_"+user).removeClass('sad_small');
			$("#theater_icons #user_wrap_"+user).removeClass('wow_small');
			$("#theater_icons #user_wrap_"+user).removeClass('none_small');
			$("#theater_icons #user_wrap_"+user).removeClass('heart_small');
			$("#theater_icons #user_wrap_"+user).removeClass('quest_small');
			
			$("#inbox .chat_icon_user_"+user).removeClass('happy_med');
			$("#inbox .chat_icon_user_"+user).removeClass('sad_med');
			$("#inbox .chat_icon_user_"+user).removeClass('wow_med');
			$("#inbox .chat_icon_user_"+user).removeClass('none_med');
			$("#inbox .chat_icon_user_"+user).removeClass('heart_med');
			$("#inbox .chat_icon_user_"+user).removeClass('quest_med');
			
			$("#color_node_"+user).remove();
			
	  	if (colorme.imagesize == colorme.imagesmall) {
	  		$("#user_wrap_"+user).addClass(icon+'_small');
	  		var nclass = "color_node_small";
				/*
				var bg = "_small";
				$("#user_wrap_"+user).css("backgroundPosition","-4px -4px");
				*/
			} else {
	  		$("#user_wrap_"+user).addClass(icon);
	  		var nclass = "color_node";
				/*
				var bg = "";
				$("#user_wrap_"+user).css("backgroundPosition","-8px -8px");
				*/
			}
			
			$("#inbox .chat_icon_user_"+user).addClass(icon+'_med');
			/*
			$("#user_wrap_"+user).css("background-image","url('/images/alt1/"+icon+"_bg"+bg+".png')");
	  	*/
			$("#user_wrap_"+user).append('<img id="color_node_'+user+'" class="'+nclass+'" src="/images/alt1/'+icon+'_icon.png" />');
	  	
			$("#color_node_"+user).animate({
		    opacity: 0,
		    top: '-=70'
		  }, 3000, function() {
		    $("#color_node_"+user).remove();
		  });
		  colorme.animating = false;
	  }
	},
	
	resize: function() {
		
		colorme.currentwidth = ($(window).width() - 445);
  	$(".userblock").width(colorme.currentwidth);
  	if (colorme.imagesize == colorme.imagesmall) {
			//console.log("Resize Small Icons");
			var iconswide = Math.ceil(colorme.currentwidth / colorme.scrollsize);
			//console.log("Width is " + iconswide + " and Icons are " + colorme.rpos);
			if (iconswide > colorme.rpos) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
			} else if (colorme.lpos == 0) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right.png");
			}
			//Make the width of the display area 1/2 of total icons times icon wrapper + padding
			var width = colorme.rpos / 2 * (colorme.imagesize + 4);
			$(".bottomblock #theater_icons").css('width',width+'px');
			
		} else {
			//console.log("Resize Big Icons");
			var iconswide = Math.ceil(colorme.currentwidth / colorme.scrollsize);
			//console.log("Width is " + iconswide + " and Icons are " + colorme.rpos);
			if (iconswide > colorme.rpos) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
			} else if (colorme.lpos == 0) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right.png");
			}
			$(".bottomblock #theater_icons").css('width','10000px');
		}
  	$(".userblock").width(colorme.currentwidth);
  	
		return;
		
  },
  
  resize_last: function() {
		
		colorme.currentwidth = ($(window).width() - 445);
  	$(".userblock").width(colorme.currentwidth);
  	if (colorme.imagesize == colorme.imagesmall) {
			//console.log("Resize Small Icons");
			$(".bottomblock #theater_icons").css('width',colorme.currentwidth+'px');
			var iconswide = colorme.currentwidth / colorme.scrollsize;
			colorme.bpos = Math.ceil(colorme.rpos / iconswide) - 1;
			//console.log("Rows are "+colorme.bpos);
			//If we had scrolled to the bottom
			//But the bottom moved
			//Scroll up to the new bottom
			//console.log("BPOS: "+colorme.bpos+" TPOS: " + colorme.tpos);
			if ((colorme.tpos > 0) && (colorme.tpos >= colorme.bpos) && (colorme.animating == false)) {
				colorme.animating = true;
				var addlrows = (colorme.tpos - colorme.bpos) + 1;
				$("#theater_icons").animate({
					top: '+='+(colorme.scrollsize * addlrows)
			  }, 100, function() {
			  	colorme.tpos = colorme.tpos - addlrows - 1;
					//console.log("Readjusting the bottom row by " + addlrows + " to tpos of " + colorme.tpos);
					$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
					colorme.animating = false;
			  });
			}
			if (colorme.tpos >= colorme.bpos) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
			} else if (colorme.tpos > 0) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left.png");
			}
			//If there are only two rows, hide the scrollers
			if (colorme.bpos < 2) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
			} else if (colorme.tpos >= colorme.bpos - 1) {
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
			} else {
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right.png");
			}
			
		} else {
			//console.log("Resize Big Icons");
			var iconswide = Math.ceil(colorme.currentwidth / colorme.scrollsize);
			//console.log("Width is " + iconswide + " and Icons are " + colorme.rpos);
			if (iconswide > colorme.rpos) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right_off.png");
			} else if (colorme.lpos == 0) {
				$("#user_wrap_left img").attr("src","/images/alt1/chat_left_off.png");
				$("#user_wrap_right img").attr("src","/images/alt1/chat_right.png");
			}
			$(".bottomblock #theater_icons").css('width','10000px');
		}
  	$(".userblock").width(colorme.currentwidth);
  	
		return;
		
  },
  
  //Decides when to switch from LARGE to SMALL icons
	boxSwitch: function() {
		if (($(".theater_icon").length > 50) && (colorme.imagesize != colorme.imagesmall)) {
			console.log("Makiing Tall Box");
			colorme.tallBox();
		} else if (($(".theater_icon").length < 50) && (colorme.imagesize != colorme.imagesmall)) {
			console.log("Makding Short Box coz length is "+$(".theater_icon").length+" and imagesize is "+colorme.imagesize);
			colorme.shortBox();
		}
	},
	
	//Make the chat icons small
  tallBox: function() {
  	
  	$("#theater_icons").css("left","0px");
		colorme.imagesize = colorme.imagesmall;
		colorme.scrollsize = colorme.imagesmall + 4;
		colorme.lpos = 0;
		$("#theater_icons .happy").addClass('happy_small');
		$("#theater_icons .happy").removeClass('happy');
		$("#theater_icons .sad").addClass('sad_small');
		$("#theater_icons .sad").removeClass('sad');
		$("#theater_icons .wow").addClass('wow_small');
		$("#theater_icons .wow").removeClass('wow');
		$("#theater_icons .none").addClass('none_small');
		$("#theater_icons .none").removeClass('none');
		$("#theater_icons .heart").addClass('heart_small');
		$("#theater_icons .heart").removeClass('heart');
		$("#theater_icons .quest").addClass('quest_small');
		$("#theater_icons .quest").removeClass('quest');
		
  	$(".theater_icon .colorme_user").attr("width",colorme.imagesize);
  	$(".theater_icon").css("width",colorme.imagesize);
  	$(".theater_icon").css("padding","2px");
  	colorme.resize();
  	
		
	},
	
	//Make the chat icons big
	shortBox: function() {
  	
		//console.log("Big Icons");
		
		$("#theater_icons").css("top","0px");
  	$("#theater_icons").css("left","0px");
		colorme.imagesize = colorme.imagelarge;
		colorme.scrollsize = colorme.imagelarge + 14;
		colorme.tpos = 0;
		$("#theater_icons .happy_small").addClass('happy');
		$("#theater_icons .happy_small").removeClass('happy_small');
		$("#theater_icons .sad_small").addClass('sad');
		$("#theater_icons .sad_small").removeClass('sad_small');
		$("#theater_icons .wow_small").addClass('wow');
		$("#theater_icons .wow_small").removeClass('wow_small');
		$("#theater_icons .none_small").addClass('none');
		$("#theater_icons .none_small").removeClass('none_small');
		$("#theater_icons .heart_small").addClass('heart');
		$("#theater_icons .heart_small").removeClass('heart_small');
		$("#theater_icons .quest_small").addClass('quest');
		$("#theater_icons .quest_small").removeClass('quest_small');
		
  	$(".theater_icon .colorme_user").attr("width",colorme.imagesize);
  	$(".theater_icon").css("width",colorme.imagesize);
  	$(".theater_icon").css("padding","7px");
  	colorme.resize();
		
	},
	
	setChatSize : function() {
		/*
		$(".footer").css("height","148px");
  	$(".bottomblock").css("height","148px");
  	$(".colorblock").css("top","96px");
  	$(".userblock").css("height","96px");
  	*/
  	
		$(".footer").css("height","122px");
		$(".bottomblock").css("height","122px");
  	$(".colorblock").css("top","70px");
  	$(".userblock").css("height","68px");
  	
	},
	
	finishPost: function( response ) {
		//console.log("Finished");
	}
	
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
  colorme.init();
  
});
