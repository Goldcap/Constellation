var showtime_collection = {

    url:'/services/Screenings/latest',
    button:null,
    isActive : true,
    list:null,
    showtimeBlockCount:0,
    parseTag : '.movie_block',
    blockHeight: 224,
    predicate: null,
    today: null,
    nextshows: null,
    pullType: null,
    
    templateString : '\
	  <div class="movie_block ">\
	      <div class="movie_block_content" >\
	          <a data-attach-point="dateholderLink"><ul class="featured_time" data-attach-point="dateholder"></ul></a>\
	          <span class="movie_block_in_progress"></span>\
	          <div class="movie_block_poster" data-attach-point="hostImageParent">\
	              <span class="screening_container" data-attach-point="screeningImageWrap" ><img data-attach-point="screeningImage" /></span>\
	              <div class="tooltip film_block_description_tip" style="display:none"><div class="film_block_description_content" data-attach-point="hostName"></div><span class="film_block_description_tip_arrow"></span></div>\
	          </div>\
	          <div class="movie_block_details" data-attach-point="content"> \
	              <h6 class="movie_block_title" data-attach-point="title"></h6>\
	              <p data-attach-point="movieTime"></p>\
	              <div class="user_images"> \
	                  <span class="user_list" data-attach-point="userList"></span> \
	                  <span class="user_count" data-attach-point="attendingCount"></span> \
	                  <a data-attach-point="attendButton" class="button uppercase"></a> \
	              </div>\
	          </div>\
	      </div>\
	  </div>\
	  ',
	
	  init: function() {
	      
      showtime_collection.linkBlocks();
      
      var a = new Date();
      showtime_collection.today = $.format.date(a, "MM/dd/yy");
      
      //console.log(showtime_collection.today);
      showtime_collection.nextshows = new Array();
      showtime_collection.getNextShows( 'upcoming' );
      
      $("#moreUpcoming").click(function(e) {
          e.preventDefault();
          showtime_collection.predicate = 'next';
          showtime_collection.setDefaults("#moreUpcoming");
          showtime_collection.getList( "upcoming" );
      });
      $("#moreFeatured").click(function(e) {
          e.preventDefault();
          showtime_collection.predicate = 'featured';
          showtime_collection.setDefaults("#moreFeatured");
          showtime_collection.getList( "featured" );
      });
        
    },
    
    setDefaults: function( type ){
        //console.log("Set Defaults for "+type);
        showtime_collection.button = $(type);
        showtime_collection.list = $(type).parent().prev().find('.film_block_list');
        var height = showtime_collection.list.height();
        showtime_collection.list.parent().css({height: height});
        showtime_collection.showtimeBlockCount = showtime_collection.list.find(showtime_collection.parseTag).length;
    },
    
    linkBlocks: function() {
        $(".movie_block").click(function(e) {
            if (e.target.getAttribute("alt") != "") {
                //window.location.href = e.target.getAttribute("alt");
            }
        });
    },
        
    getList: function( type ){
        showtime_collection.getNextShows( type );
            
        showtime_collection.offset = showtime_collection.showtimeBlockCount;
            
        showtime_collection.pullType = type;
            
        var args = {"type": type,
                  "page": showtime_collection.showtimeBlockCount, //showtime_collection.showtimeBlockCount,
                  "viewed" : showtime_collection.nextshows.join(",")
                    };
        if ($("#film_id").length > 0) {
                    args.film = $("#film_id").html();
                }
        jQuery.ajax({
            url: showtime_collection.url,
            data: $.param(args),
            dataType: 'json',
            success:  showtime_collection.onGetListSuccess
        })
    },
    
    onGetListSuccess: function(response){
        if ((response != undefined) && (response.ScreeningListResponse != null)) {
                if(showtime_collection.showtimeBlockCount >= response.meta.totalresults){
                    showtime_collection.button.css({opacity: 0.5});
                return;
            }
	              jQuery.each(response.ScreeningListResponse, function(index, showtime){
	                  //console.log(showtime.screening_name);
	                  if ($(".movie_block[identifier="+showtime.screening_id+"]").length == 0) {
	                       showtime_collection.button.parent().prev().find('.film_block_list').append(showtime_collection.buildTemplate( showtime ));
                         showtime_collection.showtimeBlockCount = showtime_collection.showtimeBlockCount+1;
                         //Start the countdown
                                 countdown_alt.init(showtime.screening_unique_key+'_'+showtime_collection.predicate,showtime.screening_date);
                         //Start the countdown
                                 remaining_alt.runHrMin(showtime.screening_unique_key,showtime.screening_date);
                         //Add Attendees
                                 screening_attendees.init(showtime.screening_unique_key,showtime.screening_id);
                             } else {
                                //alert("found "+showtime.screening_id);
                             }
                        });
            showtime_collection.slideDown();
            if(showtime_collection.showtimeBlockCount >= response.meta.totalresults){
                showtime_collection.button.css({opacity: 0.5});
            }

        }
    },
    
    slideDown: function(){
        var height = showtime_collection.list.height();
        showtime_collection.list.parent().animate({
            height: height
        },200)
    },
        
    buildTemplate : function( showtime ){
    	
			var totalSeats = (showtime.screening_total_seats > 0) ? showtime.screening_total_seats : showtime.screening_film_total_seats;
			totalSeats = (totalSeats > 0) ? totalSeats : 500;
	
			var domNode = $(showtime_collection.templateString),
      time = showtime.screening_date.split('|'),
			isSoldout = showtime.screening_audience_count >= totalSeats,
      isLimited = (showtime.screening_highlighted == "true") ? true : false,
      isHosting = showtime.screening_hosting,
      isAttending = showtime.screening_attending,
      title = (showtime.screening_name != '') ? showtime.screening_name : showtime.screening_film_name,
      seatsLeft = showtime.screening_film_total_seats - showtime.screening_audience_count,
      data = {
          id: showtime.screening_id,
          key: showtime.screening_unique_key,
          film: showtime.screening_film_id,
          screening_name: showtime.screening_name,
          hostimage: showtime.screening_user_photo_url,
          title: title,
          time: (parseInt(time[3]) > 12 ? parseInt(time[3]) - 12 : time[3]) + ':' + time[4] + (parseInt(time[3]) > 12 ? 'PM': 'AM'),
          date: time[1] + '/' + time[2] + '/' + time[0].substr(2,4),
          countdown_start: new Date(time[0], time[1]-1, time[2], time[3], time[4], time[5]),
          host_name: showtime.screening_user_full_name,
          host_bio: showtime.screening_user_bio
      }
    
      $(domNode).attr('class','movie_block movie_block_dynamic movie_block_'+showtime_collection.pullType);

      if (isHosting){
          domNode.addClass('movie_block_hosting');            
      } else if(isAttending){
          domNode.addClass('movie_block_attend');                        
      } else if(isLimited){
          domNode.addClass('movie_block_gold');
      }

      $(domNode).attr('identifier',data.id);
      $(domNode).attr('alt','/film/'+data.film);

    	if (showtime.screening_still_image != '') {
          var image = '/uploads/screeningResources/'+showtime.screening_film_id+'/screenings/film_screening_next_'+showtime.screening_still_image;
          // $("[data-attach-point=screeningImageContainer]",domNode).attr("class","screening_container");
          $("[data-attach-point=screeningImage]",domNode).attr("width","150");
      } else if (showtime.screening_film_still_image != '') {
          var image = '/uploads/screeningResources/'+showtime.screening_film_id+'/still/'+showtime.screening_film_still_image;
          // $("[data-attach-point=screeningImageContainer]",domNode)//.attr("class","logo_container");
          $("[data-attach-point=screeningImage]",domNode).attr("height","130");
      } else {
          var image = 'https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt/featured_still.jpg';
          // $("[data-attach-point=screeningImageContainer]",domNode)//.attr("class","logo_container");
          $("[data-attach-point=screeningImage]",domNode).attr("width","140");
      }

      $("[data-attach-point=screeningImage]",domNode).attr('src',image);
      $("[data-attach-point=screeningImageWrap]",domNode).attr('id','screening_' + data.key).attr('onclick', "window.location='/theater/"+data.key+"';");
      $("[data-attach-point=screeningImageWrap]",domNode).tooltip({ effect: 'slide', relative: true, position: 'center right' });
       
      if (data.screening_name != '') {
				$("[data-attach-point=title]",domNode).html('<strong>' + data.screening_name + '</strong>');
	      $("[data-attach-point=hostName]",domNode).html(data.title);
			} else {
				if ((data.host_name != undefined) && (data.host_name != '')) {
	      	if ((data.host_bio != undefined) && (data.host_bio != '')) {
	      		$("[data-attach-point=title]",domNode).html('<span id="hostname_'+data.key+'_tip">' + data.host_name + '</span><div class="tooltip film_block_description_tip"><div class="film_block_description_content" style="display:none">'+data.host_bio+'</div></div> hosts <strong>' +data.title + '</strong>');
	          $('#hostname_'+data.key+'_tip',domNode).tooltip({ effect: 'slide', relative: true, position: 'center center' });
	      	} else {
	        	$("[data-attach-point=title]",domNode).html('<span id="hostname_'+data.key+'_tip">' + data.host_name + '</span> hosts </span><strong>' +data.title + '</strong>');
	
	      	}
					$("[data-attach-point=hostName]",domNode).html(data.host_name); 
				} else {
	          $("[data-attach-point=title]",domNode).html('<strong>' + data.title + '</strong>');
	          $("[data-attach-point=hostName]",domNode).html(data.title);
	      }
      }
      $("[data-attach-point=title]",domNode).attr('onclick', "window.location = '/film/"+data.film+"'");
        
      if (showtime_collection.today == data.date) {
          var time_now =  new Date();
          
          if ( time_now > data.countdown_start ) {
            // $("[data-attach-point=time]",domNode).attr("id","time_remaining_"+data.key);
            // $("[data-attach-point=time]",domNode).attr("class","time_remaining");
            // var somehtml = data.time+ ' ' + showtime.screening_timezone + '<img border="0" class="in_progress" src="/images/alt1/in_progress.png">';
            // $("[data-attach-point=date]",domNode).html(somehtml);
            var runningTime = showtime.screening_film_running_time.split(':');

	          $("[data-attach-point=dateholder]",domNode).html('<li><img border="0" class="in_progress" src="/images/alt1/in_progress.png"></li>');
	          $("[data-attach-point=movieTime]",domNode).html('<p><span class="countdown_start">ELAPSED TIME</span> <span id="time_remaining_'+data.key+'" class="elapsed_time">2011|10|20|22|37|00</span> <span class="film_time_total">/ '+runningTime[0]+' <span class="shorty">HRs</span> '+runningTime[1]+'<span class="shorty">MIN</span><span></span></span></p>');
          } else {
              $("[data-attach-point=dateholder]",domNode).html('<li>Today @ '+ data.time + ' ' + showtime.screening_timezone+'</li>')
              $("[data-attach-point=movieTime]",domNode).html('<span class="countdown_start">STARTS IN</span> <span id="timekeeper_' + data.key + '_' + showtime_collection.predicate +'" class="timekeeper"></span>');
          }
        } else {
          $("[data-attach-point=dateholder]",domNode).html('<li>'+data.time + ' ' + showtime.screening_timezone+'</li><li>'+data.date+'</li>')
          $("[data-attach-point=movieTime]",domNode).html('<span class="countdown_start">STARTS IN</span> <span id="timekeeper_' + data.key + '_' + showtime_collection.predicate +'" class="timekeeper"></span> | <span class="uppercase link" onclick="reminder.remind(\'' + data.key +'\');">Remind Me</span>');
        }
        $('[data-attach-point=dateholderLink]', domNode).attr('href','/theater/' + data.key);

        if(isHosting){
            $('[data-attach-point=dateholder]', domNode).after('<p class="movie_block_type"><span class="movie_block_type_hosting"></span></p>');
        } else if(isAttending){
            $('[data-attach-point=dateholder]', domNode).after('<p class="movie_block_type"><span class="movie_block_type_attending"></span></p>');
        } else if(isLimited){
          if (isSoldout) {
            $('[data-attach-point=dateholder]', domNode).after('<p class="movie_block_type">Sold Out<span class="movie_block_type_limited"></span></p>');
          } else {
            $('[data-attach-point=dateholder]', domNode).after('<p class="movie_block_type">'+(seatsLeft < 20 ? seatsLeft +' seats left' : '')+'<span class="movie_block_type_limited"></span></p>');
          }
        } else if(isSoldout){
            $('[data-attach-point=dateholder]', domNode).after('<p class="movie_block_type"><span class="movie_block_type_soldout"></span></p>');
        } 

        if (showtime.screening_user_photo_url != '') {
          if (showtime.screening_user_photo_url.substr(0,4) == 'http') {
              var imhtml = '<img class="screening_host" width="40" height="40" src="'+showtime.screening_user_photo_url+'" />';
          } else {
              var imhtml = '<img class="screening_host" width="40" height="40" src="/uploads/hosts/'+showtime.screening_user_id+'/'+showtime.screening_user_photo_url+'" />';
          }
          $("[data-attach-point=title]",domNode).prepend(imhtml);
              
        } else if (showtime.screening_user_image != '') {
          if (showtime.screening_user_image.substr(0,4) == 'http') {
              var imhtml = '<img class="screening_host" width="40" height="40" src="'+showtime.screening_user_image+'" />';
          } else {
              var imhtml = '<img class="screening_host" width="40" height="40" src="/uploads/hosts/'+showtime.screening_user_id+'/'+showtime.screening_user_image+'" />';
          }
          $("[data-attach-point=title]",domNode).prepend(imhtml);
        } else if (showtime.screening_user_id > 0) {
            var imhtml = '<img class="screening_host" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png" />';
            $("[data-attach-point=title]",domNode).prepend(imhtml);
        }

        var buttonText  = 'Join',
            buttonClass = 'button_blue';

        if (isHosting) {
            buttonText = 'Enter Theater'; 
            buttonClass = 'button_green';
        } else if(isAttending) {
            buttonText = 'Enter Theater';
            buttonClass = 'button_purple';
        } else if(isSoldout){
            buttonText = 'join'; 
            buttonClass = 'button_gray disabled_button';
        }

        $("[data-attach-point=attendButton]",domNode)
            .html(buttonText)
            .addClass(buttonClass);
        if(!isSoldout){
           $("[data-attach-point=attendButton]",domNode).attr('href','/theater/' + data.key);
        }
            //button_blue

        $("[data-attach-point=attendingCount]",domNode).attr('class','user_count user_count_' + data.key );
        $("[data-attach-point=userList]",domNode).attr('class','user_list user_images_' + data.key );

        if(isHosting || isAttending) {
            if (isHosting) { var intype = 'hosting'; } else { var intype = 'screening'; };
            $("[data-attach-point=content]",domNode).append($('<div class="movie_block_invite">Invite your friends to this screening <span class="button button_gray button_invite_email" onclick="invite.invite(\''+intype+'\',\''+data.key+'\');"><span class="icon_email"></span> Email invitations</span><span class="button button_faceblue button_invite_facebook" onclick="fb_invite.invite(\''+intype+'\',\''+data.key+'\');"><span class="icon_facebook"></span> Invite Facebook friends</span></div>'));
        }

        return domNode;
    },
    
    getNextShows: function( type ) {
        //console.log(type);
        if (type == 'upcoming') {
                var filter = 'featured';
            } else {
                var filter = 'upcoming';
            }
        showtime_collection.nextshows = new Array();
        console.log(type + " has " + $(".movie_block_"+filter).length);
            $(".movie_block_"+filter).each(function(element){
                showtime_collection.nextshows.push($(this).attr('identifier'));
                console.log($(this).attr('title'));
            });
        }

};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    showtime_collection.init();
    
});
