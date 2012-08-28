define(["text!./../Template/EventModule.html", "../View/Header" ], function(template){
    var CTV = CTV || {};
    CTV.Controller =  CTV.Controller || {};

    CTV.Controller.Login = Backbone.View.extend({
    	initialize: function(){
            if(this.options.isLoggedIn){
                new Header();
            }
    	}
    	
    });

   return CTV.Controller.Login;
});