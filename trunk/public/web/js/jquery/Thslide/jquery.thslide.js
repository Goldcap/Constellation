/*=============================    
jquery.thslide.js 
v.0.1
Julien Decaudin - 04/2010
www.juliendecaudin.com
=============================*/

(function ($) {
    
    $.fn.thslide = function (callerSettings,item) {
    
        var settings = $.extend({
            navPreviousWrapper: '.thslide_nav_previous a',
            navNextWrapper: '.thslide_nav_next a',
            listWrapper: '.thslide_list ul',
            items: '.thslide_list ul li a',
            itemOffset: 280, //distance in pixel between the left-hand side of an item and the following one
            itemVisible: 3, //number of items visible by the user
            slideSpeedSlow: 1000, //speed of the items when sliding slowly
            slideSpeedFast: 400, //speed of the items when sliding fast            
            infiniteScroll: 0, //scroll infinitely through items (deactivated by default)
            scrollOver: 0, //scroll on rollover
            debug: 0
        }, callerSettings || {});

        settings.navPreviousWrapper = $(this).find(settings.navPreviousWrapper);
        settings.navNextWrapper = $(this).find(settings.navNextWrapper);
        settings.listWrapper = $(this).find(settings.listWrapper);
        settings.items = $(this).find(settings.items);
        settings.totalItem = $(settings.listWrapper).find('li').length; //total number of items
        settings.itemOffsetMax;
        settings.listMarginLeft;
        settings.slideSpeed;
        settings.easing = "easeInExpo";
        settings.locked = false;
        settings.scrollOn = false;
        settings.scrollTimer = 0;
        settings.navTimer;
        settings.offsetStatus = 0;
        settings.offsetCurrent = 2;
        settings.manualCarousel = 0;
        settings.slideDuration = 4000;
        settings.autoSlid = true;
        settings.wrapNum = settings.itemVisible;
        settings.wrapMid = settings.offsetCurrent;
        
        if (settings.totalItem < settings.itemVisible) {
          settings.wrapNum = settings.totalItem;
        }
        
        if (settings.totalItem <= 1) {
          $(".thslide_nav_previous").hide();
          $(".thslide_nav_next").hide();
          $(".barousel_image img").show();
          $("div[title=barousel_item_1]").show();
          $("#screening_1 img").attr("class","default");
          $("#screening_1 span").attr("class","default");
          return;
        }
        
        //start the carousel
        if (settings.manualCarousel == 0) {
            setTimeout( settings );
        }
        
        if (settings.totalItem < 3) {
          settings.offsetCurrent = $(".thslide_item").length;
          settings.wrapMid = settings.offsetCurrent;
        }
        
        if (item != undefined) {
          goToItem( settings, item );
        }
        
        // --- Init list & items offset
        updateListMargin(settings);
        settings.itemOffsetMax = parseInt($(settings.listWrapper).find('li').length) * settings.itemOffset - (settings.itemVisible * settings.itemOffset);
        settings.itemOffsetMax = -parseInt(settings.itemOffsetMax);

        // --- Init interactions
        //item select
        settings.items.click(function ( e ) {
            //e.preventDefault();
            var node = $(e.target);
            
						//if(node.parent().attr("href") != undefined) {
						//	window.location.href = node.parent().attr("href");
						//	return;
						//}
						//console.log("Set Duration on Click, 20 secs");
            
            id = $(this).attr("id");
            thecount = id.split("_");
            thecount = thecount[1];
            
            goToItem( settings, thecount );
            $("#screening_hidden_"+thecount).click();
            window.clearTimeout(settings.timerCarousel);
            settings.slideDuration = 20000;
            setTimeout( settings );
            
            return false;
            
        });
        
        //click
        $(settings.navPreviousWrapper).click(function () {
            //console.log("Set Duration on Nav Previous, 20 secs");
            window.clearTimeout(settings.timerCarousel);
            settings.slideDuration = 20000;
            setTimeout( settings );
            return false;
        });

        $(settings.navNextWrapper).click(function () {
            //console.log("Set Duration on Nav Next, 20 secs");
            window.clearTimeout(settings.timerCarousel);
            settings.slideDuration = 20000;
            setTimeout( settings );
            return false;
        });

        //mouse down
        $(settings.navPreviousWrapper).mousedown(function () {
            //console.log("MD Remove Timeout");
            window.clearTimeout(settings.timerCarousel);
            window.clearTimeout(settings.navTimer);
            settings.scrollOn = true;
            settings.slideSpeed = settings.slideSpeedFast;
            clearItemSelected();
            slideListPrevious(settings);
        });

        $(settings.navNextWrapper).mousedown(function () {
            //console.log("MD Remove Timeout");
            window.clearTimeout(settings.timerCarousel);
            window.clearTimeout(settings.navTimer);
            settings.scrollOn = true;
            settings.slideSpeed = settings.slideSpeedFast;
            clearItemSelected();
            slideListNext(settings);
        });

        //mouse up
        $(settings.navPreviousWrapper).mouseup(function () {
            settings.scrollOn = false;
            setItemSelected( settings );
            $("#screening_hidden_"+settings.offsetCurrent).click();
        });

        $(settings.navNextWrapper).mouseup(function () {
            settings.scrollOn = false;
            setItemSelected( settings );
            $("#screening_hidden_"+settings.offsetCurrent).click();
        });

        if (settings.scrollOver == 1) {
            //mouse over (rollover)
            $(settings.navPreviousWrapper).mouseover(function () {
                //if the nav isn't already scrolling
                if (!settings.scrollOn) {
                    settings.scrollOn = true;
                    settings.slideSpeed = settings.slideSpeedSlow;

                    var functionCall = function () { slideListPrevious(settings); };
                    settings.navTimer = window.setTimeout(functionCall, settings.scrollTimer);
                }
            });

            $(settings.navNextWrapper).mouseover(function () {
                //if the nav isn't already scrolling
                if (!settings.scrollOn) {
                    settings.scrollOn = true;
                    settings.slideSpeed = settings.slideSpeedSlow;

                    var functionCall = function () { slideListNext(settings); };
                    settings.navTimer = window.setTimeout(functionCall, settings.scrollTimer);
                }
            });

            //mouse out (rollout)
            $(settings.navPreviousWrapper).mouseout(function () {
                settings.scrollOn = false;
                setItemSelected( settings );
                $("#screening_hidden_"+settings.offsetCurrent).click();
            });

            $(settings.navNextWrapper).mouseout(function () {
                settings.scrollOn = false;
                setItemSelected( settings );
                $("#screening_hidden_"+settings.offsetCurrent).click();
            });
        }
        return this;
    };

    //slide the list to the left
    var slideListPrevious = function (settings) {
        if (!settings.locked && settings.scrollOn && ((parseInt(settings.listMarginLeft) + parseInt(settings.itemOffset) <= 0) || settings.infiniteScroll == 1)) {
            
            if (settings.offsetCount) {
              if (settings.offsetCount == settings.offsetStatus) {
                settings.scrollOn = false;
                settings.offsetStatus = 0;
                settings.offsetCount = null;
                setItemSelected( settings );
                return;
              }
              settings.offsetStatus += 1;
              
            }
            
            if (settings.offsetCurrent == 1) {
              settings.offsetCurrent = settings.totalItem;
            } else {
              settings.offsetCurrent -= 1;
            }
            
            settings.locked = true;

            if (settings.infiniteScroll == 1) {
                updateListFromBeginning(settings, function () {
                    slideListPreviousAction(settings);
                });
            } else {
                slideListPreviousAction(settings);
            }
        }
    };

    var slideListPreviousAction = function (settings) {
        var offsetUpdate = parseInt(settings.listMarginLeft) + parseInt(settings.itemOffset);

        /* DEBUG */
        if (settings.debug == 1) {
            console.log('offsetUpdate: ' + offsetUpdate);
        }

        $(settings.listWrapper).animate({
            marginLeft: offsetUpdate
        }, settings.slideSpeed, settings.easing, function () {
            if (settings.infiniteScroll == 0) {
                updateListMargin(settings);
            }
            settings.locked = false;
            
            if (settings.scrollOn) {
                slideListPrevious(settings);
            }
        });
    };

    //slide the list to the right
    var slideListNext = function (settings) {
        
        if (!settings.locked && settings.scrollOn && (((parseInt(settings.listMarginLeft) - parseInt(settings.itemOffset)) >= settings.itemOffsetMax) || settings.infiniteScroll == 1)) {
            
            if (settings.offsetCount) {
              if (settings.offsetCount == settings.offsetStatus) {
                settings.scrollOn = false;
                settings.offsetStatus = 0;
                settings.offsetCount = null;
                setItemSelected( settings );
                return;
              }
              settings.offsetStatus += 1;
            }
            
            if (settings.offsetCurrent < settings.totalItem) {
              settings.offsetCurrent += 1;
            } else {
              settings.offsetCurrent = 1;
            }
            
            settings.locked = true;

            var offsetUpdate = -parseInt(settings.itemOffset);

            /* DEBUG */
            if (settings.debug == 1) {
                console.log('offsetUpdate: ' + offsetUpdate);
            }
						
						$(settings.listWrapper).animate({
                marginLeft: offsetUpdate
            }, settings.slideSpeed, settings.easing, function () {
                if (settings.infiniteScroll == 0) {
                    updateListMargin(settings);
                } else {
                    updateListFromEnd(settings, null);
                }
                settings.locked = false;
                
                if (settings.scrollOn) {
                    slideListNext(settings);
                }
            });
        }
    };

    var initListItems = function (settings) {
        for (var i = 0; i < settings.totalItem; i++) {
            var pos = parseInt($(settings.listWrapper).find('li').length - 1) - parseInt(i);
            $(settings.listWrapper).find('li:eq(' + pos + ')').clone().insertBefore($(settings.listWrapper).find('li:first-child')).addClass('thslide_copy');
        }
        resetListMargin(settings);
    };

    var updateListFromBeginning = function (settings, callback) {
        var itemToMove = $(settings.listWrapper).find('li:last-child');
        $(settings.listWrapper).find('li:first-child').before(itemToMove);
        $(settings.listWrapper).css('marginLeft', -parseInt(settings.itemOffset));
        updateListMargin(settings);

        if (typeof callback == 'function') {
            return callback();
        }
    };

    var updateListFromEnd = function (settings, callback) {
        var itemToMove = $(settings.listWrapper).find('li:first-child');
        $(settings.listWrapper).find('li:last-child').after(itemToMove);
        $(settings.listWrapper).css('marginLeft', 0);
        updateListMargin(settings);

        if (typeof callback == 'function') {
            return callback();
        }
    };

    //update the left margin of the items list for scrolling effect
    var updateListMargin = function (settings) {
    	if ($(settings.listWrapper).css('marginLeft') != undefined)
        settings.listMarginLeft = $(settings.listWrapper).css('marginLeft').split('px')[0];
    };

    //reset the left margin of the items list for infinite scrolling
    var resetListMargin = function (settings) {
        //$(settings.listWrapper).css('marginLeft', - parseInt(settings.itemOffset) * settings.totalItem);
        $(settings.listWrapper).css('marginLeft', -parseInt(settings.itemOffset));
        updateListMargin(settings);
    };
    
    var clearItemSelected = function () {
      $(".thslide_list ul li[attachPoint=thslide_parent]").removeAttr("class");
			$(".thslide_list ul li[attachPoint=thslide_parent] img").attr("class","other");
      $(".thslide_list ul li[attachPoint=thslide_parent] .thslide_details").attr("class","other thslide_details");
    }
    
    var setItemSelected = function ( settings ) {
      //console.log("Set Item Selected with Duration " + settings.slideDuration);
      //console.log("Offset now #screening_"+settings.offsetCurrent);
			$("#screening_"+settings.offsetCurrent).attr("class","current");
      $("#screening_"+settings.offsetCurrent+" img").attr("class","default");
      $("#screening_"+settings.offsetCurrent+" .thslide_details").attr("class","default thslide_details");
      //console.log("clicking barousel broadcast");
      $(".barousel_image a").hide();
      $(".barousel_image a img").hide();
      $(".barousel_image a:nth-child("+settings.offsetCurrent+") img").show();
			$(".barousel_image a:nth-child("+settings.offsetCurrent+")").fadeIn('slow','linear');
      $(".barousel_content div").hide();
			$(".barousel_content div:nth-child("+settings.offsetCurrent+")").fadeIn('slow','linear');
    }
    
    var moveNext = function ( settings ) {
      //console.log("Move Next");
      var id = $(".thslide_list li.current").attr("id");
      if (! id) {
        console.log("No ID!");
        return;
      }
      thecount = id.split("_");
      thecount = thecount[1];
      //console.log("COUNT is " + thecount);
      if (thecount == settings.totalItem) {
        thecount = 0;
      }
      var anum = parseInt(thecount);
      var thiswhich = (anum + 1);
      goToItem( settings, thiswhich );
      //console.log("Moved Content to #screening_hidden_"+thiswhich);
      $("#screening_hidden_"+thiswhich).click();
      //start the carousel
      if (settings.manualCarousel == 0) {
          //console.log("Set Timeout Item Selected, 5 secs");
          settings.slideDuration = 5000;
          setTimeout( settings );
      }
    }
    
    var goToItem = function ( settings, thecount ) {
       
      //console.log("goToItem "+settings.offsetCurrent+" "+thecount)   
      if (thecount == settings.offsetCurrent) {
        return;
      }
      
      window.clearTimeout(settings.navTimer);
      settings.scrollOn = true;
      
      clearItemSelected();
      
      if (thecount > settings.offsetCurrent) {
        if ((settings.offsetCurrent < settings.wrapMid) && (thecount > (settings.wrapNum + 1))) {
          //console.log("Case 1, Go Left");
          var someresult = (parseInt(settings.totalItem) + parseInt(settings.offsetCurrent)) - thecount;
          settings.offsetCount = someresult;
          //console.log("Go to "+settings.offsetCount);
          //console.log(settings.totalItem + " + " +  settings.offsetCurrent + " - " + thecount + " = " + someresult);
          //return;
          slideListPrevious(settings);
        } else {
          //console.log("Case 1, Go Right");
          settings.offsetCount = thecount - settings.offsetCurrent;
          //console.log("Go to "+settings.offsetCount);
          slideListNext(settings);
        }
      }
      
      if (thecount < settings.offsetCurrent) {
        if ((settings.offsetCurrent > (settings.wrapNum + 1)) && (thecount < settings.wrapMid)) {
          //console.log("Case 2, Go Right");
          var someresult = (parseInt(thecount) + parseInt(settings.totalItem)) - parseInt(settings.offsetCurrent);
          settings.offsetCount = someresult;
          //console.log("Go to "+settings.offsetCount);
          slideListNext(settings);
        } else {
          //console.log("Case 2, Go Left");
          var someresult = parseInt(settings.offsetCurrent) - parseInt(thecount);
          settings.offsetCount = someresult;
          //console.log("Go to "+settings.offsetCount);
          slideListPrevious(settings);
        
        }
        
      }
    }
    
    var setTimeout = function( settings ) {
      //console.log("Setting timeout from Item Selected at " + settings.slideDuration + " Milliseconds");
      var loadItemCall = function () { moveNext( settings ); };
      settings.timerCarousel = window.setTimeout(loadItemCall, settings.slideDuration);
    }
    
})(jQuery);
