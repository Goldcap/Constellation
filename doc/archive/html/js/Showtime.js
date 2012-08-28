(function(){

    var template = '\
        <div class="movie_block"><div class="movie_block_content">\
            <ul class="featured_time"><li data-attach-point="time"></li><li data-attach-point="date"></li></ul>\
            <span class="movie_block_in_progress"></span>\
                <div class="movie_block_poster"> \
                    <img data-attach-point="hostImage" /> \
                    <div class="tooltip tooltip_large" style="display:none" data-attach-point="hostName"></div> \
                </div>\
                <div class="movie_block_details"> \
                    <h6 class="movie_block_title" data-attach-point="title"></h6>\
                  	<p><span class="countdown_start">STARTS IN</span> <span data-attach-point="countdown" class="timekeeper">2HRS 15MIN 37S</span></p>\
                  	\
                  	<div class="user_images"> \
                		<span class="user_list" data-attach-point="userList"></span> \
                		<span class="user_count" data-attach-point="attendingCount"></span> \
                        <a data-attach-point="attendButton" class="button button_blue uppercase">Attend</a> \
                    </div>\
                </div>\
            </div>\
        </div> \
    ';

    
    

    this.ShowtimeBlock = function(data){
        this.data = data;
        this.buildTemplate();
    }
    
    ShowtimeBlock.prototype = {
        templateString : template,
        buildTemplate : function(){
            this.domNode = $(this.templateString);
            this.attachPoints();
            this.attachValues();
        },
        attachPoints: function(){
            var self = this;
            this.domNode.find('*[data-attach-point]').each( function(index, point){
                $point = $(point);
                self[$point.attr('data-attach-point')] = $point;
            });
        },
        attachValues: function(){
            var data = this.mapData(this.data)
            this.hostImage.attr('src',data.host_avatar).tooltip({ effect: 'slide', relative: true, position: 'center right' });
            
            this.hostName.html(data.host_name); 
            this.title.html(data.title);
            this.date.html(data.date);
            this.time.html(data.time);
            this.attendButton.attr('href','/theater/' + data.id );
            this.attendingCount.html(data.attendingCount + ' attending');
            
            for (var i = 0; i < data.attendees.length; i++) {
                this.userList.append('<img class="user_image_id32Gp" src="'+data.attendees[i].avatar+'" alt="'+data.attendees[i].username+'" width="50" /><div class="tooltip" style="display:none">'+data.attendees[i].username+'</div>');
            }
                this.userList.find('img').tooltip({ effect: 'slide', relative: true, position: 'center right' });
                
            this.startCountdown();
            
            //        
        },
        startCountdown: function(){
            var times = this.data.showtime_start.split('|'),
                td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
            this.countdown.countdown({
                layout: '<span style ="text-transform:lowercase">{dnn}<span class="shorty">DAYS</span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span>{snn}<span class="shorty">s</span></span>', 
                until: td,
                serverSync: getCurrentTime,
                format: 'DHMS'
            });
        },
        mapData: function(showtime){
            var time = showtime.showtime_start.split('|');
            var data = {
                id: showtime.id,
                title: showtime.showtime_title,
                time: (parseInt(time[3]) > 12 ? parseInt(time[3]) - 12 : time[3]) + ':' + time[4] + (parseInt(time[3]) > 12 ? 'PM': 'AM'),
                date: time[1] + '/' + time[2] + '/' + time[0].substr(2,4),
                host_avatar: showtime['host'].avatar,
                host_name: showtime['host'].name,
                attendingCount: showtime.attendees.length,
                attendees: showtime.attendees
            };
            
            return data;
        }
        
    };


})();
