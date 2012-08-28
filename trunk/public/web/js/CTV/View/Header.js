define(function(){
    var CTV = CTV || {};
    CTV.View =  CTV.View || {};

    CTV.View.Header = Backbone.View.extend({
        el: $('#header-nav'),
        isVisible: false,
    	initialize: function(){
            this.attachPoints();
            this.attachEvents();
    	},
        attachPoints: function(){
            this.$user = $('#header-user');
            this.$b = $(document.body);

        },
        attachEvents: function(){
            this.boundClick = _.bind(this.onUserClick, this)
            this.$user.bind("click",this.boundClick);
        },
        onUserClick: function(event){
            event.stopPropagation();
            if(!this.isVisible){
                this.isVisible = true;
                this.$el.stop().animate({height: 136},200);
                this.$b.bind('click',this.boundClick);
            } else {
                this.isVisible = false;
                this.$b.unbind('click',this.boundClick);
                this.$el.stop().animate({height: 0},200);

            }
        }
    	
    });

   return CTV.View.Header;
});