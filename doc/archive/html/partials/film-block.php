<div class="movie_block"> 
    <div class="movie_block_content"> 
		<ul class="featured_time"><li>10:00PM EDT</li><li>09/22/11</li></ul> 
        <div class="movie_block_poster"> 
            <img class="user_host_id32Gp" src="https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt/featured_still.jpg" /> 
            <div class="tooltip tooltip_large" style="display:none"></div> 
        </div>
        <div class="movie_block_details"> 
            <h6 class="movie_block_title">Exclusive Constellation Event: JIG</h6>
          	<p><span class="countdown_start">STARTS IN</span> <span class="timekeeper" id="timekeeper_id32Gp">2HRS 15MIN 37S</span></p>
          	
          	<div class="user_images"> 
        		<span class="user_list user_images_id32Gp"></span> 
        		<span class="user_count user_count_id32Gp">5 attending</span> 
                <a href="/theater/id32Gp" title="id32Gp" class="button button_blue uppercase">Attend</a> 
            </div>
        </div>
    </div>
</div> 


<script type="text/javascript"> 
$(document).ready(function() {
    
    $(".user_host_id32Gp" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });

    $.ajax({url:'/services/Screenings/users?screening=5127', 
        type: "GET", 
        cache: false, 
        dataType: "json", 
        success: function(response) {
            if ((response != null) && (response.users != undefined)) {
                for (var i = 0; i < response.users.length; i++) {
                    $(".user_images_id32Gp").append('<img class="user_image_id32Gp" src="'+response.users[i].image+'" alt="'+response.users[i].username+'" width="50" /><div class="tooltip" style="display:none">'+response.users[i].username+'</div>');
                }
                $(".user_image_id32Gp" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
            }
        }, 
        error: function(response) {
            console.log("ERROR:", response);
        }
    });

  countdown_alt.init('id32Gp','2011|09|22|22|00|00');
});
</script> 
