// JavaScript Document
var screening_attendees = {
	
	init: function( key, screening, limit ) {
		if (limit == undefined) {
			limit = 6;
		}
		//console.log(key + " " + screening);
		$.ajax({url:'/services/Screenings/users?screening='+screening+'&limit='+limit, 
        type: "GET", 
        cache: false, 
        dataType: "json", 
        success: function(response) {
          if ((response != null) && (response.users != undefined)) {
          console.log("key " + key + " has " + response.users.length);
          if (response.users.length == 0) {
						$(".user_count_"+key).remove();
					} else {
						//Removed 11/4/2011 Bug 001973
          	//$(".user_count_"+key).html(response.totalresults + " Recently Joined");
          	$(".user_count_"+key).html("Recently Joined");
					}
					for (var i = 0; i < response.users.length; i++) {
            $(".user_images_"+key).append('<img class="user_image user_image_'+key+'" src="'+response.users[i].image+'" alt="'+response.users[i].username+'" width="50" /><div class="tooltip user_tooltip" style="display:none">'+response.users[i].username+'</div>');
          }
          $(".user_image_"+key ).tooltip({ effect: 'slide', relative: true, position: 'top center' });
          } else {
						$(".user_count_"+key).remove();
					}
        }, error: function(response) {
            console.log("ERROR:", response);
        }
    });
	}
}
