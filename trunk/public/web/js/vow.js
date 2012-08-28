(function($) {
  $.fn.tweet = function(o){
    var s = $.extend({
      username: null,                   
      list: null,
      favorites: false
    }, o);

   

    function replacer (regex, replacement) {
      return function() {
        var returning = [];
        this.each(function() {
          returning.push(this.replace(regex, replacement));
        });
        return $(returning);
      };
    }

    return this.each(function(i, widget){

    });
  };
})(jQuery);