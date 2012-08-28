var how = {
    
    //This pulls the current list of users from the room
    init: function() {
        $(".main_works").click(function(e){
          e.preventDefault();
          $.blockUI({ 
              message: $("#main-how-popup").html(), 
              css: {
                position: 'absolute',
                top: '0px',
                left: '200px',
                textAlign: 'left',
                border: '0px'
              }
          });
          $(".popuplg-close a").click(function(e){
            e.preventDefault();
            $.unblockUI(); 
            //error.showError("error",$("#main-how-popup").html());
          });
          //error.showError("error",$("#main-how-popup").html());
        });
        
    }
    
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    //Set our constants
    how.init();
    
});
