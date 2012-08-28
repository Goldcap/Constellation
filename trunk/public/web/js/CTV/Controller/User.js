define(['../View/Header', '../View/Login', '../View/Alert'], function(Header, Login){

    var CTV = CTV || {};
    CTV.Controller =  CTV.Controller || {};

    CTV.Controller.User = Backbone.View.extend({
    	initialize: function(){
            if(this.options.isLoggedIn){
                new Header();
            } else {
                new Login();
            }
            if(this.options.hasLogginError){
                Alert.open({type:'error', body:'Your username or password is not correct.'})
            } else if(this.options.hasSignupError){
                Alert.open({type:'error', body:'The email you entered is already exists in our system.'})
            }
    	}
    });

   return CTV.Controller.User;
});