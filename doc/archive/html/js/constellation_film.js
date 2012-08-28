/*=============================    
jquery.barousel.js 
v.0.1
Julien Decaudin - 03/2010
www.juliendecaudin.com
=============================*/

(function ($) {

    $.fn.barousel = function (callerSettings) {
        var settings = $.extend({
            imageWrapper: '.barousel_image',
            contentWrapper: '.barousel_content',
            contentLinksWrapper: null,
            navWrapper: '.barousel_nav',
            slideDuration: 5000, //duration of each slide in milliseconds
            navType: 1, //1: boxes navigation; 2: prev/next navigation; 3: custom navigation
            fadeIn: 1, //fade between slides; activated by default
            fadeInSpeed: 600, //fade duration in milliseconds 
            manualCarousel: 0, //manual carousel if set to 1
            contentResize: 1, //resize content container
            contentResizeSpeed: 300, //content resize speed in milliseconds
            debug: 0
        }, callerSettings || {});

        settings.imageWrapper = $(this).find(settings.imageWrapper);
        settings.contentWrapper = $(this).find(settings.contentWrapper);
        settings.contentLinksWrapper = $(settings.contentLinksWrapper);
        settings.navWrapper = $(this).find(settings.navWrapper);
        settings.imageList = settings.imageWrapper.find('img').not('[class*=intro]'); //list of the items' background image (intro image is ignored)
        settings.contentList = settings.contentWrapper.find('div').not('[class*=intro]'); //list of the items' content (intro content is ignored)
        settings.contentLinksList = settings.contentLinksWrapper.find('a'); //list of the items' content links (optional)
        settings.imageIntro = settings.imageWrapper.find('img[class*=intro]');
        settings.contentIntro = settings.contentWrapper.find('div[class*=intro]');
        settings.currentIndex = 1; //index of the current displayed item
        settings.totalItem = settings.imageList.length; //total number of items
        settings.stopCarousel = 0; //flag to stop the carousel
        settings.timerCarousel; //timer for the carousel
        settings.navFreeze = 0; //flag to avoid clicking too fast on the nav
        settings.introActive = 0; //flag to know if there is an intro state and if it's active
        
        if (settings.imageWrapper.find('img[class*=intro]').length > 0) {
            settings.introActive = 1;
        }

        if (settings.totalItem == 1) {
            //$(settings.navWrapper).hide();            
        } else {
            //set the index of each image  
            settings.imageList.each(function (n) { this.index = n; });

            //set the index of each content  
            settings.contentList.each(function (n) { this.index = n; });

            //set the index of each content link (optional) 
            settings.contentLinksList.each(function (n) { this.index = n; });

            //return error if different number of images and contents
            if (settings.imageList.length != settings.contentList.length) {
                /* DEBUG */
                if (settings.debug == 1) console.log('[Barousel error] images and contents must be the same number');
                return this;
            }

            //init the default content height
            if (settings.contentResize == 1 && settings.introActive == 0) {
                $(settings.contentWrapper).height($(settings.contentList[settings.currentIndex]).height() + 10);
            }

            //init the content link default state (optional)
            if (settings.contentLinksWrapper != null) {
                $(settings.contentLinksList[settings.currentIndex]).addClass('current');
            }

            //build the navigation
            if (settings.navType == 1) {
                //items navigation type
                var strNavList = "<ul>";
                settings.imageList.each(function (n) {
                    var currentClass = "";
                    if (n == 0) currentClass = "current";
                    strNavList += "<li><a href='#' title='" + $(settings.contentList[n]).find('p.header').text() + "' class='" + currentClass + "'>&nbsp;</a></li>";
                });
                strNavList += "</ul>";
                settings.navWrapper.append(strNavList);
                settings.navList = settings.navWrapper.find('a'); //list of the items' nav link

                //set the index of each nav link
                settings.navList.each(function (n) { this.index = n; });

            } else if (settings.navType == 2) {
                //prev/next navigation type
                var strNavList = "<ul>";
                strNavList += "<li class='prev'><a href='#' title='previous'>&nbsp;</a></li>";
                strNavList += "<li class='next'><a href='#' title='next'>&nbsp;</a></li>";
                strNavList += "</ul><div class='counter'><span class='counter_current'>1</span>/<span class='counter_total'>" + settings.totalItem + "</span></div>";
                settings.navWrapper.append(strNavList);
                settings.navList = settings.navWrapper.find('a'); //list of the items' nav link
            } else if (settings.navType == 3) {
                //custom navigation [static build]
                settings.navList = settings.navWrapper.find('a'); //list of the items' nav link
                //set the index of each nav link
                settings.navList.each(function (n) { this.index = n; });
            }

            //init the navigation click event
            if (settings.navType == 1 || settings.navType == 3) {
                //items navigation type
                settings.navList.each(function (n) {
                    $(this).click(function () {
                      //console.log("Clicked "+n);
                      if (settings.navFreeze == 0) {
                          window.clearTimeout(settings.timerCarousel);
                          settings.stopCarousel = 1;
                          //console.log("Current Index is: " + settings.currentIndex);
                          if (settings.currentIndex != n || settings.introActive == 1) {
                              loadItem(settings, n);
                              settings.currentIndex = n;
                          }
                      }
                      settings.introActive = 0;
                      return false;
                    });
                });
            } else if (settings.navType == 2) {
                //prev/next navigation type
                settings.navList.each(function () {
                    $(this).click(function () {
                        //console.log("Clicked "+n);
                        if (settings.navFreeze == 0) {
                            window.clearTimeout(settings.timerCarousel);
                            settings.stopCarousel = 1;

                            if ($(this).parent().hasClass('prev')) {
                                var previousIndex;

                                if (parseInt(settings.currentIndex) == 0) {
                                    previousIndex = parseInt(settings.totalItem) - 1;
                                } else {
                                    previousIndex = parseInt(settings.currentIndex) - 1;
                                }
                                loadItem(settings, previousIndex);
                                settings.currentIndex = previousIndex;

                            } else if ($(this).parent().hasClass('next')) {
                                var nextIndex;

                                if (parseInt(settings.currentIndex) == (parseInt(settings.totalItem) - 1)) {
                                    nextIndex = 0;
                                } else {
                                    nextIndex = parseInt(settings.currentIndex) + 1;
                                }
                                loadItem(settings, nextIndex);
                                settings.currentIndex = nextIndex;
                            }
                        }
                        settings.introActive = 0;
                        return false;
                    });
                });
            }

            //start the carousel
            if (settings.manualCarousel == 0) {
                var loadItemCall = function () { loadItem(settings, 1); };
                settings.timerCarousel = window.setTimeout(loadItemCall, settings.slideDuration);
            }
        }

        return this;
    };

    var loadItem = function (settings, index) {
    
        //console.log("Load Item "+index);
        //reset the nav link current state
        if (settings.navType != 2) {
            settings.navList.each(function (n) { $(this).removeClass('current'); });
            $(settings.navList[index]).addClass('current');
        }

        //Change the background image then display the new content
        var currentImage;
        if (settings.introActive == 1) {
            currentImage = $(settings.imageIntro);
        } else {
            currentImage = $(settings.imageList[settings.currentIndex]);
        }
        var nextImage = $(settings.imageList[index]);

        /* DEBUG */
        if (settings.debug == 1) {
            console.log('[Barousel loadItem] currentImage:' + currentImage.attr('src'));
            console.log('[Barousel loadItem] nextImage:' + nextImage.attr('src'));
        }

        if (!currentImage.hasClass('default')) { currentImage.attr('class', 'previous'); }
        nextImage.attr('class', 'current');

        //fade-in effect
        if (settings.fadeIn == 0) {
            nextImage.show();
            currentImage.hide();
            loadModuleContent(settings, index);
        } else {
            settings.navFreeze = 1;
            $(settings.contentList).hide();
            nextImage.fadeIn(settings.fadeInSpeed, function () {
                currentImage.hide();
                currentImage.removeClass('previous');
                settings.navFreeze = 0;
            });
            loadModuleContent(settings, index);
        }

        //carousel functionnality (deactivated when the user click on an item)
        if (settings.stopCarousel == 0) {
            settings.currentIndex = index;
            var nextIndex;

            if (settings.currentIndex == settings.totalItem - 1) {
                nextIndex = 0;
            } else {
                nextIndex = parseInt(settings.currentIndex) + 1;
            }
            var loadItemCall = function () { loadItem(settings, nextIndex); };
            settings.timerCarousel = window.setTimeout(loadItemCall, settings.slideDuration);
        }
    };

    var loadModuleContent = function (settings, index) {
        if (settings.introActive == 1) {
            $(settings.contentIntro).hide();
            $(settings.contentWrapper).attr('class', '');
        }

        //Resize content        
        if (settings.contentResize == 1 && parseInt($(settings.contentWrapper).height()) != parseInt($(settings.contentList[index]).height() + 10)) {
            $(settings.contentWrapper).animate({
                height: $(settings.contentList[index]).height() + 10
            }, settings.contentResizeSpeed, function () {
                loadModuleContentAction(settings, index);
            });
        } else {
            loadModuleContentAction(settings, index);
        }

        //update counter for previous/next nav
        if (settings.navType == 2) {
            $(settings.navWrapper).find('.counter_current').text(index + 1);
        }
    };

    var loadModuleContentAction = function (settings, index) {
        //display the loaded content                
        $(settings.contentList).hide();
        $(settings.contentList[index]).show();

        if (settings.contentLinksWrapper != null) {
            $(settings.contentLinksList).removeClass('current');
            $(settings.contentLinksList[index]).addClass('current');
        }
    };

})(jQuery);
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
            itemOffset: 100, //distance in pixel between the left-hand side of an item and the following one
            itemVisible: 5, //number of items visible by the user
            slideSpeedSlow: 600, //speed of the items when sliding slowly
            slideSpeedFast: 200, //speed of the items when sliding fast            
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
        settings.easing;
        settings.locked = false;
        settings.scrollOn = false;
        settings.scrollTimer = 0;
        settings.navTimer;
        settings.offsetStatus = 0;
        settings.offsetCurrent = 3;
        settings.manualCarousel = 0;
        settings.slideDuration = 5000;
        settings.autoSlid = false;
        settings.wrapNum = settings.itemVisible;
        settings.wrapMid = settings.offsetCurrent;
        
        if (settings.totalItem < settings.itemVisible) {
          settings.wrapNum = settings.totalItem;
        }
        
        if (settings.totalItem == 1) {
          $(".thslide_nav_previous").hide();
          $(".thslide_nav_next").hide();
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
            e.preventDefault();
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
            settings.easing = "swing";
            clearItemSelected();
            slideListPrevious(settings);
        });

        $(settings.navNextWrapper).mousedown(function () {
            //console.log("MD Remove Timeout");
            window.clearTimeout(settings.timerCarousel);
            window.clearTimeout(settings.navTimer);
            settings.scrollOn = true;
            settings.slideSpeed = settings.slideSpeedFast;
            settings.easing = "swing";
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
                    settings.easing = "linear";

                    var functionCall = function () { slideListPrevious(settings); };
                    settings.navTimer = window.setTimeout(functionCall, settings.scrollTimer);
                }
            });

            $(settings.navNextWrapper).mouseover(function () {
                //if the nav isn't already scrolling
                if (!settings.scrollOn) {
                    settings.scrollOn = true;
                    settings.slideSpeed = settings.slideSpeedSlow;
                    settings.easing = "linear";

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
                settings.easing = "linear";
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
                    settings.easing = "linear";
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
        settings.listMarginLeft = $(settings.listWrapper).css('marginLeft').split('px')[0];
    };

    //reset the left margin of the items list for infinite scrolling
    var resetListMargin = function (settings) {
        //$(settings.listWrapper).css('marginLeft', - parseInt(settings.itemOffset) * settings.totalItem);
        $(settings.listWrapper).css('marginLeft', -parseInt(settings.itemOffset));
        updateListMargin(settings);
    };
    
    var clearItemSelected = function () {
      $(".thslide_item img").attr("class","ogcar_floatright other");
      $(".thslide_item span").attr("class","other");
    }
    
    var setItemSelected = function ( settings ) {
      //console.log("Set Item Selected with Duration " + settings.slideDuration);
      $("#screening_"+settings.offsetCurrent+" img").attr("class","ogcar_floatright default");
      $("#screening_"+settings.offsetCurrent+" span").attr("class","default");
      
    }
    
    var moveNext = function ( settings ) {
      //console.log("Move Next");
      var id = $(".thslide_item img.default").parent().attr("id");
      if (! id) {
        //console.log("No ID!");
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
      settings.easing = "swing";
      
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
/*
* jQuery jclock - Clock plugin - v 2.2.0
* http://plugins.jquery.com/project/jclock
*
* Copyright (c) 2007-2009 Doug Sparling <http://www.dougsparling.com>
* Licensed under the MIT License:
* http://www.opensource.org/licenses/mit-license.php
*/
(function($) {
 
  $.fn.jclock = function(options) {
    var version = '2.2.0';
 
    // options
    var opts = $.extend({}, $.fn.jclock.defaults, options);
         
    return this.each(function() {
      $this = $(this);
      $this.timerID = null;
      $this.running = false;
 
      // Record keeping for seeded clock
      $this.increment = 0;
      $this.lastCalled = new Date().getTime();
 
      var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
 
      $this.format = o.format;
      $this.utc = o.utc;
      // deprecate utc_offset (v 2.2.0)
      $this.utcOffset = (o.utc_offset != null) ? o.utc_offset : o.utcOffset;
      $this.seedTime = o.seedTime;
      $this.timeout = o.timeout;
 
      $this.css({
        fontFamily: o.fontFamily,
        fontSize: o.fontSize,
        backgroundColor: o.background,
        color: o.foreground
      });
 
      // %a
      $this.daysAbbrvNames = new Array(7);
      $this.daysAbbrvNames[0] = "Sun";
      $this.daysAbbrvNames[1] = "Mon";
      $this.daysAbbrvNames[2] = "Tue";
      $this.daysAbbrvNames[3] = "Wed";
      $this.daysAbbrvNames[4] = "Thu";
      $this.daysAbbrvNames[5] = "Fri";
      $this.daysAbbrvNames[6] = "Sat";
 
      // %A
      $this.daysFullNames = new Array(7);
      $this.daysFullNames[0] = "Sunday";
      $this.daysFullNames[1] = "Monday";
      $this.daysFullNames[2] = "Tuesday";
      $this.daysFullNames[3] = "Wednesday";
      $this.daysFullNames[4] = "Thursday";
      $this.daysFullNames[5] = "Friday";
      $this.daysFullNames[6] = "Saturday";
 
      // %b
      $this.monthsAbbrvNames = new Array(12);
      $this.monthsAbbrvNames[0] = "Jan";
      $this.monthsAbbrvNames[1] = "Feb";
      $this.monthsAbbrvNames[2] = "Mar";
      $this.monthsAbbrvNames[3] = "Apr";
      $this.monthsAbbrvNames[4] = "May";
      $this.monthsAbbrvNames[5] = "Jun";
      $this.monthsAbbrvNames[6] = "Jul";
      $this.monthsAbbrvNames[7] = "Aug";
      $this.monthsAbbrvNames[8] = "Sep";
      $this.monthsAbbrvNames[9] = "Oct";
      $this.monthsAbbrvNames[10] = "Nov";
      $this.monthsAbbrvNames[11] = "Dec";
 
      // %B
      $this.monthsFullNames = new Array(12);
      $this.monthsFullNames[0] = "January";
      $this.monthsFullNames[1] = "February";
      $this.monthsFullNames[2] = "March";
      $this.monthsFullNames[3] = "April";
      $this.monthsFullNames[4] = "May";
      $this.monthsFullNames[5] = "June";
      $this.monthsFullNames[6] = "July";
      $this.monthsFullNames[7] = "August";
      $this.monthsFullNames[8] = "September";
      $this.monthsFullNames[9] = "October";
      $this.monthsFullNames[10] = "November";
      $this.monthsFullNames[11] = "December";
 
      $.fn.jclock.startClock($this);
 
    });
  };
       
  $.fn.jclock.startClock = function(el) {
    $.fn.jclock.stopClock(el);
    $.fn.jclock.displayTime(el);
  }
 
  $.fn.jclock.stopClock = function(el) {
    if(el.running) {
      clearTimeout(el.timerID);
    }
    el.running = false;
  }
 
  $.fn.jclock.displayTime = function(el) {
    var time = $.fn.jclock.getTime(el);
    el.html(time);
    el.timerID = setTimeout(function(){$.fn.jclock.displayTime(el)},el.timeout);
  }
 
  $.fn.jclock.getTime = function(el) {
    if(typeof(el.seedTime) == 'undefined') {
      // Seed time not being used, use current time
      var now = new Date();
    } else {
      // Otherwise, use seed time with increment
      el.increment += new Date().getTime() - el.lastCalled;
      var now = new Date(el.seedTime + el.increment);
      el.lastCalled = new Date().getTime();
    }
 
    if(el.utc == true) {
      var localTime = now.getTime();
      var localOffset = now.getTimezoneOffset() * 60000;
      var utc = localTime + localOffset;
      var utcTime = utc + (3600000 * el.utcOffset);
      now = new Date(utcTime);
    }
 
    var timeNow = "";
    var i = 0;
    var index = 0;
    while ((index = el.format.indexOf("%", i)) != -1) {
      timeNow += el.format.substring(i, index);
      index++;
 
      // modifier flag
      //switch (el.format.charAt(index++)) {
      //}
      
      var property = $.fn.jclock.getProperty(now, el, el.format.charAt(index));
      index++;
      
      //switch (switchCase) {
      //}
 
      timeNow += property;
      i = index
    }
 
    timeNow += el.format.substring(i);
    return timeNow;
  };
 
  $.fn.jclock.getProperty = function(dateObject, el, property) {
 
    switch (property) {
      case "a": // abbrv day names
          return (el.daysAbbrvNames[dateObject.getDay()]);
      case "A": // full day names
          return (el.daysFullNames[dateObject.getDay()]);
      case "b": // abbrv month names
          return (el.monthsAbbrvNames[dateObject.getMonth()]);
      case "B": // full month names
          return (el.monthsFullNames[dateObject.getMonth()]);
      case "d": // day 01-31
          return ((dateObject.getDate() < 10) ? "0" : "") + dateObject.getDate();
      case "H": // hour as a decimal number using a 24-hour clock (range 00 to 23)
          return ((dateObject.getHours() < 10) ? "0" : "") + dateObject.getHours();
      case "I": // hour as a decimal number using a 12-hour clock (range 01 to 12)
          var hours = (dateObject.getHours() % 12 || 12);
          return ((hours < 10) ? "0" : "") + hours;
      case "m": // month number
          return ((dateObject.getMonth() < 10) ? "0" : "") + (dateObject.getMonth() + 1);
      case "M": // minute as a decimal number
          return ((dateObject.getMinutes() < 10) ? "0" : "") + dateObject.getMinutes();
      case "p": // either `am' or `pm' according to the given time value,
          // or the corresponding strings for the current locale
          return (dateObject.getHours() < 12 ? "am" : "pm");
      case "P": // either `AM' or `PM' according to the given time value,
          return (dateObject.getHours() < 12 ? "AM" : "PM");
      case "S": // second as a decimal number
          return ((dateObject.getSeconds() < 10) ? "0" : "") + dateObject.getSeconds();
      case "y": // two-digit year
          return dateObject.getFullYear().toString().substring(2);
      case "Y": // full year
          return (dateObject.getFullYear());
      case "%":
          return "%";
    }
 
  }
       
  // plugin defaults (24-hour)
  $.fn.jclock.defaults = {
    format: '%H:%M:%S',
    utcOffset: 0,
    utc: false,
    fontFamily: '',
    fontSize: '',
    foreground: '',
    background: '',
    seedTime: undefined,
    timeout: 1000 // 1000 = one second, 60000 = one minute
  };
 
})(jQuery);
// JavaScript Document
/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

(function($){$.fn.editable=function(target,options){if('disable'==target){$(this).data('disabled.editable',true);return;}
if('enable'==target){$(this).data('disabled.editable',false);return;}
if('destroy'==target){$(this).unbind($(this).data('event.editable')).removeData('disabled.editable').removeData('event.editable');return;}
var settings=$.extend({},$.fn.editable.defaults,{target:target},options);var plugin=$.editable.types[settings.type].plugin||function(){};var submit=$.editable.types[settings.type].submit||function(){};var buttons=$.editable.types[settings.type].buttons||$.editable.types['defaults'].buttons;var content=$.editable.types[settings.type].content||$.editable.types['defaults'].content;var element=$.editable.types[settings.type].element||$.editable.types['defaults'].element;var reset=$.editable.types[settings.type].reset||$.editable.types['defaults'].reset;var callback=settings.callback||function(){};var onedit=settings.onedit||function(){};var onsubmit=settings.onsubmit||function(){};var onreset=settings.onreset||function(){};var onerror=settings.onerror||reset;if(settings.tooltip){$(this).attr('title',settings.tooltip);}
settings.autowidth='auto'==settings.width;settings.autoheight='auto'==settings.height;return this.each(function(){var self=this;var savedwidth=$(self).width();var savedheight=$(self).height();$(this).data('event.editable',settings.event);if(!$.trim($(this).html())){$(this).html(settings.placeholder);}
$(this).bind(settings.event,function(e){if(true===$(this).data('disabled.editable')){return;}
if(self.editing){return;}
if(false===onedit.apply(this,[settings,self])){return;}
e.preventDefault();e.stopPropagation();if(settings.tooltip){$(self).removeAttr('title');}
if(0==$(self).width()){settings.width=savedwidth;settings.height=savedheight;}else{if(settings.width!='none'){settings.width=settings.autowidth?$(self).width():settings.width;}
if(settings.height!='none'){settings.height=settings.autoheight?$(self).height():settings.height;}}
if($(this).html().toLowerCase().replace(/(;|")/g,'')==settings.placeholder.toLowerCase().replace(/(;|")/g,'')){$(this).html('');}
self.editing=true;self.revert=$(self).html();$(self).html('');var form=$('<form />');if(settings.cssclass){if('inherit'==settings.cssclass){form.attr('class',$(self).attr('class'));}else{form.attr('class',settings.cssclass);}}
if(settings.style){if('inherit'==settings.style){form.attr('style',$(self).attr('style'));form.css('display',$(self).css('display'));}else{form.attr('style',settings.style);}}
var input=element.apply(form,[settings,self]);var input_content;if(settings.loadurl){var t=setTimeout(function(){input.disabled=true;content.apply(form,[settings.loadtext,settings,self]);},100);var loaddata={};loaddata[settings.id]=self.id;if($.isFunction(settings.loaddata)){$.extend(loaddata,settings.loaddata.apply(self,[self.revert,settings]));}else{$.extend(loaddata,settings.loaddata);}
$.ajax({type:settings.loadtype,url:settings.loadurl,data:loaddata,async:false,success:function(result){window.clearTimeout(t);input_content=result;input.disabled=false;}});}else if(settings.data){input_content=settings.data;if($.isFunction(settings.data)){input_content=settings.data.apply(self,[self.revert,settings]);}}else{input_content=self.revert;}
content.apply(form,[input_content,settings,self]);input.attr('name',settings.name);buttons.apply(form,[settings,self]);$(self).append(form);plugin.apply(form,[settings,self]);$(':input:visible:enabled:first',form).focus();if(settings.select){input.select();}
input.keydown(function(e){if(e.keyCode==27){e.preventDefault();reset.apply(form,[settings,self]);}});var t;if('cancel'==settings.onblur){input.blur(function(e){t=setTimeout(function(){reset.apply(form,[settings,self]);},500);});}else if('submit'==settings.onblur){input.blur(function(e){t=setTimeout(function(){form.submit();},200);});}else if($.isFunction(settings.onblur)){input.blur(function(e){settings.onblur.apply(self,[input.val(),settings]);});}else{input.blur(function(e){});}
form.submit(function(e){if(t){clearTimeout(t);}
e.preventDefault();if(false!==onsubmit.apply(form,[settings,self])){if(false!==submit.apply(form,[settings,self])){if($.isFunction(settings.target)){var str=settings.target.apply(self,[input.val(),settings]);$(self).html(str);self.editing=false;callback.apply(self,[self.innerHTML,settings]);if(!$.trim($(self).html())){$(self).html(settings.placeholder);}}else{var submitdata={};submitdata[settings.name]=input.val();submitdata[settings.id]=self.id;if($.isFunction(settings.submitdata)){$.extend(submitdata,settings.submitdata.apply(self,[self.revert,settings]));}else{$.extend(submitdata,settings.submitdata);}
if('PUT'==settings.method){submitdata['_method']='put';}
$(self).html(settings.indicator);var ajaxoptions={type:'POST',data:submitdata,dataType:'html',url:settings.target,success:function(result,status){if(ajaxoptions.dataType=='html'){$(self).html(result);}
self.editing=false;callback.apply(self,[result,settings]);if(!$.trim($(self).html())){$(self).html(settings.placeholder);}},error:function(xhr,status,error){onerror.apply(form,[settings,self,xhr]);}};$.extend(ajaxoptions,settings.ajaxoptions);$.ajax(ajaxoptions);}}}
$(self).attr('title',settings.tooltip);return false;});});this.reset=function(form){if(this.editing){if(false!==onreset.apply(form,[settings,self])){$(self).html(self.revert);self.editing=false;if(!$.trim($(self).html())){$(self).html(settings.placeholder);}
if(settings.tooltip){$(self).attr('title',settings.tooltip);}}}};});};$.editable={types:{defaults:{element:function(settings,original){var input=$('<input type="hidden" id="'+settings.name+'_editable"></input>');$(this).append(input);return(input);},content:function(string,settings,original){$(':input:first',this).val("");},reset:function(settings,original){original.reset(this);},buttons:function(settings,original){var form=this;if(settings.submit){if(settings.submit.match(/>$/)){var submit=$(settings.submit).click(function(){if(submit.attr("type")!="submit"){form.submit();}});}else{var submit=$('<button type="submit" />');submit.html(settings.submit);}
$(this).append(submit);}
if(settings.cancel){if(settings.cancel.match(/>$/)){var cancel=$(settings.cancel);}else{var cancel=$('<button type="cancel" />');cancel.html(settings.cancel);}
$(this).append(cancel);$(cancel).click(function(event){if($.isFunction($.editable.types[settings.type].reset)){var reset=$.editable.types[settings.type].reset;}else{var reset=$.editable.types['defaults'].reset;}
reset.apply(form,[settings,original]);return false;});}}},text:{element:function(settings,original){var input=$('<input />');if(settings.width!='none'){input.width(settings.width);}
if(settings.height!='none'){input.height(settings.height);}
input.attr('autocomplete','off');$(this).append(input);return(input);}},textarea:{element:function(settings,original){var textarea=$('<textarea id="'+settings.name+'_editable" />');if(settings.rows){textarea.attr('rows',settings.rows);}else if(settings.height!="none"){textarea.height(settings.height);}
if(settings.cols){textarea.attr('cols',settings.cols);}else if(settings.width!="none"){textarea.width(settings.width);}
$(this).append(textarea);return(textarea);}},select:{element:function(settings,original){var select=$('<select />');$(this).append(select);return(select);},content:function(data,settings,original){if(String==data.constructor){eval('var json = '+data);}else{var json=data;}
for(var key in json){if(!json.hasOwnProperty(key)){continue;}
if('selected'==key){continue;}
var option=$('<option />').val(key).append(json[key]);$('select',this).append(option);}
$('select',this).children().each(function(){if($(this).val()==json['selected']||$(this).text()==$.trim(original.revert)){$(this).attr('selected','selected');}});}}},addInputType:function(name,input){$.editable.types[name]=input;}};$.fn.editable.defaults={name:'value',id:'id',type:'text',width:'auto',height:'auto',event:'click.editable',onblur:'cancel',loadtype:'GET',loadtext:'Loading...',placeholder:'Click to edit',loaddata:{},submitdata:{},ajaxoptions:{}};})(jQuery);
(function($){$.fn.inputlimiter=function(options){var opts=$.extend({},$.fn.inputlimiter.defaults,options);if(opts.boxAttach&&!$('#'+opts.boxId).length)
{$('<div/>').appendTo("body").attr({id:opts.boxId,'class':opts.boxClass}).css({'position':'absolute'}).hide();if($.fn.bgiframe)
$('#'+opts.boxId).bgiframe();}
$(this).each(function(i){$(this).keyup(function(e){if($(this).val().length>opts.limit)
$(this).val($(this).val().substring(0,opts.limit));if(opts.boxAttach)
{$('#'+opts.boxId).css({'width':$(this).outerWidth()-($('#'+opts.boxId).outerWidth()-$('#'+opts.boxId).width())+'px','left':$(this).offset().left+'px','top':($(this).offset().top+$(this).outerHeight())-1+'px','z-index':2000});}
var charsRemaining=opts.limit-$(this).val().length;var remText=opts.remTextFilter(opts,charsRemaining);var limitText=opts.limitTextFilter(opts);if(opts.limitTextShow)
{$('#'+opts.boxId).html(remText+' '+limitText);var textWidth=$("<span/>").appendTo("body").attr({id:'19cc9195583bfae1fad88e19d443be7a','class':opts.boxClass}).html(remText+' '+limitText).innerWidth();$("#19cc9195583bfae1fad88e19d443be7a").remove();if(textWidth>$('#'+opts.boxId).innerWidth()){$('#'+opts.boxId).html(remText+'<br />'+limitText);}
$('#'+opts.boxId).show();}
else
$('#'+opts.boxId).html(remText).show();});$(this).keypress(function(e){if((!e.keyCode||(e.keyCode>46&&e.keyCode<90))&&$(this).val().length>=opts.limit)
return false;});$(this).blur(function(){if(opts.boxAttach)
{$('#'+opts.boxId).fadeOut('fast');}
else if(opts.remTextHideOnBlur)
{var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit==1?'':'s'));$('#'+opts.boxId).html(limitText);}});});};$.fn.inputlimiter.remtextfilter=function(opts,charsRemaining){var remText=opts.remText;if(charsRemaining==0&&opts.remFullText!=null){remText=opts.remFullText;}
remText=remText.replace(/\%n/g,charsRemaining);remText=remText.replace(/\%s/g,(opts.zeroPlural?(charsRemaining==1?'':'s'):(charsRemaining<=1?'':'s')));return remText;};$.fn.inputlimiter.limittextfilter=function(opts){var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit<=1?'':'s'));return limitText;};$.fn.inputlimiter.defaults={limit:255,boxAttach:true,boxId:'limiterBox',boxClass:'limiterBox',remText:'%n character%s remaining.',remTextFilter:$.fn.inputlimiter.remtextfilter,remTextHideOnBlur:true,remFullText:null,limitTextShow:true,limitText:'Field limited to %n character%s.',limitTextFilter:$.fn.inputlimiter.limittextfilter,zeroPlural:true};})(jQuery);
/* jQuery.head - v1.0.3 - K Reeve aka BinaryKitten
*
*	makes a Head Request via XMLHttpRequest (ajax) and returns an object/array of headers returned from the server
*	$.head(url, [data], [callback])
*		url			The url to which to place the head request
*		data		(optional) any data you wish to pass - see $.post and $.get for more info
*		callback	(optional) Function to call when the head request is complete.
*					This function will be passed an object containing the headers with
*					the object consisting of key/value pairs where the key is the header name and the
*					value it's corresponding value
*
*	for discussion and info please visit: http://binarykitten.me.uk/jQHead
*
* ------------ Version History -----------------------------------
* v1.0.3
* 	Fixed the zero-index issue with the for loop for the headers
* v1.0.2
* 	placed the function inside an enclosure
*
* v1.0.1
* 	The 1st version - based on $.post/$.get
*/

(function ($) {
  $.extend({
	head: function( url, data, callback, timeout ) {
	  if ( $.isFunction( data ) ) {
		  callback = data;
		  data = {};
	  }
    if (timeout == undefined) {
      timeout = 2000;
    }
	  return $.ajax({
		type: "HEAD",
		url: url,
		data: data,
		timeout: timeout,
		complete: function (XMLHttpRequest, textStatus) {
		  var headers = XMLHttpRequest.getAllResponseHeaders().split("\n");
		  var new_headers = {};
		  var l = headers.length;
		  for (var key=0;key<l;key++) {
			  if (headers[key].length != 0) {
				  header = headers[key].split(": ");
				  new_headers[header[0]] = header[1];
			  }
		  }
		  if ($.isFunction(callback)) {
			callback(new_headers);
		  }
		}
	  });
	}
  });
})(jQuery);
/*
* jQuery timepicker addon
* By: Trent Richardson [http://trentrichardson.com]
* Version 0.9.2
* Last Modified: 12/27/2010
* 
* Copyright 2010 Trent Richardson
* Dual licensed under the MIT and GPL licenses.
* http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
* http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
* 
* HERES THE CSS:
* .ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
* .ui-timepicker-div dl{ text-align: left; }
* .ui-timepicker-div dl dt{ height: 25px; }
* .ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
* .ui-timepicker-div td { font-size: 90%; }
*/

(function($) {

$.extend($.ui, { timepicker: { version: "0.9.2" } });

/* Time picker manager.
   Use the singleton instance of this class, $.timepicker, to interact with the time picker.
   Settings for (groups of) time pickers are maintained in an instance object,
   allowing multiple different settings on the same page. */

function Timepicker() {
	this.regional = []; // Available regional settings, indexed by language code
	this.regional[''] = { // Default regional settings
		currentText: 'Now',
		closeText: 'Done',
		ampm: false,
		timeFormat: 'hh:mm tt',
		timeOnlyTitle: 'Choose Time',
		timeText: 'Time',
		hourText: 'Hour',
		minuteText: 'Minute',
		secondText: 'Second'
	};
	this._defaults = { // Global defaults for all the datetime picker instances
		showButtonPanel: true,
		timeOnly: false,
		showHour: true,
		showMinute: true,
		showSecond: false,
		showTime: true,
		stepHour: 0.05,
		stepMinute: 0.05,
		stepSecond: 0.05,
		hour: 0,
		minute: 0,
		second: 0,
		hourMin: 0,
		minuteMin: 0,
		secondMin: 0,
		hourMax: 23,
		minuteMax: 59,
		secondMax: 59,
		minDateTime: null,
		maxDateTime: null,		
		hourGrid: 0,
		minuteGrid: 0,
		secondGrid: 0,
		alwaysSetTime: true,
		separator: ' ',
		altFieldTimeOnly: true
	};
	$.extend(this._defaults, this.regional['']);
}

$.extend(Timepicker.prototype, {
	$input: null,
	$altInput: null,
	$timeObj: null,
	inst: null,
	hour_slider: null,
	minute_slider: null,
	second_slider: null,
	hour: 0,
	minute: 0,
	second: 0,
	hourMinOriginal: null,
	minuteMinOriginal: null,
	secondMinOriginal: null,
	hourMaxOriginal: null,
	minuteMaxOriginal: null,
	secondMaxOriginal: null,
	ampm: '',
	formattedDate: '',
	formattedTime: '',
	formattedDateTime: '',

	/* Override the default settings for all instances of the time picker.
	   @param  settings  object - the new settings to use as defaults (anonymous object)
	   @return the manager object */
	setDefaults: function(settings) {
		extendRemove(this._defaults, settings || {});
		return this;
	},

	//########################################################################
	// Create a new Timepicker instance
	//########################################################################
	_newInst: function($input, o) {
		var tp_inst = new Timepicker(),
			inlineSettings = {};

		tp_inst.hour = tp_inst._defaults.hour;
		tp_inst.minute = tp_inst._defaults.minute;
		tp_inst.second = tp_inst._defaults.second;
		tp_inst.ampm = '';
		tp_inst.$input = $input;
			

		for (var attrName in this._defaults) {
			var attrValue = $input.attr('time:' + attrName);
			if (attrValue) {
				try {
					inlineSettings[attrName] = eval(attrValue);
				} catch (err) {
					inlineSettings[attrName] = attrValue;
				}
			}
		}
		tp_inst._defaults = $.extend({}, this._defaults, inlineSettings, o, {
			beforeShow: function(input, dp_inst) {
				if (o.altField)
					tp_inst.$altInput = $($.datepicker._get(dp_inst, 'altField'))
						.css({ cursor: 'pointer' })
						.focus(function(){
							$input.trigger("focus");
						});
				if ($.isFunction(o.beforeShow))
					o.beforeShow(input, dp_inst);
			},
			onChangeMonthYear: function(year, month, dp_inst) {
				// Update the time as well : this prevents the time from disappearing from the $input field.
				tp_inst._updateDateTime(dp_inst);
				if ($.isFunction(o.onChangeMonthYear))
					o.onChangeMonthYear(year, month, dp_inst);
			},
			onClose: function(dateText, dp_inst) {
				if (tp_inst.timeDefined === true && $input.val() != '')
					tp_inst._updateDateTime(dp_inst);
				if ($.isFunction(o.onClose))
					o.onClose(dateText, dp_inst);
			},
			timepicker: tp_inst // add timepicker as a property of datepicker: $.datepicker._get(dp_inst, 'timepicker');
		});

		// datepicker needs minDate/maxDate, timepicker needs minDateTime/maxDateTime..
		if(tp_inst._defaults.minDate !== undefined && tp_inst._defaults.minDate instanceof Date)
			tp_inst._defaults.minDateTime = new Date(tp_inst._defaults.minDate.getTime());
		if(tp_inst._defaults.minDateTime !== undefined && tp_inst._defaults.minDateTime instanceof Date)
			tp_inst._defaults.minDate = new Date(tp_inst._defaults.minDateTime.getTime());
		if(tp_inst._defaults.maxDate !== undefined && tp_inst._defaults.maxDate instanceof Date)
			tp_inst._defaults.maxDateTime = new Date(tp_inst._defaults.maxDate.getTime());
		if(tp_inst._defaults.maxDateTime !== undefined && tp_inst._defaults.maxDateTime instanceof Date)
			tp_inst._defaults.maxDate = new Date(tp_inst._defaults.maxDateTime.getTime());
			
		return tp_inst;
	},

	//########################################################################
	// add our sliders to the calendar
	//########################################################################
	_addTimePicker: function(dp_inst) {
		var currDT = (this.$altInput && this._defaults.altFieldTimeOnly) ?
				this.$input.val() + ' ' + this.$altInput.val() : 
				this.$input.val();

		this.timeDefined = this._parseTime(currDT);
		this._limitMinMaxDateTime(dp_inst, false);
		this._injectTimePicker();
	},

	//########################################################################
	// parse the time string from input value or _setTime
	//########################################################################
	_parseTime: function(timeString, withDate) {
		var regstr = this._defaults.timeFormat.toString()
				.replace(/h{1,2}/ig, '(\\d?\\d)')
				.replace(/m{1,2}/ig, '(\\d?\\d)')
				.replace(/s{1,2}/ig, '(\\d?\\d)')
				.replace(/t{1,2}/ig, '(am|pm|a|p)?')
				.replace(/\s/g, '\\s?') + '$',
			order = this._getFormatPositions(),
			treg;

		if (!this.inst) this.inst = $.datepicker._getInst(this.$input[0]);

		if (withDate || !this._defaults.timeOnly) {
			// the time should come after x number of characters and a space.
			// x = at least the length of text specified by the date format
			var dp_dateFormat = $.datepicker._get(this.inst, 'dateFormat');
			regstr = '.{' + dp_dateFormat.length + ',}\\s*' + this._defaults.separator.replace(/\s/g, '\\s?') + regstr;
		}

		treg = timeString.match(new RegExp(regstr, 'i'));

		if (treg) {
			if (order.t !== -1)
				this.ampm = ((treg[order.t] === undefined || treg[order.t].length === 0) ?
					'' :
					(treg[order.t].charAt(0).toUpperCase() == 'A') ? 'AM' : 'PM').toUpperCase();

			if (order.h !== -1) {
				if (this.ampm == 'AM' && treg[order.h] == '12') 
					this.hour = 0; // 12am = 0 hour
				else if (this.ampm == 'PM' && treg[order.h] != '12') 
					this.hour = (parseFloat(treg[order.h]) + 12).toFixed(0); // 12pm = 12 hour, any other pm = hour + 12
				else this.hour = Number(treg[order.h]);
			}

			if (order.m !== -1) this.minute = Number(treg[order.m]);
			if (order.s !== -1) this.second = Number(treg[order.s]);
			
			return true;

		}
		return false;
	},

	//########################################################################
	// figure out position of time elements.. cause js cant do named captures
	//########################################################################
	_getFormatPositions: function() {
		var finds = this._defaults.timeFormat.toLowerCase().match(/(h{1,2}|m{1,2}|s{1,2}|t{1,2})/g),
			orders = { h: -1, m: -1, s: -1, t: -1 };

		if (finds)
			for (var i = 0; i < finds.length; i++)
				if (orders[finds[i].toString().charAt(0)] == -1)
					orders[finds[i].toString().charAt(0)] = i + 1;

		return orders;
	},

	//########################################################################
	// generate and inject html for timepicker into ui datepicker
	//########################################################################
	_injectTimePicker: function() {
		var $dp = this.inst.dpDiv,
			o = this._defaults,
			tp_inst = this,
			// Added by Peter Medeiros:
			// - Figure out what the hour/minute/second max should be based on the step values.
			// - Example: if stepMinute is 15, then minMax is 45.
			hourMax = (o.hourMax - (o.hourMax % o.stepHour)).toFixed(0),
			minMax  = (o.minuteMax - (o.minuteMax % o.stepMinute)).toFixed(0),
			secMax  = (o.secondMax - (o.secondMax % o.stepSecond)).toFixed(0),
			dp_id = this.inst.id.toString().replace(/([^A-Za-z0-9_])/g, '');

		// Prevent displaying twice
		if ($dp.find("div#ui-timepicker-div-"+ dp_id).length === 0) {
			var noDisplay = ' style="display:none;"',
				html =	'<div class="ui-timepicker-div" id="ui-timepicker-div-' + dp_id + '"><dl>' +
						'<dt class="ui_tpicker_time_label" id="ui_tpicker_time_label_' + dp_id + '"' +
						((o.showTime) ? '' : noDisplay) + '>' + o.timeText + '</dt>' +
						'<dd class="ui_tpicker_time" id="ui_tpicker_time_' + dp_id + '"' +
						((o.showTime) ? '' : noDisplay) + '></dd>' +
						'<dt class="ui_tpicker_hour_label" id="ui_tpicker_hour_label_' + dp_id + '"' +
						((o.showHour) ? '' : noDisplay) + '>' + o.hourText + '</dt>',
				hourGridSize = 0,
				minuteGridSize = 0,
				secondGridSize = 0,
				size;
 
			if (o.showHour && o.hourGrid > 0) {
				html += '<dd class="ui_tpicker_hour">' +
						'<div id="ui_tpicker_hour_' + dp_id + '"' + ((o.showHour)   ? '' : noDisplay) + '></div>' +
						'<div style="padding-left: 1px"><table><tr>';

				for (var h = o.hourMin; h < hourMax; h += o.hourGrid) {
					hourGridSize++;
					var tmph = (o.ampm && h > 12) ? h-12 : h;
					if (tmph < 10) tmph = '0' + tmph;
					if (o.ampm) {
						if (h == 0) tmph = 12 +'a';
						else if (h < 12) tmph += 'a';
						else tmph += 'p';
					}
					html += '<td>' + tmph + '</td>';
				}

				html += '</tr></table></div>' +
						'</dd>';
			} else html += '<dd class="ui_tpicker_hour" id="ui_tpicker_hour_' + dp_id + '"' +
							((o.showHour) ? '' : noDisplay) + '></dd>';

			html += '<dt class="ui_tpicker_minute_label" id="ui_tpicker_minute_label_' + dp_id + '"' +
					((o.showMinute) ? '' : noDisplay) + '>' + o.minuteText + '</dt>';

			if (o.showMinute && o.minuteGrid > 0) {
				html += '<dd class="ui_tpicker_minute ui_tpicker_minute_' + o.minuteGrid + '">' +
						'<div id="ui_tpicker_minute_' + dp_id + '"' +
						((o.showMinute) ? '' : noDisplay) + '></div>' +
						'<div style="padding-left: 1px"><table><tr>';

				for (var m = o.minuteMin; m < minMax; m += o.minuteGrid) {
					minuteGridSize++;
					html += '<td>' + ((m < 10) ? '0' : '') + m + '</td>';
				}

				html += '</tr></table></div>' +
						'</dd>';
			} else html += '<dd class="ui_tpicker_minute" id="ui_tpicker_minute_' + dp_id + '"' +
							((o.showMinute) ? '' : noDisplay) + '></dd>';

			html += '<dt class="ui_tpicker_second_label" id="ui_tpicker_second_label_' + dp_id + '"' +
					((o.showSecond) ? '' : noDisplay) + '>' + o.secondText + '</dt>';

			if (o.showSecond && o.secondGrid > 0) {
				html += '<dd class="ui_tpicker_second ui_tpicker_second_' + o.secondGrid + '">' +
						'<div id="ui_tpicker_second_' + dp_id + '"' +
						((o.showSecond) ? '' : noDisplay) + '></div>' +
						'<div style="padding-left: 1px"><table><tr>';

				for (var s = o.secondMin; s < secMax; s += o.secondGrid) {
					secondGridSize++;
					html += '<td>' + ((s < 10) ? '0' : '') + s + '</td>';
				}

				html += '</tr></table></div>' +
						'</dd>';
			} else html += '<dd class="ui_tpicker_second" id="ui_tpicker_second_' + dp_id + '"'	+
							((o.showSecond) ? '' : noDisplay) + '></dd>';

			html += '</dl></div>';
			$tp = $(html);

				// if we only want time picker...
			if (o.timeOnly === true) {
				$tp.prepend(
					'<div class="ui-widget-header ui-helper-clearfix ui-corner-all">' +
						'<div class="ui-datepicker-title">' + o.timeOnlyTitle + '</div>' +
					'</div>');
				$dp.find('.ui-datepicker-header, .ui-datepicker-calendar').hide();
			}

			this.hour_slider = $tp.find('#ui_tpicker_hour_'+ dp_id).slider({
				orientation: "horizontal",
				value: this.hour,
				min: o.hourMin,
				max: hourMax,
				step: o.stepHour,
				slide: function(event, ui) {
					tp_inst.hour_slider.slider( "option", "value", ui.value);
					tp_inst._onTimeChange();
				}
			});

			// Updated by Peter Medeiros:
			// - Pass in Event and UI instance into slide function
			this.minute_slider = $tp.find('#ui_tpicker_minute_'+ dp_id).slider({
				orientation: "horizontal",
				value: this.minute,
				min: o.minuteMin,
				max: minMax,
				step: o.stepMinute,
				slide: function(event, ui) {
					// update the global minute slider instance value with the current slider value
					tp_inst.minute_slider.slider( "option", "value", ui.value);
					tp_inst._onTimeChange();
				}
			});

			this.second_slider = $tp.find('#ui_tpicker_second_'+ dp_id).slider({
				orientation: "horizontal",
				value: this.second,
				min: o.secondMin,
				max: secMax,
				step: o.stepSecond,
				slide: function(event, ui) {
					tp_inst.second_slider.slider( "option", "value", ui.value);
					tp_inst._onTimeChange();
				}
			});

			// Add grid functionality
			if (o.showHour && o.hourGrid > 0) {
				size = 100 * hourGridSize * o.hourGrid / (hourMax - o.hourMin);

				$tp.find(".ui_tpicker_hour table").css({
					width: size + "%",
					marginLeft: (size / (-2 * hourGridSize)) + "%",
					borderCollapse: 'collapse'
				}).find("td").each( function(index) {
					$(this).click(function() {
						var h = $(this).html();
						if(o.ampm)	{
							var ap = h.substring(2).toLowerCase(),
								aph = parseInt(h.substring(0,2));
							if (ap == 'a') {
								if (aph == 12) h = 0;
								else h = aph;
							} else if (aph == 12) h = 12;
							else h = aph + 12;
						}
						tp_inst.hour_slider.slider("option", "value", h);
						tp_inst._onTimeChange();
					}).css({
						cursor: 'pointer',
						width: (100 / hourGridSize) + '%',
						textAlign: 'center',
						overflow: 'hidden'
					});
				});
			}

			if (o.showMinute && o.minuteGrid > 0) {
				size = 100 * minuteGridSize * o.minuteGrid / (minMax - o.minuteMin);
				$tp.find(".ui_tpicker_minute table").css({
					width: size + "%",
					marginLeft: (size / (-2 * minuteGridSize)) + "%",
					borderCollapse: 'collapse'
				}).find("td").each(function(index) {
					$(this).click(function() {
						tp_inst.minute_slider.slider("option", "value", $(this).html());
						tp_inst._onTimeChange();
					}).css({
						cursor: 'pointer',
						width: (100 / minuteGridSize) + '%',
						textAlign: 'center',
						overflow: 'hidden'
					});
				});
			}

			if (o.showSecond && o.secondGrid > 0) {
				$tp.find(".ui_tpicker_second table").css({
					width: size + "%",
					marginLeft: (size / (-2 * secondGridSize)) + "%",
					borderCollapse: 'collapse'
				}).find("td").each(function(index) {
					$(this).click(function() {
						tp_inst.second_slider.slider("option", "value", $(this).html());
						tp_inst._onTimeChange();
					}).css({
						cursor: 'pointer',
						width: (100 / secondGridSize) + '%',
						textAlign: 'center',
						overflow: 'hidden'
					});
				});
			}

			var $buttonPanel = $dp.find('.ui-datepicker-buttonpane');
			if ($buttonPanel.length) $buttonPanel.before($tp);
			else $dp.append($tp);

			this.$timeObj = $('#ui_tpicker_time_'+ dp_id);

			if (this.inst !== null) {
				var timeDefined = this.timeDefined;
				this._onTimeChange();
				this.timeDefined = timeDefined;
			}
		}
	},

	//########################################################################
	// This function tries to limit the ability to go outside the 
	// min/max date range
	//########################################################################
	_limitMinMaxDateTime: function(dp_inst, adjustSliders){
		var o = this._defaults,
			dp_date = new Date(dp_inst.selectedYear, dp_inst.selectedMonth, dp_inst.selectedDay),
			tp_date = new Date(dp_inst.selectedYear, dp_inst.selectedMonth, dp_inst.selectedDay, this.hour, this.minute, this.second, 0);
		
		if(this._defaults.minDateTime !== null && dp_date){
			var minDateTime = this._defaults.minDateTime,
				minDateTimeDate = new Date(minDateTime.getFullYear(), minDateTime.getMonth(), minDateTime.getDate(), 0, 0, 0, 0);
			
			if(this.hourMinOriginal === null || this.minuteMinOriginal === null || this.secondMinOriginal === null){
				this.hourMinOriginal = o.hourMin;
				this.minuteMinOriginal = o.minuteMin;
				this.secondMinOriginal = o.secondMin;
			}
		
			if(minDateTimeDate.getTime() == dp_date.getTime()){
				this._defaults.hourMin = minDateTime.getHours();
				this._defaults.minuteMin = minDateTime.getMinutes();
				this._defaults.secondMin = minDateTime.getSeconds();

				if(this.hour < this._defaults.hourMin) this.hour = this._defaults.hourMin;
				if(this.minute < this._defaults.minuteMin) this.minute = this._defaults.minuteMin;
				if(this.second < this._defaults.secondMin) this.second = this._defaults.secondMin;
			}else{
				this._defaults.hourMin = this.hourMinOriginal;
				this._defaults.minuteMin = this.minuteMinOriginal;
				this._defaults.secondMin = this.secondMinOriginal;
			}
		}

		if(this._defaults.maxDateTime !== null && dp_date){
			var maxDateTime = this._defaults.maxDateTime,
				maxDateTimeDate = new Date(maxDateTime.getFullYear(), maxDateTime.getMonth(), maxDateTime.getDate(), 0, 0, 0, 0);
	
			if(this.hourMaxOriginal === null || this.minuteMaxOriginal === null || this.secondMaxOriginal === null){
				this.hourMaxOriginal = o.hourMax;
				this.minuteMaxOriginal = o.minuteMax;
				this.secondMaxOriginal = o.secondMax;
			}
		
			if(maxDateTimeDate.getTime() == dp_date.getTime()){
				this._defaults.hourMax = maxDateTime.getHours();
				this._defaults.minuteMax = maxDateTime.getMinutes();
				this._defaults.secondMax = maxDateTime.getSeconds();
				
				if(this.hour > this._defaults.hourMax){ this.hour = this._defaults.hourMax; }
				if(this.minute > this._defaults.minuteMax) this.minute = this._defaults.minuteMax;
				if(this.second > this._defaults.secondMax) this.second = this._defaults.secondMax;
			}else{
				this._defaults.hourMax = this.hourMaxOriginal;
				this._defaults.minuteMax = this.minuteMaxOriginal;
				this._defaults.secondMax = this.secondMaxOriginal;
			}
		}
				
		if(adjustSliders !== undefined && adjustSliders === true){
			this.hour_slider.slider("option", { min: this._defaults.hourMin, max: this._defaults.hourMax }).slider('value', this.hour);
			this.minute_slider.slider("option", { min: this._defaults.minuteMin, max: this._defaults.minuteMax }).slider('value', this.minute);
			this.second_slider.slider("option", { min: this._defaults.secondMin, max: this._defaults.secondMax }).slider('value', this.second);
		}

	},
	
	//########################################################################
	// when a slider moves, set the internal time...
	// on time change is also called when the time is updated in the text field
	//########################################################################
	_onTimeChange: function() {
		var hour   = (this.hour_slider) ? this.hour_slider.slider('value') : false,
			minute = (this.minute_slider) ? this.minute_slider.slider('value') : false,
			second = (this.second_slider) ? this.second_slider.slider('value') : false;

		if (hour !== false) hour = parseInt(hour,10);
		if (minute !== false) minute = parseInt(minute,10);
		if (second !== false) second = parseInt(second,10);

		var ampm = (hour < 12) ? 'AM' : 'PM';
			
		// If the update was done in the input field, the input field should not be updated.
		// If the update was done using the sliders, update the input field.
		var hasChanged = (hour != this.hour || minute != this.minute || second != this.second || (this.ampm.length > 0 && this.ampm != ampm));
		
		if (hasChanged) {

			if (hour !== false) {
				this.hour = hour;
				if (this._defaults.ampm) this.ampm = ampm;
			}
			if (minute !== false) this.minute = minute;
			if (second !== false) this.second = second;

		}
		this._formatTime();
		if (this.$timeObj) this.$timeObj.text(this.formattedTime);
		this.timeDefined = true;
		if (hasChanged) this._updateDateTime();
	},

	//########################################################################
	// format the time all pretty...
	//########################################################################
	_formatTime: function(time, format, ampm) {
		if (ampm == undefined) ampm = this._defaults.ampm;
		time = time || { hour: this.hour, minute: this.minute, second: this.second, ampm: this.ampm };
		var tmptime = format || this._defaults.timeFormat.toString();

		if (ampm) {
			var hour12 = ((time.ampm == 'AM') ? (time.hour) : (time.hour % 12));
			hour12 = (Number(hour12) === 0) ? 12 : hour12;
			tmptime = tmptime.toString()
				.replace(/hh/g, ((hour12 < 10) ? '0' : '') + hour12)
				.replace(/h/g, hour12)
				.replace(/mm/g, ((time.minute < 10) ? '0' : '') + time.minute)
				.replace(/m/g, time.minute)
				.replace(/ss/g, ((time.second < 10) ? '0' : '') + time.second)
				.replace(/s/g, time.second)
				.replace(/TT/g, time.ampm.toUpperCase())
				.replace(/tt/g, time.ampm.toLowerCase())
				.replace(/T/g, time.ampm.charAt(0).toUpperCase())
				.replace(/t/g, time.ampm.charAt(0).toLowerCase());
		} else {
			tmptime = tmptime.toString()
				.replace(/hh/g, ((time.hour < 10) ? '0' : '') + time.hour)
				.replace(/h/g, time.hour)
				.replace(/mm/g, ((time.minute < 10) ? '0' : '') + time.minute)
				.replace(/m/g, time.minute)
				.replace(/ss/g, ((time.second < 10) ? '0' : '') + time.second)
				.replace(/s/g, time.second);
			tmptime = $.trim(tmptime.replace(/t/gi, ''));
		}

		if (arguments.length) return tmptime;
		else this.formattedTime = tmptime;
	},

	//########################################################################
	// update our input with the new date time..
	//########################################################################
	_updateDateTime: function(dp_inst) {
		dp_inst = this.inst || dp_inst,
			dt = new Date(dp_inst.selectedYear, dp_inst.selectedMonth, dp_inst.selectedDay),
			dateFmt = $.datepicker._get(dp_inst, 'dateFormat'),
			formatCfg = $.datepicker._getFormatConfig(dp_inst),
			timeAvailable = dt !== null && this.timeDefined;
		this.formattedDate = $.datepicker.formatDate(dateFmt, (dt === null ? new Date() : dt), formatCfg);
		var formattedDateTime = this.formattedDate;
		if (dp_inst.lastVal !== undefined && (dp_inst.lastVal.length > 0 && this.$input.val().length === 0))
			return;

		if (this._defaults.timeOnly === true) {
			formattedDateTime = this.formattedTime;
		} else if (this._defaults.timeOnly !== true && (this._defaults.alwaysSetTime || timeAvailable)) {			
			formattedDateTime += this._defaults.separator + this.formattedTime;
		}

		this.formattedDateTime = formattedDateTime;
		
		if (this.$altInput && this._defaults.altFieldTimeOnly === true)	{
			this.$altInput.val(this.formattedTime);
			this.$input.val(this.formattedDate);
		} else if(this.$altInput) {
			this.$altInput.val(formattedDateTime);
			this.$input.val(formattedDateTime);
		} else {
			this.$input.val(formattedDateTime);
		}
		
		this.$input.trigger("change");
	}

});

$.fn.extend({
	//########################################################################
	// shorthand just to use timepicker..
	//########################################################################
	timepicker: function(o) {
		o = o || {};
		var tmp_args = arguments;

		if (typeof o == 'object') tmp_args[0] = $.extend(o, { timeOnly: true });

		return $(this).each(function() {
			$.fn.datetimepicker.apply($(this), tmp_args);
		});
	},

	//########################################################################
	// extend timepicker to datepicker
	//########################################################################
	datetimepicker: function(o) {
		o = o || {};
		var $input = this,
			tmp_args = arguments;

		if (typeof(o) == 'string'){
			if(o == 'getDate') 
				return $.fn.datepicker.apply($(this[0]), tmp_args);
			else 
				return this.each(function() {
					var $t = $(this);
					$t.datepicker.apply($t, tmp_args);
				});
		}
		else
			return this.each(function() {
				var $t = $(this);
				$t.datepicker($.timepicker._newInst($t, o)._defaults);
			});
	}
});

//########################################################################
// the bad hack :/ override datepicker so it doesnt close on select
// inspired: http://stackoverflow.com/questions/1252512/jquery-datepicker-prevent-closing-picker-when-clicking-a-date/1762378#1762378
//########################################################################
$.datepicker._base_selectDate = $.datepicker._selectDate;
$.datepicker._selectDate = function (id, dateStr) {
	var inst = this._getInst($(id)[0]),
		tp_inst = this._get(inst, 'timepicker');

	if (tp_inst) {
		tp_inst._limitMinMaxDateTime(inst, true);
		inst.inline = inst.stay_open = true;
		this._base_selectDate(id, dateStr);
		inst.inline = inst.stay_open = false;
		this._notifyChange(inst);
		this._updateDatepicker(inst);
	}
	else this._base_selectDate(id, dateStr);
};

//#############################################################################################
// second bad hack :/ override datepicker so it triggers an event when changing the input field
// and does not redraw the datepicker on every selectDate event
//#############################################################################################
$.datepicker._base_updateDatepicker = $.datepicker._updateDatepicker;
$.datepicker._updateDatepicker = function(inst) {
	if (typeof(inst.stay_open) !== 'boolean' || inst.stay_open === false) {
				
		this._base_updateDatepicker(inst);
		
		// Reload the time control when changing something in the input text field.
		var tp_inst = this._get(inst, 'timepicker');
		if(tp_inst) tp_inst._addTimePicker(inst);
	}
};

//#######################################################################################
// third bad hack :/ override datepicker so it allows spaces and colan in the input field
//#######################################################################################
$.datepicker._base_doKeyPress = $.datepicker._doKeyPress;
$.datepicker._doKeyPress = function(event) {
	var inst = $.datepicker._getInst(event.target),
		tp_inst = $.datepicker._get(inst, 'timepicker');

	if (tp_inst) {
		if ($.datepicker._get(inst, 'constrainInput')) {
			var ampm = tp_inst._defaults.ampm,
				datetimeChars = tp_inst._defaults.timeFormat.toString()
								.replace(/[hms]/g, '')
								.replace(/TT/g, ampm ? 'APM' : '')
								.replace(/T/g, ampm ? 'AP' : '')
								.replace(/tt/g, ampm ? 'apm' : '')
								.replace(/t/g, ampm ? 'ap' : '') +
								" " +
								tp_inst._defaults.separator +
								$.datepicker._possibleChars($.datepicker._get(inst, 'dateFormat')),
				chr = String.fromCharCode(event.charCode === undefined ? event.keyCode : event.charCode);
			return event.ctrlKey || (chr < ' ' || !datetimeChars || datetimeChars.indexOf(chr) > -1);
		}
	}
	
	return $.datepicker._base_doKeyPress(event);
};

//#######################################################################################
// Override key up event to sync manual input changes.
//#######################################################################################
$.datepicker._base_doKeyUp = $.datepicker._doKeyUp;
$.datepicker._doKeyUp = function (event) {
	var inst = $.datepicker._getInst(event.target),
		tp_inst = $.datepicker._get(inst, 'timepicker');

	if (tp_inst) {
		if (tp_inst._defaults.timeOnly && (inst.input.val() != inst.lastVal)) {
			try {
				$.datepicker._updateDatepicker(inst);
			}
			catch (err) {
				$.datepicker.log(err);
			}
		}
	}

	return $.datepicker._base_doKeyUp(event);
};

//#######################################################################################
// override "Today" button to also grab the time.
//#######################################################################################
$.datepicker._base_gotoToday = $.datepicker._gotoToday;
$.datepicker._gotoToday = function(id) {
	this._base_gotoToday(id);
	this._setTime(this._getInst($(id)[0]), new Date());
};

//#######################################################################################
// Create our own set time function
//#######################################################################################
$.datepicker._setTime = function(inst, date) {
	var tp_inst = this._get(inst, 'timepicker');
	if (tp_inst) {
		var defaults = tp_inst._defaults,
			// calling _setTime with no date sets time to defaults
			hour = date ? date.getHours() : defaults.hour,
			minute = date ? date.getMinutes() : defaults.minute,
			second = date ? date.getSeconds() : defaults.second;

		//check if within min/max times..
		if ((hour < defaults.hourMin || hour > defaults.hourMax) || (minute < defaults.minuteMin || minute > defaults.minuteMax) || (second < defaults.secondMin || second > defaults.secondMax)) {
			hour = defaults.hourMin;
			minute = defaults.minuteMin;
			second = defaults.secondMin;
		}

		if (tp_inst.hour_slider) tp_inst.hour_slider.slider('value', hour);
		else tp_inst.hour = hour;
		if (tp_inst.minute_slider) tp_inst.minute_slider.slider('value', minute);
		else tp_inst.minute = minute;
		if (tp_inst.second_slider) tp_inst.second_slider.slider('value', second);
		else tp_inst.second = second;

		tp_inst._onTimeChange();
		tp_inst._updateDateTime(inst);
	}
};

//#######################################################################################
// Create new public method to set only time, callable as $().datepicker('setTime', date)
//#######################################################################################
$.datepicker._setTimeDatepicker = function(target, date, withDate) {
	var inst = this._getInst(target),
		tp_inst = this._get(inst, 'timepicker');

	if (tp_inst) {
		this._setDateFromField(inst);
		var tp_date;
		if (date) {
			if (typeof date == "string") {
				tp_inst._parseTime(date, withDate);
				tp_date = new Date();
				tp_date.setHours(tp_inst.hour, tp_inst.minute, tp_inst.second);
			}
			else tp_date = new Date(date.getTime());
			if (tp_date.toString() == 'Invalid Date') tp_date = undefined;
		}
		this._setTime(inst, tp_date);
	}

};

//#######################################################################################
// override setDate() to allow setting time too within Date object
//#######################################################################################
$.datepicker._base_setDateDatepicker = $.datepicker._setDateDatepicker;
$.datepicker._setDateDatepicker = function(target, date) {
	var inst = this._getInst(target),
	tp_date = (date instanceof Date) ? new Date(date.getTime()) : date;

	this._updateDatepicker(inst);
	this._base_setDateDatepicker.apply(this, arguments);
	this._setTimeDatepicker(target, tp_date, true);
};

//#######################################################################################
// override getDate() to allow getting time too within Date object
//#######################################################################################
$.datepicker._base_getDateDatepicker = $.datepicker._getDateDatepicker;
$.datepicker._getDateDatepicker = function(target, noDefault) {
	var inst = this._getInst(target),
		tp_inst = this._get(inst, 'timepicker');

	if (tp_inst) {
		this._setDateFromField(inst, noDefault);
		var date = this._getDate(inst);
		if (date && tp_inst._parseTime($(target).val(), true)) date.setHours(tp_inst.hour, tp_inst.minute, tp_inst.second);
		return date;
	}
	return this._base_getDateDatepicker(target, noDefault);
};

//#######################################################################################
// jQuery extend now ignores nulls!
//#######################################################################################
function extendRemove(target, props) {
	$.extend(target, props);
	for (var name in props)
		if (props[name] === null || props[name] === undefined)
			target[name] = props[name];
	return target;
}

$.timepicker = new Timepicker(); // singleton instance
$.timepicker.version = "0.9.2";

})(jQuery);
/// jQuery plugin to add support for SwfUpload
/// (c) 2008 Steven Sanderson

(function($) {
    $.fn.makeAsyncUploader = function(options) {
        return this.each(function() {
            // Put in place a new container with a unique ID
            var id = $(this).attr("id");
            var container = $("<span class='asyncUploader'/>");
            container.append($("<span id='placeHolder' class='progressContainer'/>"));
            container.append($("<div class='progressbar'> <div>&nbsp;</div> </div>"));
            container.append($("<span id='" + id + "_completedMessage' class='messageContainer'/>"));
            container.append($("<span id='" + id + "_uploading'><span class='uploadmessage'></span><span class='cancelContainer'><input type='button' value='Cancel' class='cancelButton' /></span></span>"));
            container.append($("<span id='" + id + "_swf'/>"));
            container.append($("<input type='hidden' name='" + id + "_filename'/>"));
            container.append($("<input type='hidden' name='" + id + "_guid'/>"));
            $(this).before(container).remove();
            $("#placeHolder", container).show();
            $("div.progressbar", container).hide();
            $("span[id$=_uploading]", container).hide();

            // Instantiate the uploader SWF
            var swfu;
            var width = 160, height = 22;
            
            if (options) {
                width = options.width || width;
                height = options.height || height;
            }
            
            
            var defaults = {
                flash_url: "swfupload.swf",
                upload_url: "/services/ImageManager",
                file_size_limit: "3 MB",
                file_types: "*.*",
                file_types_description: "All Files",

                button_image_url: "blankButton.png",
                button_width: width,
                button_height: height,
                button_placeholder_id: id + "_swf",
                button_text: '<span class="buttonText">Choose file</span>',
                button_text_style: '.buttonText { text-indent: 10; font-size: 13pt; font-family: arial; } .buttonAnother { color: #000000; font-size: 13pt; font-family: arial; }',
                button_text_left_padding: (width - 70) / 2,
                button_text_top_padding: 1,

                // Called when the user chooses a new file from the file browser prompt (begins the upload)
                file_queued_handler: function(file) { swfu.startUpload(); },

                // Called when a file doesn't even begin to upload, because of some error
                file_queue_error_handler: function(file, code, msg) { alert("Sorry, your file wasn't uploaded: " + msg); },

                // Called when an error occurs during upload
                upload_error_handler: function(file, code, msg) { alert("Sorry, your file wasn't uploaded: " + msg); },

                // Called when upload is beginning (switches controls to uploading state)
                upload_start_handler: function() {
                    $("#placeHolder", container).hide();
                    swfu.setButtonDimensions(0, height);
                    $("input[name$=_filename]", container).val("");
                    $("input[name$=_guid]", container).val("");
                    $("div.progressbar div", container).css("width", "0px");
                    $("div.progressbar", container).show();
                    $("span[id$=_uploading]", container).show();
                    $("span[id$=_completedMessage]", container).html("").hide();

                    if (options.disableDuringUpload)
                        $(options.disableDuringUpload).attr("disabled", "disabled");
                },

                // Called when upload completed successfully (puts success details into hidden fields)
                upload_success_handler: function(file, response) {
                    $("input[name$=_filename]", container).val(file.name);
                    $("input[name$=_guid]", container).val(response);
                    console.log("Completed Upload");
                    
                    $("span[id$=_completedMessage]", container).html("Successfully Uploaded:<br /><b>{0}</b> ({1} KB)"
                                .replace("{0}", file.name)
                                .replace("{1}", Math.round(file.size / 1024))
                            );
                    swfu.setButtonText('<span class="buttonAnother">Choose Another</span>');
                },

                // Called when upload is finished (either success or failure - reverts controls to non-uploading state)
                upload_complete_handler: function() {
                    var clearup = function() {
                        $("div.progressbar", container).hide();
                        $("span[id$=_completedMessage]", container).show();
                        $("span[id$=_uploading]", container).hide();
                        swfu.setButtonDimensions(width, height);
                    };
                    if ($("input[name$=_filename]", container).val() != "") // Success
                        $("div.progressbar div", container).animate({ width: "100%" }, { duration: "fast", queue: false, complete: clearup });
                    else // Fail
                        clearup();

                    if (options.disableDuringUpload)
                        $(options.disableDuringUpload).removeAttr("disabled");
                },

                // Called periodically during upload (moves the progess bar along)
                upload_progress_handler: function(file, bytes, total) {
                    var percent = 100 * bytes / total;
                    $("div.progressbar div", container).animate({ width: percent + "%" }, { duration: 500, queue: false });
                }
            };
            swfu = new SWFUpload($.extend(defaults, options || {}));
            //swfu.setButtonText("<font face='Arial' size='13pt'>Choose File</font>");
            
            // Called when user clicks "cancel" (forces the upload to end, and eliminates progress bar immediately)
            $("span[id$=_uploading] input[type='button']", container).click(function() {
                swfu.cancelUpload(null, false);
            });

            // Give the effect of preserving state, if requested
            if (options.existingFilename || "" != "") {
                $("span[id$=_completedMessage]", container).html("Successfully Uploaded:<br /><b>{0}</b> ({1} KB)"
                                .replace("{0}", options.existingFilename)
                                .replace("{1}", options.existingFileSize ? Math.round(options.existingFileSize / 1024) : "?")
                            ).show();
                $("input[name$=_filename]", container).val(options.existingFilename);
            }
            if (options.existingGuid || "" != "")
                $("input[name$=_guid]", container).val(options.existingGuid);
        });
    }
})(jQuery);
var screenings_carousel = {
  
  //Setup Our Initial Carousel Object
  init: function() {
    if ($('#barousel_thslide').length > 0) {
    //http://labs.juliendecaudin.com/barousel/#th_download
    $('#barousel_thslide').barousel({
        navWrapper: '#thslide_barousel_nav .thslide_hidden',
        manualCarousel: 1,
        navType: 3
    });

    $('#thslide_barousel_nav').thslide({
        infiniteScroll: 1,
        itemOffset: 93
    });
   }
  }

}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  //console.log("Screenings Carousel");
	if ($('#barousel_thslide').length > 0) {
	 screenings_carousel.init();
	}
});
// JavaScript Document
$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	$('.screening_list').jScrollPane({
    verticalDragMinHeight: 30,
		verticalDragMaxHeight: 30,
    verticalGutter: 0	
  });
	
});
	
	
var activityHistory = {

    errorSleepTime: 5000,
    cursor: null,
    host: null,
    port: null,
    instance: null,
    room: null,
    user: null,
    destination: null,
    inbox: null,
    listcount: 7,
    
    construct: function() {
      activityHistory.host = $("#host").html();
      activityHistory.port = parseInt($("#port").html()) + 14090;
      activityHistory.room = $("#room").html();
      activityHistory.film = $("#film").html();
      activityHistory.destination = activityHistory.host + ":" + activityHistory.port;
      activityHistory.inbox = $("#constellation_map");
    },
    
    //This pulls the last five events from the room
    activityHistory: function() {
        var args = {"room": activityHistory.room,
                    "film": activityHistory.film,
                    "location" : activityHistory.location};
        $.ajax({url: "/services/history/init?i="+activityHistory.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: activityHistory.onHistorySuccess,
                error: activityHistory.onError});
    },
    
    //Updates our Activity List
    onHistorySuccess: function(response) {
        try {
            //console.log("Activity Init Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            //console.log(response.activities.length, " activites from init");
            
            //Clear out the temp array
            for (var i = 0; i<response.activities.length-1; i++) {
              //console.log(i);
              if (i == activityHistory.listcount) break;
              activityHistory.showActivity(response.activities[i],false);
            }
            activityHistory.setColors();
        } catch (e) {
            activityHistory.onError();
            return;
        }
    },
    
    //This pulls new users when they arrive in the room 
    poll: function() {
        var args = {"room": activityHistory.room,
                    "film": activityHistory.film,
                    "location": activityHistory.location};
        $.ajax({url: "/services/history/update?i="+activityHistory.destination,
                type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                //timeout: activityHistory.errorSleepTime,
                success: activityHistory.onPollSuccess,
                error: activityHistory.onPollError
                });
    },
    
    //Show the users who arrived
    onPollSuccess: function(response) {
        try {
            //console.log("Activity Poll Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            //console.log(response.users.length, "new users");
            for (var i = 0; i < response.items.length; i++) {
              activityHistory.showActivity(response.items[i],true);
            }
            activityHistory.setColors();
        } catch (e) {
            activityHistory.onError();
        }
        //activityHistory.errorSleepTime = 100;
        console.log("Restart Poll");
        window.setTimeout(activityHistory.poll, 0);
    },
    
    onPollError: function(response) {
        //activityHistory.errorSleepTime *= 2;
        //console.log("Poll error; sleeping for", activityHistory.errorSleepTime, "ms");
        console.log("Restart Poll");
        window.setTimeout(activityHistory.poll, 0);
    },
    
    onError: function(response) {
        //activityHistory.errorSleepTime *= 2;
        console.log("Poll error; sleeping for", activityHistory.errorSleepTime, "ms");
        //window.setTimeout(activityHistory.poll, activityHistory.errorSleepTime);
    },
    
    showActivity: function(activity,update,i) {
      if ((activityHistory.location == 'screening') || (activityHistory.location == 'film')) {
        html = '<li id="constellation-'+activity.activity_id+'">'+activity.message+'</li>';
        if (update) {
          $("#constellation_map").prepend(html);
        } else {
          $("#constellation_map").append(html);
        }
        activityHistory.removeActivity();
      }
    },
    
    setColors: function() {
      for (i=2;i<=activityHistory.listcount;i++) {
        //console.log("Setting Color for "+i);
        //console.log($("#constellation_map li:nth-child("+i+")").length);
        $("#constellation_map li:nth-child("+i+")").attr("class","color"+i);
      }
    },
    
    removeActivity: function() {
      if ($("#constellation_map li").length > activityHistory.listcount) {
        for(i=activityHistory.listcount;i<=$("#constellation_map ul li").length;i++) {
          $("#constellation_map li:nth-child("+i+")").remove();
        }
      }
    }
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/screening/)) {
      activityHistory.location = "screening";
      //Get the last five events
    }
    if (window.location.pathname.match(/lobby/)) {
      activityHistory.location = "lobby";
      //Get the last five events
    }
    if (window.location.pathname.match(/film/)) {
      activityHistory.location = "film";
      //Get the last five events
    }
    
    activityHistory.construct();
    //Add Prior Activities
    activityHistory.activityHistory();
    //Wait for New Additions
    activityHistory.poll();
    
    //Remove Users who have dissapeared
    activityHistory.removeActivity();

});
/*
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */

$(window).load(function(){
	 if (!window.console) window.console = {};
   if (!window.console.log) window.console.log = function() {};
 
   $("#timezone-select").click(function(e){
  		if (e != undefined)
  			e.stopPropagation();
			
			$("#timezone-select-popup").fadeIn(100);
			
			modal.modalIn();
			
      $(".modal").click(function(e){
      	$("#timezone-select-popup").fadeOut(100);
	    	modal.modalOut();
	    });
   });
   
   $("#timezone-popup-close").click(function(){
      $("#timezone-select-popup").fadeOut(100);
   });
   
   
   $("#timezone-button").click(function(e) {
     $("#timezone_form").submit();
   });
   
   /*DO THIS AT SOME POINT
   $.ajax({
		   type: "POST",
		   url: changeTimezoneUrl,
		   data: "tz_option="+tzId
		 });
	*/
});
function newScreening(form,url,callback,type) {
    var vals = form.HostFormToDict();
    if (type == "purchase") {
      $("#host_purchase").fadeOut();
      $("#process").fadeIn();
    }
    $.postHostJSON(
      url, 
      vals,
      callback
    );
}

jQuery.postHostJSON = function(url, args, callback) {
    $.ajax({url: url, 
      data: $.param(args), 
      dataType: "text", 
      type: "POST",
      success: function(response) {
    	 if (callback) callback(response);
      }, 
      error: function(response) {
    	 console.log("ERROR:", response)
      }
    });
};

jQuery.fn.HostFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
	json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}

var host_screening = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  hostSubmitted: false,
  inviteSubmitted: false,
  purchaseSubmitted: false,
  totalPrice: 0,
  currentPrice: 0,
  priceDiscount: .10,
  price_threshold: .5,
  gbip: false,
  
  init: function() {
    
    host_screening.totalPrice = parseFloat($("#host_ticket_price").val());
    $("#host_invite_count").val( 0 );
    
    if ($("#host-import-email").length > 0) {
      $("#host-import-email").watermark("EMAIL");
      $("#host-import-password").watermark("PASSWORD");
      $("#host-import-password").attr("maxlength",20);
    }
    
    $("#host-ticket_discount").html(host_screening.priceDiscount);
    
    if ($("#gbip").html() == 1) {
      host_screening.gbip = true;
    }
    
    //Detail Steps
  	if ($("#host_screening_button").length > 0) {
  	  //console.log("Host Popup Select Enabled");
      $("#host_screening_button").click(function(e){
        e.preventDefault();
  	    if ($("#host_details").length == 0) {
          $("#login_destination").val('/film/'+$("#film").html()+'/host_screening?currentDate='+$("#current_date").html());
          login.showpopup();
          return;
        } else {
          //console.log("Host Popup Selected");
          host_screening.detail();
        }
      });
    }
    
    $("#host_submit").click(function( e ){
      console.log("Host Step");
      e.preventDefault();
  		if (host_screening.validateDetail()) {
  		  host_screening.hostSubmitted = true;
        newScreening($("#detail-form"),
                    "/host/"+$("#film").html()+"/detail",
                    host_screening.detailResult,
                    "host");
      }
    });
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/host_screening/)) {
      host_screening.detail();
    }
    
    //This is the "PRE PURCHASE" Invite Page
    if (window.location.pathname.match(/\/host_invite/)) {
      if ($("#host_invite").length > 0) {
        //MODIFIED 07/27/2011
        //host_screening.invite();
        host_screening.pay();
      }
    }
    
    //If we're in the purchase step, show the popup
    if (window.location.pathname.match(/\/host_purchase/)) {
      host_screening.pay();
    }
    
    //If we're in the confirm step, show the popup
    if (window.location.pathname.match(/\/host_confirm/)) {
      var obj = window.location.pathname.split("/");
      console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      host_screening.confirm();
    }
    
    //Send Contacts 
    $("#btn-host-invite").click( function(e) {
      e.preventDefault();
      console.log("SENDING");
      $('.nr-invitees').html($('#host-accepted-invites-container li').length);
      host_screening.sendInvites();
  	});
    
    
  },
  
  geoblock : function() {
    $(".gbip-close").click(function(){
      $("#geoblock").fadeOut(400).queue(function() {
        host_screening.detail();
        $("#geoblock").clearQueue();
      });
    });
    host_screening.gbip = false;
    $("#geoblock").fadeIn();
  },
  
  detail : function() {
    
    console.log("Showing Host");
    host_screening.hostSubmitted = false;
    $("#host_date").val('');
    $("#host_time").val('');
    $("#host-accepted-invites-container li").remove();
    
    if ($("#host_details").length == 0) {
      $("#login_destination").val('/film/'+$("#film").html()+'/host_screening?currentDate='+$("#current_date").html());
      login.showpopup();
      return;
    }
    
    if (host_screening.gbip) {
      host_screening.geoblock();
      return;
    }
    
    $("#host_details").fadeIn("slow");
    
    $(".host_type").click(function(){
      $("#hosting_type").val($(this).html());
      $(".host_type").attr('class','btn_medium host_type');
      $(this).attr('class','btn_medium_og host_type');
    });
    
    // Handle label show/hide.
  	$("#fld-greeting").inputlimiter({
                              limit: 150,
                              boxId: 'textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
    
    $("#REF_host_image_original").fancybox({
      'autoScale'		: false,
			'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'width'		: 140,
			'height'		: 120,
      overlayShow: true,
      hideOnContentClick: false,
      'scrolling'		: 'no',
    	'titleShow'		: false,
    	'onComplete': function() {
        // set up the form for ajax submission
        $("#FILE_host_image_original").makeAsyncUploader({
          upload_url: "/services/ImageManager/host/"+$("#host_id").val()+'?constellation_frontend='+$("#session_id").val(),
          flash_url: '/js/swfupload/swfupload.swf',
          button_image_url: '/js/swfupload/blankButton.png',
          debug: false
        });
      },
    	'onClosed' : function() {
  	    var pic = '/uploads/hosts/'+$("input[name=FILE_host_image_original_guid]").val();
        $("#host_image_intro_text").hide();
        if ($("input[name=FILE_host_image_original_guid]").val().length > 0) {
          pic=pic+".jpg";
          $("#host_image_original_preview").attr("src",pic);
          $("#host_image_original_preview_wrapper").show();
        }
    	}
    });
    
    
	  var starttimes = $("#film_start_offset").html().split('|');
    console.log("Start time: " + $("#film_start_offset").html());
    var theMinDate = new Date(starttimes[0], starttimes[1]-1, starttimes[2], starttimes[3], starttimes[4]);
	  
    var endtimes = $("#film_end_offset").html().split('|');
    console.log("End time: " + $("#film_end_offset").html());
    var theMaxDate = new Date(endtimes[0], endtimes[1]-1, endtimes[2], endtimes[3], endtimes[4]);
	  
    $('#host_date').datepicker({
    	numberOfMonths: 2,
    	minDate: theMinDate,
    	maxDate: theMaxDate
    });
    
    //http://docs.jquery.com/UI/Datepicker#option-defaultDate
    $('#host_time').timepicker({
    	minuteGrid: 15,
    	defaultDate: -3,
    	ampm: true
    });
    
    //$('#fld-host-date').datetimepicker();
    
  },
  
  detailResult: function (response) {
    var response = eval("(" + response + ")");
    if ((response.hostResponse.status == "success") && (response.hostResponse.screening != '')) {
      $(".current_host_screening_time").html(response.hostResponse.alt);
      $("#screening").html(response.hostResponse.screening);
      var theurl = $("#host_ticket_price").val();
      var theprice = theurl.replace(".","_");
      $("#paypal-button").attr("href","/services/Paypal/express/host?vars="+response.hostResponse.screening+"-"+$("#film").html()+"-"+theprice);
      $("#host_details").fadeOut("slow");
      //MODIFIED 07/27/2011
      //host_screening.invite();
      host_screening.pay();
    } else {
      error.showError("error",response.hostResponse.message);
      host_screening.hostSubmitted = false;
      host_screening.detail();
    }
  },
  
  validateDetail: function() {
    var err = 0;
    if ($("#host_date").val() == '') { $("#host_date").css('background','#ff6666'); err++; } else { $("#host_date").css('background','#A5D0ED'); }
    if ($("#host_time").val() == '') { $("#host_time").css('background','#ff6666'); err++; } else { $("#host_time").css('background','#A5D0ED'); }
    
    if (err > 0) {
      error.showError("error","You must choose a hosting date and time that is later than the current date and time.");
      host_screening.hostSubmitted = false;
      return false;
    }
    if (host_screening.hostSubmitted) {
      return false;
    }
    
    return true;
  },
  
  
  updatePrice : function ( price ) {
    var tot = new Number(host_screening.totalPrice);
    var thaprice = tot * host_screening.price_threshold;
    if ((price > 0) && (price >= thaprice)) {
      host_screening.currentPrice = price;
      var num = new Number(host_screening.currentPrice);
      $(".host-ticket_price").html(num.toFixed(2));
      $("#host-ticket_price").val(num.toFixed(2));
    }
  },
  
  updateCount : function () {
    //IC are previously sent Invites
    var ic = $("#host_invite_count").val();
    $(".host_number_invites").html(parseInt(ic) + parseInt($('#host_invite_count').val()));
  },
  
  
  invite : function() {
    
    console.log("Showing Host");
    
    host_screening.currentPrice = host_screening.totalPrice;
    host_screening.updatePrice( host_screening.currentPrice );
    $(".host_number_invites").html("0");
    
    console.log("INVITE with"+$("#host-accepted-invites-container li").length);
     
    //if ($("#host-accepted-invites-container li").length > 0) {
    //  host_screening.updatePrice( $("#setup_price").val() );
    //}
     
    $("#host_add_invite").editable(function(value, settings) { 
         host_screening.addContactToList(value);
         return "Click To Add Email";
      }, { 
         cssclass : 'invite_input',
         height: '12',
         width: '150',
         type    : 'textarea',
         submit  : 'add',
         name: 'editablehost'
     });
     
		if ($("#host_invite").length > 0) {
			$("#host_invite").fadeIn("slow");
		}
		else {
			host_screening.pay();
		}
    
    $("#host-go-purchase").click(function( e ) {
      e.preventDefault();
      console.log("Purchasing " + "/film/"+$("#film").html()+"/purchase/"+$("#screening").html());
      if ((host_screening.inviteSubmitted == false) && ($('#host-accepted-invites-container li').length > 0)) {
        error.showError('notice','Your Invites Aren\'t Sent','Please send your pending invites, then purchase your ticket.');
      } else {
        $("#host_invite").fadeOut("slow").queue(function(){
          host_screening.pay();
          $("#host_invite").clearQueue();
        });
      }
    });
    
    
    //view already invited popup
  	$('#view-already-invited').click(function(){
  		movePopup('#already-invited-popup', 100, 85);
  		togglePopup('already-invited');
  	});
  	
    host_screening.getInvites();
    
    $("#step_thanks #btn-review_invites").click( function(e) {
  		togglePopup('review_invitees');
  		movePopup('#review_invitees-popup', 110, 1);
  	});
  	
  	$('#close-preview-invite-popup').click(function(){
  		$('#preview-invite-popup').hide();
  	});
  	
  	$('#preview-invite-popup').click(function(event){
  		event.stopPropagation();
  	});
  		
  	$('#btn-invite-more').click( function() {
  		$('#step_thanks').addClass('step_done').removeClass('step_current');
  		$('#step_thanks').slideUp('slow');
  		$('#step-invite').slideDown('slow');
  	
  	});
  	
  	/* PREVIEW INVITE */
  	$('#host-btn-preview_invite').click(function() {
  		
  		if($('#steps-setup').length !== 0) // we are on setup screening page
  		{
  			uniqueKey = $('input.unique_key').val();
  		}
      
      var args = {"screening":$('#screening').html(),
                  "film":$('#film').html(),
                  "message":$('#host-invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
      
      $.ajax({
  			url: '/services/Invite',
  			data: $.param(args), 
        type: "GET", 
        cache: false, 
        dataType: "html", 
        success: function(html){
  				$('#preview-invite-popup .invite-content').html(html);
  				$('#preview-invite-popup').show();
  			}
  			
  		});
  		
  		return false;
  	});
  	/* END PREVIEW INVITE */
  	
  },
  
  getInvites: function() {
    
  	/* INVITATION STEPS */
  	//Show the appropriate Form for Input
    $('.host_import_click').click(function(e){
    
      $("#host_service_name").html($(this).attr('name'));
      
  		$('#host-import-contacts-container .login').fadeIn("slow");
  		
  		$('#host-import-email').val('');
  		$('#host-import-password').val('');
  		
      $("#host-import-email").watermark("EMAIL");
      $("#host-import-password").watermark("PASSWORD");
  		
  		e.preventDefault();
  		
      $('#host-import-contacts-container').slideDown('fast');
  		$('#host-import-contacts-provider').val( $(this).attr("name") );
  		
  	});
  	
  	$('#host-btn-import').click(function(e){
  	  
      e.preventDefault();
      if (($('#host-import-email').val() == '') || ($('#host-import-password').val() == '') || ($('#host-import-email').val() == 'EMAIL') || ($('#host-import-password').val() == 'PASSWORD')) {
        $('#contacts-error-area').html("Please enter a valid username and password.");
      } else {
  		  host_screening.getContacts(e);
  		}
  		
  		return false;
  	});
  	
  	$("#host-invite-fld-greeting").inputlimiter({
                              limit: 150,
                              boxId: 'host-invite-textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
  	
  },
  
  removeEmail: function (email) {
    console.log("Removing!");
		$('#host-accepted-invites-container li[title="'+email+'"] a.remove-btn').remove();
		screening_room.collectedEmails = jQuery.grep(screening_room.collectedEmails, function(value) {return value != email;});
    $('#host-accepted-invites-container li[title="'+email+'"]').remove();
    
    host_screening.updatePrice( host_screening.currentPrice + host_screening.priceDiscount );
    host_screening.updateCount();
    
  },
  
  getContacts : function(e) {
    e.preventDefault();
    
    itRetrievedContacts = 0;
    $('#host-contacts-loading-area').fadeIn();
    
    var args = {"email":$('#host-import-email').val(),
                "password":$('#host-import-password').val(),
                "provider":$('#host-import-contacts-provider').val(),
                "provider-alt":$('#host-provider-alternate').val()};
    
    $.ajax({
      url: '/services/ContactGrabber', 
      data: $.param(args), 
      type: "GET", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          host_screening.populateContacts( response );
      }, error: function(response) {
          error.showError('notice','communication error','Please try again');  
          $("#host-contacts-loading-area").fadeOut("slow");
      }
    });
   }, 
   
   populateContacts: function ( json ){
    
    if (json.result == 'success') {
      if ($('#step-invite .error-panel').is(':visible')) {
        $('#step-invite .error-panel').animate({
        width: 'toggle'
        }, 500);
      }
      
      $('#host-contacts-error-area').html('');
      $('.holder').remove();
      
      $('#host-import-contacts-container').fadeOut('slow');
      
      switch($("#host_service_name").html()) {
        case "twitter":
          $('#host_tw_session').val(json.session);
          break;
        case "facebook":
          $('#host_fb_session').val(json.session);
          break;
      }
      
      for(email in json.emails){
        host_screening.generateHtmlForContact(json.emails[email]);
      }
      
      $('#host-import-email').val('');
      $('#host-import-password').val('');
      
    } else if(json.result == 'failure') {
    
      $('#host-contacts-error-area').html(json.message);
      
      $('#step-invite .error-panel .errors').html(json.message);
      if (!$('#step-invite .error-panel').is(':visible')) {
        $('#step-invite .error-panel').animate({
        width: 'toggle'
        }, 500);
      }
    }
      
    $('#host-contacts-loading-area').fadeOut();	
    
    return false;
    
  },
  
  generateHtmlForContact : function(email) {
    if ($("#host_service_name").html() == 'facebook') {
      var img = '<img src="'+email.name+'" />';
      var id = email.id;
    } else if ($("#host_service_name").html() == 'twitter') {
      var img = '';
      var id = email.email;
    } else {
      var img = '';
      var id = host_screening.itRetrievedContacts;
    }
    $('#host-fld-invites-container').append('<li class="holder" onclick="host_screening.pushContactToList(\''+email.email+'\',\''+id+'\',\''+$("#host_service_name").html()+'\');" id="host-holder-'+id+'" title="host-holder-email-'+screening_room.itRetrievedContacts+'"><div>' + img + email.email + '</div><a class="add-btn" href="javascript:void(0);">&raquo;</a></li>');
  	host_screening.itRetrievedContacts++;
  },
  
  pushContactToList : function( item,id,type ) {
    if ((type == 'twitter') || (type == 'facebook')) {
      host_screening.addEmailtoList(item,id,type);
    } else {
      host_screening.checkSmtpEmail(item);
    }
  },
  
  addContactToList : function(val) {
  	var trimValue = $.trim(val);
  	host_screening.checkSmtpEmail(trimValue);
  		
  },
  
  checkSmtpEmail: function( anemail ) {
    var args = {"email":anemail};
    
    $.ajax({
      url: '/services/ContactValidator', 
      data: $.param(args), 
      type: "GET", 
      cache: false,
      timeout: 2000,
      dataType: "json", 
      success: function(response) {
        if (response.result = "valid") {
          console.log( anemail +" is valid" );
          host_screening.addEmailtoList(anemail,anemail,'email');
          return true;
        } else {
          console.log( anemail +" is not valid");
          error.showError('error','The email you have entered is invalid', anemail +' is not valid.'); 
          return false;
        }
        
      }, error: function(response) {
        console.log( anemail +" is not valid");
        error.showError('notice','communication error','Please try again'); 
        return false
      }
    });
  },
  
  
  addEmailtoList: function( trimValue,id,type ) {
    if($("#host-accepted-invites-container li[title='"+trimValue+"']").length > 0) {
      return;
    }
    if($.inArray(trimValue, host_screening.collectedEmails) == -1)
  	{
  		len = host_screening.collectedEmails.length;
  		len -= 1;
  		
  		$('#host-accepted-invites-container').append('<li title="'+id+'" type="'+type+'"><div>'+trimValue+'</div><a href="javascript:void(0);" class="remove-btn" onClick="host_screening.removeEmail(\''+id+'\');">x</a></li>');
  		
      host_screening.updatePrice( host_screening.currentPrice - host_screening.priceDiscount );
      host_screening.updateCount();
      
      return false;
    } else {
  		return val;
  	}	
  },
  
  sendInvites: function() {
    
    host_screening.inviteSubmitted = true;
    
    $('#inviting-popup').show();
    
    if ($('#host-accepted-invites-container li').length == 0) {
      $('#inviting-errors').attr("style","color: red").html("There are no people to invite,<br /> please add some email addresses and try again..");
      $('#inviting-popup').delay(2000).fadeOut(400).queue(function() {
        $('#inviting-errors').html('');
        $('#inviting-popup').clearQueue();
      });
      return;
    }
    emails = [];
    fbs = [];
    tws = [];
    j=0;
    $('#host-accepted-invites-container').children("li[type='email']").each(function(idx, elm) {
        emails.push(elm.title);
        j++;
    });
    $('#host-accepted-invites-container').children("li[type='facebook']").each(function(idx, elm) {
        fbs.push(elm.title);
        j++;
    });
     $('#host-accepted-invites-container').children("li[type='twitter']").each(function(idx, elm) {
        tws.push(elm.title);
        j++;
    });
    var args = {"emails":emails,
                "user_type":'host',
                "facebooks":fbs,
                "fb_session":$("#host_fb_session").val(),
                "tweets":tws,
                "tw_session":$("#host_tw_session").val(),
                "screening":$('#screening').html(),
                "message":$('#host-invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          host_screening.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
          error.showError('notice','communication error','Please try again'); 
          $('#inviting-popup').delay(2000).fadeOut(400);
      }
    });
  },
  
  finishInvites: function( response ) {
    if (response.result == "success") {
      var ic = $("#host_invite_count").val();
      $("#host_invite_count").val(parseInt(ic) + parseInt($('#host-accepted-invites-container li').length));
      $('#host-accepted-invites-container li').remove();
      error.showError('notice',response.message); 
      $('#inviting-popup').delay(2000).fadeOut(400);
    } else {
      error.showError('error',response.message); 
      $('#inviting-popup').delay(2000).fadeOut(400);
    }
  },
  
  goPaypal : function() {
    if (($("#screening").html() != "") && ($("#film").html() != "")) {
      var theurl = $("#host_ticket_price").val();
      var theprice = theurl.replace(".","_");
      window.location.href = "/services/Paypal/express/host?vars="+$("#screening").html()+"-"+$("#film").html()+"-"+theprice;
    }
  },
  
  pay : function() {
    
    $("#host_purchase").fadeIn("slow");
    
    $("#host_purchase_submit").click(function(){
      if (host_screening.validatePurchase()) {
        
        host_screening.purchaseSubmitted = true;
        newScreening($("#host_purchase_form"),
                    "/host/"+$("#film").html()+"/purchase/"+$("#screening").html(),
                    host_screening.purchaseResult,
                    "purchase");
        //$("#host_purchase_form").submit();
      }
    });
  	
  },
  
  validatePurchase: function() {
    console.log("Purchase Here");
    var err = 0;
    if ($("#host-fld-cc_first_name").val() == '') { $("#host-fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#host-fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_last_name").val() == '') { $("#host-fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#host-fld-cc_last_name").css('background','#A5D0ED'); }
    if (! host_screening.isValidEmailAddress($("#host-fld-cc_email").val())) { $("#host-fld-cc_email").css('background','#ff6666'); err++; } else { $("#host-fld-cc_email").css('background','#A5D0ED'); }
    if (! host_screening.isValidEmailAddress($("#host-fld-cc_confirm_email").val())) { $("#host-fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#host-fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (host_screening.isValidEmailAddress($("#host-fld-cc_email").val()) && host_screening.isValidEmailAddress($("#host-fld-cc_confirm_email").val())) {
      if ($("#host-fld-cc_email").val() != $("#host-fld-cc_confirm_email").val()) { $("#host-fld-cc_email").css('background','#ff6666'); $("#host-fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#host-fld-cc_email").css('background','#A5D0ED'); $("#host-fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#host-fld-cc_number").val() == '') { $("#host-fld-cc_number").css('background','#ff6666'); err++; } else { $("#host-fld-cc_number").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_security_number").val() == '') { $("#host-fld-cc_security_number").css('background','#ff6666'); err++; } else { $("#host-fld-cc_security_number").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_address1").val() == '') { $("#host-fld-cc_address1").css('background','#ff6666'); err++; } else { $("#host-fld-cc_address1").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_city").val() == '') { $("#host-fld-cc_city").css('background','#ff6666'); err++; } else { $("#host-fld-cc_city").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_zip").val() == '') { $("#host-fld-cc_zip").css('background','#ff6666'); err++; } else { $("#host-fld-cc_zip").css('background','#A5D0ED'); }
    
    if (err > 0) {
			var price = $("#ticket_price").val();
			console.log("price: " + price);
			if(price == 0) {
				error.showError("error","Please complete the required fields.");
			}
			else {
				error.showError("error","Your payment information is invalid.");
			}
		
      host_screening.purchaseSubmitted = false;
      return false;
    }
    if (host_screening.purchaseSubmitted) {
      error.showError("error","You payment is being processed.");
      return false;
    }
    return true;
  },
  
  purchaseResult: function (response) {
    console.log("CONFIRMED!");
    $("#process").fadeOut();
    
    var response = eval("(" + response + ")");
    if (response.hostResponse.status == "success") {
      $("#screening").html(response.hostResponse.screening);
      $("#purchase_result").html(response.hostResponse.result);
      $("#host_invite_count").val( 0 );
      host_screening.confirm();
    } else {
      error.showError("error",response.hostResponse.message);
      host_screening.purchaseSubmitted = false;
      host_screening.pay();
    }
  },
  
  confirm : function() {
    console.log("CONFIRM");
    $("#host_screening_full_link").html('http://'+$("#domain").html()+'/theater/'+$("#screening").html());
    $("#host_screening_link").attr('href','/theater/'+$("#screening").html());
    $('#host_screening_link').click(function(e){
  		e.preventDefault();
      window.location.href = '/theater/'+$("#screening").html();
    });
    host_screening.hostSubmitted = false;
    host_screening.purchaseSubmitted = false;
    $("#host_purchase").fadeOut("slow").queue(function() {
      $("#host_confirm").fadeIn("slow");
      $("#host_purchase").clearQueue();
    });
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  }
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	host_screening.init();
	
});
var reviews = {
  
  currentPosition: 0,
  slideWidth: 650,
  slides: $('.slide'),
  numberOfSlides: 0,
  timeout: 10000,
  
  init: function() {
    
    if ($("#slideshow #slidesContainer .slide").length > 0) {
      reviews.slideWidth = parseInt($("#slideshow #slidesContainer .slide").css("width").replace("px",""));
    }
    
    reviews.numberOfSlides = reviews.slides.length;
    
    if (reviews.numberOfSlides == 0) {
      return;
    }
    
    // Remove scrollbar in JS
    $('#slidesContainer').css('overflow', 'hidden');

    // Wrap all .slides with #slideInner div
    reviews.slides.wrapAll('<div id="slideInner"></div>').css({
      'float' : 'left',
      'width' : reviews.slideWidth
    });
  
    // Set #slideInner width equal to total width of all slides
    $('#slideInner').css('width', reviews.slideWidth * reviews.numberOfSlides);
  
    // Hide left arrow control on first load
    window.setTimeout(reviews.moveSlideRight, reviews.timeout);
  },

  // Create event listeners for .controls clicks
  moveSlideRight: function() {
    // Determine new position
    //console.log("Current position: " + reviews.currentPosition);
    //console.log("Number of slides: " + reviews.numberOfSlides);
    reviews.currentPosition = (reviews.currentPosition == (reviews.numberOfSlides-1)) ? 0 : reviews.currentPosition+1;
    //console.log("New position: " + reviews.currentPosition);
  
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : reviews.slideWidth*(-reviews.currentPosition)
    });
    window.setTimeout(reviews.moveSlideRight, reviews.timeout);
  }
}

//http://sixrevisions.com/tutorials/javascript_tutorial/create-a-slick-and-accessible-slideshow-using-jquery/
$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  reviews.init();

});
jQuery.fn.FeedbackFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
	json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}


// JavaScript Document
var feedback = {
  
  init: function() {
    //$.cookie("csfeedback",1);
    //console.log("Feedback Cookie Is " + $.cookie("csfeedback"));
    /*if ($.cookie("csfeedback") == undefined) {
      var randomnumber=Math.floor(Math.random()*11);
      var randomnumber=Math.floor(Math.random()*11);
      $.cookie("csfeedback",randomnumber);
    } else 
    */
    if ($.cookie("csfeedback") == undefined) {
      //Alert with the "Do You Want to Take A Survey"
      //If "Yes", set Cookie to 100
      //IF "No", set Cookie to -1
      $.cookie("csfeedback",-1, { path: "/", expires: 365 });
      feedback.surveyAsk();
    }
    /*
     else if ($.cookie("csfeedback") == 100) {
      //Add the "Survey" Tab
      feedback.getFeedback();
    }
    */
  },
  
  surveyAsk: function() {
    var message = 'Can you help us make Constellation.tv better by answering a few brief questions?<br /><br /><a class="btn_med" href="javascript:void(0)" onclick="feedback.addTab()" style="padding-top: 15px !important; margin-right: 15px;">Yes, I\'d love to!</a><a class="btn_med" href="javascript:void(0)" onclick="feedback.dropTab()" style="padding-top: 15px !important;">No Thanks!</a>';
    error.showError( "error", "We'd love some feedback!", message, 0);
  },
  
  addTab: function( e ) {
    //console.log("Tab Will Show Up Next Time");
    //$.cookie("csfeedback",100);
    $.unblockUI(); 
    //feedback.showTab();
    feedback.getFeedback();
  },
  
  dropTab: function( e ) {
    //console.log("Tab Will NOT Show Up Next Time");
    $.cookie("csfeedback",-1, { path: "/",  expires: 365 } );
    $.unblockUI(); 
  },
  
  showTab: function() {
    //console.log("Showin' the tab...");
    var node = '<a style="background-color:#222" class="fdbk_tab_left" id="fdbk_tab" href="javascript: void(0)">FEEDBACK</a>';
    $("body").append(node);
    $("#fdbk_tab").click(function(e){
      feedback.getFeedback();
    });
  },
  
  getFeedback: function() {
    $("#feedback_popup").fadeIn();
    $("#feedback").click(function(e){
      var vals = $("#feedback_form").FeedbackFormToDict();
      $.ajax({url: "/services/UserFeedback", 
        data: $.param(vals), 
        dataType: "text", 
        type: "POST",
        success: function(response) {
      	 feedback.giveThanks();
        }, 
        error: function(response) {
      	 console.log("ERROR:", response)
        }
      });
    });
  },
  
  giveThanks: function() {
    $.cookie("csfeedback",-1, { path: "/", expires: 365});
    var theml = '<div class="clearfix description">Thank you!</div>';
    $("#feedbody").html(theml);
    $("#feedback_popup").delay(3000).fadeOut();
  }
  
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    feedback.showTab();
    feedback.init();
    
});
