var header = {

    options:{

    },

    init: function(){
        this.loginButton = $('#loggedUser');
        if(this.loginButton){
            this.parentContainer = $(this.loginButton.parent());
            this.dropDown = $(this.loginButton.next()).css({height: 0, display: 'block'});;
            this.isOpen = false;
            this.attachEvents();
        }
    },
    attachEvents: function(){
        var self = this;
        this.loginButton.bind('click', jQuery.proxy(self, 'toggle'));
    },
    toggle: function(e){
        e.stopPropagation()
        if(!this.isOpen){
            this.open();
        } else {
            this.close();
        }
    },
    open: function(){
        this.isOpen = true;
        this.parentContainer.addClass('active');
        this.dropDown.animate({'height': 92});
        $('body').bind('click', jQuery.proxy(this, 'close'));
    },
    close: function(){
        this.isOpen = false;
        this.parentContainer.removeClass('active');
        this.dropDown.animate({'height': 0});
        $('body').unbind('click', jQuery.proxy(this, 'close'));
    }
};

(function(){
    header.init();
})();