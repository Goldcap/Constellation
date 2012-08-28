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
/*!
 * jCarousel - Riding carousels with jQuery
 *   http://sorgalla.com/jcarousel/
 *
 * Copyright (c) 2006 Jan Sorgalla (http://sorgalla.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Built on top of the jQuery library
 *   http://jquery.com
 *
 * Inspired by the "Carousel Component" by Bill Scott
 *   http://billwscott.com/carousel/
 */

(function(g){var q={vertical:!1,rtl:!1,start:1,offset:1,size:null,scroll:3,visible:null,animation:"normal",easing:"swing",auto:0,wrap:null,initCallback:null,setupCallback:null,reloadCallback:null,itemLoadCallback:null,itemFirstInCallback:null,itemFirstOutCallback:null,itemLastInCallback:null,itemLastOutCallback:null,itemVisibleInCallback:null,itemVisibleOutCallback:null,animationStepCallback:null,buttonNextHTML:"<div></div>",buttonPrevHTML:"<div></div>",buttonNextEvent:"click",buttonPrevEvent:"click", buttonNextCallback:null,buttonPrevCallback:null,itemFallbackDimension:null},m=!1;g(window).bind("load.jcarousel",function(){m=!0});g.jcarousel=function(a,c){this.options=g.extend({},q,c||{});this.autoStopped=this.locked=!1;this.buttonPrevState=this.buttonNextState=this.buttonPrev=this.buttonNext=this.list=this.clip=this.container=null;if(!c||c.rtl===void 0)this.options.rtl=(g(a).attr("dir")||g("html").attr("dir")||"").toLowerCase()=="rtl";this.wh=!this.options.vertical?"width":"height";this.lt=!this.options.vertical? this.options.rtl?"right":"left":"top";for(var b="",d=a.className.split(" "),f=0;f<d.length;f++)if(d[f].indexOf("jcarousel-skin")!=-1){g(a).removeClass(d[f]);b=d[f];break}a.nodeName.toUpperCase()=="UL"||a.nodeName.toUpperCase()=="OL"?(this.list=g(a),this.clip=this.list.parents(".jcarousel-clip"),this.container=this.list.parents(".jcarousel-container")):(this.container=g(a),this.list=this.container.find("ul,ol").eq(0),this.clip=this.container.find(".jcarousel-clip"));if(this.clip.size()===0)this.clip= this.list.wrap("<div></div>").parent();if(this.container.size()===0)this.container=this.clip.wrap("<div></div>").parent();b!==""&&this.container.parent()[0].className.indexOf("jcarousel-skin")==-1&&this.container.wrap('<div class=" '+b+'"></div>');this.buttonPrev=g(".jcarousel-prev",this.container);if(this.buttonPrev.size()===0&&this.options.buttonPrevHTML!==null)this.buttonPrev=g(this.options.buttonPrevHTML).appendTo(this.container);this.buttonPrev.addClass(this.className("jcarousel-prev"));this.buttonNext= g(".jcarousel-next",this.container);if(this.buttonNext.size()===0&&this.options.buttonNextHTML!==null)this.buttonNext=g(this.options.buttonNextHTML).appendTo(this.container);this.buttonNext.addClass(this.className("jcarousel-next"));this.clip.addClass(this.className("jcarousel-clip")).css({position:"relative"});this.list.addClass(this.className("jcarousel-list")).css({overflow:"hidden",position:"relative",top:0,margin:0,padding:0}).css(this.options.rtl?"right":"left",0);this.container.addClass(this.className("jcarousel-container")).css({position:"relative"}); !this.options.vertical&&this.options.rtl&&this.container.addClass("jcarousel-direction-rtl").attr("dir","rtl");var j=this.options.visible!==null?Math.ceil(this.clipping()/this.options.visible):null,b=this.list.children("li"),e=this;if(b.size()>0){var h=0,i=this.options.offset;b.each(function(){e.format(this,i++);h+=e.dimension(this,j)});this.list.css(this.wh,h+100+"px");if(!c||c.size===void 0)this.options.size=b.size()}this.container.css("display","block");this.buttonNext.css("display","block");this.buttonPrev.css("display", "block");this.funcNext=function(){e.next()};this.funcPrev=function(){e.prev()};this.funcResize=function(){e.resizeTimer&&clearTimeout(e.resizeTimer);e.resizeTimer=setTimeout(function(){e.reload()},100)};this.options.initCallback!==null&&this.options.initCallback(this,"init");!m&&g.browser.safari?(this.buttons(!1,!1),g(window).bind("load.jcarousel",function(){e.setup()})):this.setup()};var f=g.jcarousel;f.fn=f.prototype={jcarousel:"0.2.8"};f.fn.extend=f.extend=g.extend;f.fn.extend({setup:function(){this.prevLast= this.prevFirst=this.last=this.first=null;this.animating=!1;this.tail=this.resizeTimer=this.timer=null;this.inTail=!1;if(!this.locked){this.list.css(this.lt,this.pos(this.options.offset)+"px");var a=this.pos(this.options.start,!0);this.prevFirst=this.prevLast=null;this.animate(a,!1);g(window).unbind("resize.jcarousel",this.funcResize).bind("resize.jcarousel",this.funcResize);this.options.setupCallback!==null&&this.options.setupCallback(this)}},reset:function(){this.list.empty();this.list.css(this.lt, "0px");this.list.css(this.wh,"10px");this.options.initCallback!==null&&this.options.initCallback(this,"reset");this.setup()},reload:function(){this.tail!==null&&this.inTail&&this.list.css(this.lt,f.intval(this.list.css(this.lt))+this.tail);this.tail=null;this.inTail=!1;this.options.reloadCallback!==null&&this.options.reloadCallback(this);if(this.options.visible!==null){var a=this,c=Math.ceil(this.clipping()/this.options.visible),b=0,d=0;this.list.children("li").each(function(f){b+=a.dimension(this, c);f+1<a.first&&(d=b)});this.list.css(this.wh,b+"px");this.list.css(this.lt,-d+"px")}this.scroll(this.first,!1)},lock:function(){this.locked=!0;this.buttons()},unlock:function(){this.locked=!1;this.buttons()},size:function(a){if(a!==void 0)this.options.size=a,this.locked||this.buttons();return this.options.size},has:function(a,c){if(c===void 0||!c)c=a;if(this.options.size!==null&&c>this.options.size)c=this.options.size;for(var b=a;b<=c;b++){var d=this.get(b);if(!d.length||d.hasClass("jcarousel-item-placeholder"))return!1}return!0}, get:function(a){return g(">.jcarousel-item-"+a,this.list)},add:function(a,c){var b=this.get(a),d=0,p=g(c);if(b.length===0)for(var j,e=f.intval(a),b=this.create(a);;){if(j=this.get(--e),e<=0||j.length){e<=0?this.list.prepend(b):j.after(b);break}}else d=this.dimension(b);p.get(0).nodeName.toUpperCase()=="LI"?(b.replaceWith(p),b=p):b.empty().append(c);this.format(b.removeClass(this.className("jcarousel-item-placeholder")),a);p=this.options.visible!==null?Math.ceil(this.clipping()/this.options.visible): null;d=this.dimension(b,p)-d;a>0&&a<this.first&&this.list.css(this.lt,f.intval(this.list.css(this.lt))-d+"px");this.list.css(this.wh,f.intval(this.list.css(this.wh))+d+"px");return b},remove:function(a){var c=this.get(a);if(c.length&&!(a>=this.first&&a<=this.last)){var b=this.dimension(c);a<this.first&&this.list.css(this.lt,f.intval(this.list.css(this.lt))+b+"px");c.remove();this.list.css(this.wh,f.intval(this.list.css(this.wh))-b+"px")}},next:function(){this.tail!==null&&!this.inTail?this.scrollTail(!1): this.scroll((this.options.wrap=="both"||this.options.wrap=="last")&&this.options.size!==null&&this.last==this.options.size?1:this.first+this.options.scroll)},prev:function(){this.tail!==null&&this.inTail?this.scrollTail(!0):this.scroll((this.options.wrap=="both"||this.options.wrap=="first")&&this.options.size!==null&&this.first==1?this.options.size:this.first-this.options.scroll)},scrollTail:function(a){if(!this.locked&&!this.animating&&this.tail){this.pauseAuto();var c=f.intval(this.list.css(this.lt)), c=!a?c-this.tail:c+this.tail;this.inTail=!a;this.prevFirst=this.first;this.prevLast=this.last;this.animate(c)}},scroll:function(a,c){!this.locked&&!this.animating&&(this.pauseAuto(),this.animate(this.pos(a),c))},pos:function(a,c){var b=f.intval(this.list.css(this.lt));if(this.locked||this.animating)return b;this.options.wrap!="circular"&&(a=a<1?1:this.options.size&&a>this.options.size?this.options.size:a);for(var d=this.first>a,g=this.options.wrap!="circular"&&this.first<=1?1:this.first,j=d?this.get(g): this.get(this.last),e=d?g:g-1,h=null,i=0,k=!1,l=0;d?--e>=a:++e<a;){h=this.get(e);k=!h.length;if(h.length===0&&(h=this.create(e).addClass(this.className("jcarousel-item-placeholder")),j[d?"before":"after"](h),this.first!==null&&this.options.wrap=="circular"&&this.options.size!==null&&(e<=0||e>this.options.size)))j=this.get(this.index(e)),j.length&&(h=this.add(e,j.clone(!0)));j=h;l=this.dimension(h);k&&(i+=l);if(this.first!==null&&(this.options.wrap=="circular"||e>=1&&(this.options.size===null||e<= this.options.size)))b=d?b+l:b-l}for(var g=this.clipping(),m=[],o=0,n=0,j=this.get(a-1),e=a;++o;){h=this.get(e);k=!h.length;if(h.length===0){h=this.create(e).addClass(this.className("jcarousel-item-placeholder"));if(j.length===0)this.list.prepend(h);else j[d?"before":"after"](h);if(this.first!==null&&this.options.wrap=="circular"&&this.options.size!==null&&(e<=0||e>this.options.size))j=this.get(this.index(e)),j.length&&(h=this.add(e,j.clone(!0)))}j=h;l=this.dimension(h);if(l===0)throw Error("jCarousel: No width/height set for items. This will cause an infinite loop. Aborting..."); this.options.wrap!="circular"&&this.options.size!==null&&e>this.options.size?m.push(h):k&&(i+=l);n+=l;if(n>=g)break;e++}for(h=0;h<m.length;h++)m[h].remove();i>0&&(this.list.css(this.wh,this.dimension(this.list)+i+"px"),d&&(b-=i,this.list.css(this.lt,f.intval(this.list.css(this.lt))-i+"px")));i=a+o-1;if(this.options.wrap!="circular"&&this.options.size&&i>this.options.size)i=this.options.size;if(e>i){o=0;e=i;for(n=0;++o;){h=this.get(e--);if(!h.length)break;n+=this.dimension(h);if(n>=g)break}}e=i-o+ 1;this.options.wrap!="circular"&&e<1&&(e=1);if(this.inTail&&d)b+=this.tail,this.inTail=!1;this.tail=null;if(this.options.wrap!="circular"&&i==this.options.size&&i-o+1>=1&&(d=f.intval(this.get(i).css(!this.options.vertical?"marginRight":"marginBottom")),n-d>g))this.tail=n-g-d;if(c&&a===this.options.size&&this.tail)b-=this.tail,this.inTail=!0;for(;a-- >e;)b+=this.dimension(this.get(a));this.prevFirst=this.first;this.prevLast=this.last;this.first=e;this.last=i;return b},animate:function(a,c){if(!this.locked&& !this.animating){this.animating=!0;var b=this,d=function(){b.animating=!1;a===0&&b.list.css(b.lt,0);!b.autoStopped&&(b.options.wrap=="circular"||b.options.wrap=="both"||b.options.wrap=="last"||b.options.size===null||b.last<b.options.size||b.last==b.options.size&&b.tail!==null&&!b.inTail)&&b.startAuto();b.buttons();b.notify("onAfterAnimation");if(b.options.wrap=="circular"&&b.options.size!==null)for(var c=b.prevFirst;c<=b.prevLast;c++)c!==null&&!(c>=b.first&&c<=b.last)&&(c<1||c>b.options.size)&&b.remove(c)}; this.notify("onBeforeAnimation");if(!this.options.animation||c===!1)this.list.css(this.lt,a+"px"),d();else{var f=!this.options.vertical?this.options.rtl?{right:a}:{left:a}:{top:a},d={duration:this.options.animation,easing:this.options.easing,complete:d};if(g.isFunction(this.options.animationStepCallback))d.step=this.options.animationStepCallback;this.list.animate(f,d)}}},startAuto:function(a){if(a!==void 0)this.options.auto=a;if(this.options.auto===0)return this.stopAuto();if(this.timer===null){this.autoStopped= !1;var c=this;this.timer=window.setTimeout(function(){c.next()},this.options.auto*1E3)}},stopAuto:function(){this.pauseAuto();this.autoStopped=!0},pauseAuto:function(){if(this.timer!==null)window.clearTimeout(this.timer),this.timer=null},buttons:function(a,c){if(a==null&&(a=!this.locked&&this.options.size!==0&&(this.options.wrap&&this.options.wrap!="first"||this.options.size===null||this.last<this.options.size),!this.locked&&(!this.options.wrap||this.options.wrap=="first")&&this.options.size!==null&& this.last>=this.options.size))a=this.tail!==null&&!this.inTail;if(c==null&&(c=!this.locked&&this.options.size!==0&&(this.options.wrap&&this.options.wrap!="last"||this.first>1),!this.locked&&(!this.options.wrap||this.options.wrap=="last")&&this.options.size!==null&&this.first==1))c=this.tail!==null&&this.inTail;var b=this;this.buttonNext.size()>0?(this.buttonNext.unbind(this.options.buttonNextEvent+".jcarousel",this.funcNext),a&&this.buttonNext.bind(this.options.buttonNextEvent+".jcarousel",this.funcNext), this.buttonNext[a?"removeClass":"addClass"](this.className("jcarousel-next-disabled")).attr("disabled",a?!1:!0),this.options.buttonNextCallback!==null&&this.buttonNext.data("jcarouselstate")!=a&&this.buttonNext.each(function(){b.options.buttonNextCallback(b,this,a)}).data("jcarouselstate",a)):this.options.buttonNextCallback!==null&&this.buttonNextState!=a&&this.options.buttonNextCallback(b,null,a);this.buttonPrev.size()>0?(this.buttonPrev.unbind(this.options.buttonPrevEvent+".jcarousel",this.funcPrev), c&&this.buttonPrev.bind(this.options.buttonPrevEvent+".jcarousel",this.funcPrev),this.buttonPrev[c?"removeClass":"addClass"](this.className("jcarousel-prev-disabled")).attr("disabled",c?!1:!0),this.options.buttonPrevCallback!==null&&this.buttonPrev.data("jcarouselstate")!=c&&this.buttonPrev.each(function(){b.options.buttonPrevCallback(b,this,c)}).data("jcarouselstate",c)):this.options.buttonPrevCallback!==null&&this.buttonPrevState!=c&&this.options.buttonPrevCallback(b,null,c);this.buttonNextState= a;this.buttonPrevState=c},notify:function(a){var c=this.prevFirst===null?"init":this.prevFirst<this.first?"next":"prev";this.callback("itemLoadCallback",a,c);this.prevFirst!==this.first&&(this.callback("itemFirstInCallback",a,c,this.first),this.callback("itemFirstOutCallback",a,c,this.prevFirst));this.prevLast!==this.last&&(this.callback("itemLastInCallback",a,c,this.last),this.callback("itemLastOutCallback",a,c,this.prevLast));this.callback("itemVisibleInCallback",a,c,this.first,this.last,this.prevFirst, this.prevLast);this.callback("itemVisibleOutCallback",a,c,this.prevFirst,this.prevLast,this.first,this.last)},callback:function(a,c,b,d,f,j,e){if(!(this.options[a]==null||typeof this.options[a]!="object"&&c!="onAfterAnimation")){var h=typeof this.options[a]=="object"?this.options[a][c]:this.options[a];if(g.isFunction(h)){var i=this;if(d===void 0)h(i,b,c);else if(f===void 0)this.get(d).each(function(){h(i,this,d,b,c)});else for(var a=function(a){i.get(a).each(function(){h(i,this,a,b,c)})},k=d;k<=f;k++)k!== null&&!(k>=j&&k<=e)&&a(k)}}},create:function(a){return this.format("<li></li>",a)},format:function(a,c){for(var a=g(a),b=a.get(0).className.split(" "),d=0;d<b.length;d++)b[d].indexOf("jcarousel-")!=-1&&a.removeClass(b[d]);a.addClass(this.className("jcarousel-item")).addClass(this.className("jcarousel-item-"+c)).css({"float":this.options.rtl?"right":"left","list-style":"none"}).attr("jcarouselindex",c);return a},className:function(a){return a+" "+a+(!this.options.vertical?"-horizontal":"-vertical")}, dimension:function(a,c){var b=g(a);if(c==null)return!this.options.vertical?b.outerWidth(!0)||f.intval(this.options.itemFallbackDimension):b.outerHeight(!0)||f.intval(this.options.itemFallbackDimension);else{var d=!this.options.vertical?c-f.intval(b.css("marginLeft"))-f.intval(b.css("marginRight")):c-f.intval(b.css("marginTop"))-f.intval(b.css("marginBottom"));g(b).css(this.wh,d+"px");return this.dimension(b)}},clipping:function(){return!this.options.vertical?this.clip[0].offsetWidth-f.intval(this.clip.css("borderLeftWidth"))- f.intval(this.clip.css("borderRightWidth")):this.clip[0].offsetHeight-f.intval(this.clip.css("borderTopWidth"))-f.intval(this.clip.css("borderBottomWidth"))},index:function(a,c){if(c==null)c=this.options.size;return Math.round(((a-1)/c-Math.floor((a-1)/c))*c)+1}});f.extend({defaults:function(a){return g.extend(q,a||{})},intval:function(a){a=parseInt(a,10);return isNaN(a)?0:a},windowLoaded:function(){m=!0}});g.fn.jcarousel=function(a){if(typeof a=="string"){var c=g(this).data("jcarousel"),b=Array.prototype.slice.call(arguments, 1);return c[a].apply(c,b)}else return this.each(function(){var b=g(this).data("jcarousel");b?(a&&g.extend(b.options,a),b.reload()):g(this).data("jcarousel",new f(this,a))})}})(jQuery);
/*
	reflection.js for jQuery v1.03
	(c) 2006-2009 Christophe Beyls <http://www.digitalia.be>
	MIT-style license.
*/
(function(a){a.fn.extend({reflect:function(b){b=a.extend({height:1/3,opacity:0.5},b);return this.unreflect().each(function(){var c=this;if(/^img$/i.test(c.tagName)){function d(){var g=c.width,f=c.height,l,i,m,h,k;i=Math.floor((b.height>1)?Math.min(f,b.height):f*b.height);if(a.browser.msie){l=a("<img class='canvas' />").attr("src",c.src).css({width:g,height:f,marginBottom:i-f,filter:"flipv progid:DXImageTransform.Microsoft.Alpha(opacity="+(b.opacity*100)+", style=1, finishOpacity=0, startx=0, starty=0, finishx=0, finishy="+(i/f*100)+")"})[0]}else{l=a("<canvas class='canvas'/>")[0];if(!l.getContext){return}h=l.getContext("2d");try{a(l).attr({width:g,height:i});h.save();h.translate(0,f-1);h.scale(1,-1);h.drawImage(c,0,0,g,f);h.restore();h.globalCompositeOperation="destination-out";k=h.createLinearGradient(0,0,0,i);k.addColorStop(0,"rgba(255, 255, 255, "+(1-b.opacity)+")");k.addColorStop(1,"rgba(255, 255, 255, 1.0)");h.fillStyle=k;h.rect(0,0,g,i);h.fill()}catch(j){return}}a(l).css({display:"block",border:0});m=a(/^a$/i.test(c.parentNode.tagName)?"<span />":"<div />").insertAfter(c).append([c,l])[0];m.className=c.className;a.data(c,"reflected",m.style.cssText=c.style.cssText);a(m).css({width:g,height:f+i,overflow:"hidden"});c.style.cssText="display: block; border: 0px";c.className="reflected"}if(c.complete){d()}else{a(c).load(d)}}})},unreflect:function(){return this.unbind("load").each(function(){var c=this,b=a.data(this,"reflected"),d;if(b!==undefined){d=c.parentNode;c.className=d.className;c.style.cssText=b;a.removeData(c,"reflected");d.parentNode.replaceChild(c,d)}})}})})(jQuery);

// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
jQuery(function($) {
	$("img.reflect").reflect({/* Put custom options here */});
});carousel_auto_increment = 6;

var carousel = {
  
  home_carousel : null,
  films: null,
  auto_increment: 6,
  
  currentFilm: null,
  
  //Setup Our Initial Carousel Object
  setOpts: function() {
    
    carousel.overlay = $('#carousel ul li .overlay');
    carousel.carouselPopup = $('#carousel-popup');
    carousel.trailerPopup = $('#trailer-popup');
    carousel.carouselOverlay = $('.jcarousel-container #carousel-overlay');
    
    carousel.firstVisibleId = '';
    carousel.lastVisibleId = '';
    
    carousel.zoomOutProgress = false;
    carousel.reflectOptions = {height:'55',opacity:0.4};
  
    carousel.zoomInDefaultOptions = {width: '183px', height: '267px', left: '-7px', top: '-21px'};
    carousel.zoomInFirstOptions = {width: '183px', height: '267px', left: '6px', top: '-21px'};
    carousel.zoomInLastOptions = {width: '183px', height: '267px', left: '-20px', top: '-21px'};
    carousel.zoomOutOptions = {width: '163px', height: '235px', left: '0px', top: '0px'};
    
    // Reflection zoom Options
    carousel.rZoomDefaultOptions = {width: '183px', left: '-7px'};
    carousel.rZoomFirstOptions = {width: '183px', left: '6px'};
    carousel.rZoomLastOptions = {width: '183px', left: '-20px'};
    carousel.rZoomOutOptions = {width: '163px', left: '0px'};
  },
	
  //Setup Our Initial Carousel Object
  init: function() {
    
    carousel.films = cfilms;
    
    $('#main_carousel').jcarousel(carousel.jCarouselOptions);
	  if($.browser.msie) {
  		carousel.carouselOverlay.css('z-index', '-1');
  	}
  	$('.jcarousel-container').append('<div id="carousel-overlay" class="overlay"></div>');
	   
	  
  },
  
  reset: function() {
    
    carousel.firstVisibleId = $("#carousel ul li:first").attr("id");
    //console.log("First is " + carousel.firstVisibleId);
  	$('#carousel .jcarousel-container img.reflected').reflect(carousel.reflectOptions);
	  $('#carousel').css('overflow','visible');
	  
    $('#carousel_trailer_link').click(function() {
      carousel.populateVideo();
    });
    
  },
  
  //Bind the Carousel to the Enter/Exit Features
  bind : function() {
    
    $('#carousel img.reflected').mouseenter(function(){
	
      carousel.zoomOutProgress = false;
  		var el = $(this);
  		var parentId = el.parent().parent()[0].id;
  		carousel.carouselOverlay.show();
  		carousel.overlay.show();
  		$('#' + parentId + ' .overlay').hide();
  		
      var elReflection = $('#' + parentId + ' .canvas');
  		var zoomOptions = carousel.getZoomOptions(parentId);
  		
  		var reflectZoomOptions = carousel.getReflectZoomOptions(parentId);
  		$('#' + parentId).css('z-index', '4');
  		
      el.animate(zoomOptions, function(){
  			carousel.toggleImgContainer(parentId, 'in');
  		}).after(function(){
  			elReflection.animate(carousel.reflectZoomOptions);
  			changeScreeningDate($(this).attr('index'));
  		});
  	}).mouseleave(function(){
  		
      carousel.zoomOutProgress = true;
  		var el = $(this);
  		var parentId = el.parent().parent()[0].id;
  		var elReflection = $('#'+parentId+' .canvas');
  		carousel.toggleImgContainer(parentId, 'out');
  		$('#'+parentId).css('z-index', '2');
  		el.animate(carousel.zoomOutOptions, function(){
  			carousel.carouselOverlay.hide();
  		}).after(function(){
  			elReflection.animate(carousel.rZoomOutOptions);
  		});
  	});
  },
  
  jCarouselOptions : {
   scroll: 1,
   auto: carousel_auto_increment,
   wrap: 'last',
   initCallback: initCallback,
	 itemFirstInCallback: {onAfterAnimation: findFirstVisible }, 
	 itemLastInCallback: {onAfterAnimation: findLastVisible }
  },
  
  link: function() {
    
    $('#carousel img.reflected').click(function(){
  		carousel.populatePopup($(this));
  		carousel.carouselPopup.fadeIn();
  	});
  	
  	$('#carousel-popup .pop_mid .carousel-popup-close a').click(function(e){
  		e.preventDefault();
  		carousel.carouselPopup.fadeOut();
  	});
  	
  	$('#trailer-popup .pop_mid .carousel-popup-close a').click(function(e){
  		e.preventDefault();
  		carousel.stopVideo();
  	});
  },
  
  toggleImgContainer : function(parent, effect) {
    
    var image = $('#'+parent+' .img-container');
  	
  	if(effect == 'in') {
  		if (parent == carousel.firstVisibleId) {
  			image.removeClass('img-container-last').addClass('img-container-first');
  		}
  		else if(parent == lastVisibleId) {
  			image.removeClass('img-container-first').addClass('img-container-last');
  		} else {
  			image.removeClass('img-container-first img-container-last');
  		}
  		
  		if (carousel.zoomOutProgress) {
  			image.hide();
  		} else {
  			image.fadeIn('fast');
  		}
  	} else {
  		image.hide();
  	}
  },

  getZoomOptions : function(id) {
  	if(id == carousel.firstVisibleId) {
  		return carousel.zoomInFirstOptions;
  	} else if (id == lastVisibleId) {
  		return carousel.zoomInLastOptions;
  	} else {
  		return carousel.zoomInDefaultOptions;
  	}
  },
  
  getReflectZoomOptions : function(id) {
  	if(id == carousel.firstVisibleId) {
  		return carousel.rZoomFirstOptions;
  	} else if (id == carousel.lastVisibleId) {
  		return carousel.rZoomLastOptions;
  	} else {
  		return carousel.rZoomDefaultOptions;
  	}
  },
  
  populatePopup : function( elem ) {
    	
    	filmId = elem.attr('index');
    	filmType = elem.attr('type');
    	filmShort = elem.attr('name');
    	
      console.log("carousel populate");
    
    	carousel.currentFilm = filmId;
    	
      var photoContainer = $('#carousel_photo_container');
    	var movieTitle = $('#carousel_film_title');
    	var movieDesc = $('#carousel_film_description');
    	var movieInfo = $('#carousel_film_info');
    	var movieScreenings = $('#carousel_movie_link');
    	//var allShows = $('#carousel_film_link');
    	var btnPlayTrailer = $('#carousel_film_link');
    	var btnHostYourOwn = $('#carousel_host_link');
    	var allowHostYourOwn = carousel.films[filmId]['allow_hosting'];
    	var showtimesHtml = '';
    	var showtimesHoursHtml = '';
    	var showtimesTemplate = '<div class="date tzDate" name="{$insert_date_edt_timestamp}" lang="mmm dd, yyyy">{$insert_date_here}</div><div class="hours">{$insert_hours_here}</div>';
      
      //Reset this link, just in case it was hidden earlier
      $("#carousel_host_link").show();
      
    	photoContainer.attr('src', carousel.films[filmId]['logo_src']);
    	//allShows.attr('href', '/film/'+filmId);
    	if (filmType == 'programResources') {
        btnHostYourOwn.attr('href', '/film/'+filmShort+'/host_screening');//hostYourOwn+filmId);
        btnPlayTrailer.attr('href', '/film/'+filmShort);
    	} else {
        btnHostYourOwn.attr('href', '/film/'+carousel.films[filmId]['id']+'/host_screening');//hostYourOwn+filmId);
        btnPlayTrailer.attr('href', '/film/'+carousel.films[filmId]['id']);
    	}
      movieTitle.html(carousel.films[filmId]['name']);
    	movieDesc.html(carousel.films[filmId]['synopsis']);
    	movieInfo.html(carousel.films[filmId]['info']);
    	
    	// get movie screenings for the current day
    	var showTimes = '';
    	var currentDate = new Date();
    	var year = currentDate.getFullYear();
    	var month = currentDate.getMonth() + 1;
    	if(month < 10) // put 0 in front of 1-digit month
    	{
    		month = "0" + month;
    	}
    	var date = currentDate.getDate();
    	if(date < 10) // put 0 in front of 1-digit date
    	{
    		date = "0" + date;
    	}
    	currentDate = year + "-" + month + "-" + date;
    	
    	//If this film isn't "hostable", hide that button
    	if (allowHostYourOwn == "0") {
        $("#carousel_host_link").hide();
      }
    	
    	if ( carousel.films[filmId]['screenings'] != undefined ) {
      	var found = false;
      	for(var i = 0; i < carousel.films[filmId]['screenings'].length; i++) {
      		var show = carousel.films[filmId]['screenings'][i];
      		if(show['date'] === currentDate) {
      			if(!found)
      			{
      				showTimes = "Today's Showtimes: ";
      				found = true;
      				showTimes = showTimes + '<a class="link" href="/screening/' + show['unique_key'] + '">' + show['date_time_with'].substr(-7) + '</a>';
      			} else {
      				showTimes = showTimes + '; <a class="link" href="/screening/' + show['unique_key'] + '">' + show['date_time_with'].substr(-7) + '</a>';
      			}
      		}
      	}
    	
      	if(found) {
      		// get the timezone
      		showTimes = showTimes + carousel.films[filmId]['screenings'][0]['timezone'];
      	}
      	movieScreenings.html(showTimes);
    	}
    	
    	if ( carousel.films[filmId]['screenings_per_day'] != undefined ) {
      	var rowIt = 0;
      	for(screeningDate in carousel.films[filmId]['screenings_per_day']) {
      		if(rowIt < 3)
      		{
      			var colIt = 0;
      			if(colIt < 6)
      			{
      				showtimesHtml = showtimesHtml + showtimesTemplate.replace('{$insert_date_here}', screeningDate);
      				showtimesHtml = showtimesHtml.replace('{$insert_date_edt_timestamp}', carousel.films[filmId]['screenings_per_day'][screeningDate][0]['date_edt_timestamp']);
      				showtimesHoursHtml = '';
      				for(it in carousel.films[filmId]['screenings_per_day'][screeningDate])
      				{
      					for(screeningHour in carousel.films[filmId]['screenings_per_day'][screeningDate][it])
      					{
      						if(screeningHour == 'time') {
      							var screeningObj = carousel.films[filmId]['screenings_per_day'][screeningDate][it];
      							if(screeningObj.type == 0) {
      								showtimesHoursHtml = showtimesHoursHtml + '<a href="'+screeningObj.url+'" class="tzDate" name="'+screeningObj.edt_timestamp+'" lang="h:MMTT">'+screeningObj.time + '</a>, ';
      							} else {
      								showtimesHoursHtml = showtimesHoursHtml + '<span class="tzDate" name="'+screeningObj.edt_timestamp+'" lang="h:MMTT">' + screeningObj.time + '</span>, ';
      							}
      						}
      					}
      				}
      				colIt = colIt + 1;
      			}
      			rowIt = rowIt + 1;
      			showtimesHoursHtml = showtimesHoursHtml.substring(0, showtimesHoursHtml.length - 2);
      			showtimesHtml = showtimesHtml.replace('{$insert_hours_here}', showtimesHoursHtml);
      		}
      	}
      	
      	if(rowIt != 0) {
      		$('#showtimes-holder').html(showtimesHtml);
      	} else {
      		$('#showtimes-holder').html('No upcoming screenings');
      	}
      }
    },
    
    populateVideo : function() {
      carousel.carouselPopup.fadeOut();
      carousel.trailerPopup.fadeIn();
      trailer.populateVideo(carousel.currentFilm,carousel.films[carousel.currentFilm]['stream_url'],carousel.films[carousel.currentFilm]['logo_src']);
    },
    
    stopVideo : function() {
      trailer.stopVideo();
  		carousel.trailerPopup.fadeOut();
  		carousel.carouselPopup.fadeIn();
    },
    
    initAuto: function() {
      //console.log("starting auto with " + carousel_auto_increment);
      carousel.home_carousel.startAuto(carousel_auto_increment);
    }
}

var changeScreeningDate = function(filmId) {
	$('#screening_date').html('<span>'+films[filmId]["name"]+':</span> '+(typeof films[filmId]["screening_date_string"] != "undefined" ? films[filmId]["screening_date_string"] : ''));
};

function findFirstVisible(carousel, liObject, index, action) {
 firstVisibleId = liObject.id;
};

function findLastVisible (carousel, liObject, index, action) {
 lastVisibleId = liObject.id;
};


function initCallback (acarousel){

  carousel.home_carousel = acarousel;
  
  // Disable autoscrolling if the user clicks the prev or next button.
  acarousel.buttonNext.bind('click', function() {
      acarousel.startAuto(0);
      window.setTimeout(function(){acarousel.startAuto(6)}, 6000);
  });

  acarousel.buttonPrev.bind('click', function() {
      acarousel.startAuto(0);
      window.setTimeout(function(){acarousel.startAuto(6)}, 6000);
  });

  // Pause autoscrolling if the user moves with the cursor over the clip.
  acarousel.clip.hover(function() {
      acarousel.stopAuto();
  }, function() {
      acarousel.startAuto();
  });
}
  
  
$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  carousel.setOpts();
  carousel.init();
  carousel.reset();
	carousel.bind();
	carousel.link();
	
});
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
	
	
// JavaScript Document

$(document).ready(function(){
	
	genre_search.init();
	
});

var genre_search = {
  
  init: function() {
    
    $('#gv').keydown(function( e ) {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 13) {
        $.ajax({url: '/services/CarouselSearch/'+$("#gv").val(), 
          dataType: "text", 
          type: "GET",
          success: function(response) {
           carousel_auto_increment = 0;
        	 genre_search.populateCarousel(response);
          }, 
          error: function(response) {
        	 //console.log("ERROR:", response)
          }
        });
        return false;
      }
      
    });

    $("#gs").click(function() {
      $("#drop_genre").toggle();
    });
    
    $("#gg").click(function() {
      $.ajax({url: '/services/CarouselSearch/'+$("#gv").val(), 
        dataType: "text", 
        type: "GET",
        success: function(response) {
         carousel_auto_increment = 0;
      	 genre_search.populateCarousel(response);
        }, 
        error: function(response) {
      	 //console.log("ERROR:", response)
        }
      });
    });
    
    $(".drop_genre li").click(function(e) {
      //console.log("clicked "+$(this).attr("id"));
      if ($(this).attr("id") == 'all') {
        $.ajax({url: '/services/FeaturedSearch', 
          dataType: "text", 
          type: "GET",
          success: function(response) {
           $("#drop_genre").toggle();
           carousel_auto_increment = 6;
        	 genre_search.populateCarousel(response);
          }, 
          error: function(response) {
        	 error.showError('notice','communication error','Please try again'); 
          }
        });
      } else {
        $.ajax({url: '/services/GenreSearch/'+$(this).attr("id"), 
          dataType: "text", 
          type: "GET",
          success: function(response) {
        	 $("#drop_genre").toggle();
           carousel_auto_increment = 0;
           genre_search.populateCarousel(response);
          }, 
          error: function(response) {
           error.showError('notice','communication error','Please try again'); 
        	 //console.log("ERROR:", response);
          }
        });
      }
    });
  },
  
  populateCarousel: function(response) {
    response = eval("(" + response + ")");
    if (response.result == undefined) {
      //carousel.films = response;
      //console.log("Have "+ $("#main_carousel li").length);
      
      carousel.initAuto();
      $("#main_carousel li").remove();
      
      i = 0;
      for (film in response){
        node = '<li id="film_'+film+'"><div class="img-container"></div><img class="reflected" index="'+film+'" src="'+response[film]["logo_src"]+'" width="163" height="235" alt="" /><div class="overlay"></div></li>';
        carousel.home_carousel.add(i+1,node);
        i++;
      }
      carousel.home_carousel.size(i);
      
      $('#carousel .jcarousel-container img.reflected').reflect(carousel.reflectOptions);
      
      var amt = $('#main_carousel').css("left").replace("px","");
      
      if (parseInt(amt) < 0) {
        $('#main_carousel').css("left",-175);
      }
      carousel.home_carousel.scroll(0);
       
      carousel.bind();
      carousel.reset();
      carousel.link();
      
    } else {
      error.showError('notice','No results.','That search found no films. Please try again'); 
      //console.log("No Results");
    }
    /*
    $("#main_carousel li").each(function(){
      var id = $(this).attr("id").split('_');
      if (response[id[1]] == undefined) {
        $(this).css("display","none");
      } else {
        $(this).css("display","block");
      }
    });
    */
  }
}
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
var upcoming = {
  
  theDays: null,
  specialDays: null,
  thepage: 'date',
  m_names : new Array("Jan", "Feb", "Mar", 
        "Apr", "May", "Jun", "Jul", "Aug", "Sep", 
        "Oct", "Nov", "Dec"),
  
  init: function() {
    if (window.location.pathname.match(/\/film/)) {
      upcoming.thepage = 'film';
      getUrl = '/services/Screenings/upcoming?film='+$("#film").html();
    } else {
      getUrl = '/services/Screenings/upcoming';
    }
    
    
    $.ajax({url: getUrl, 
      type: "GET", 
      cache: true, 
      dataType: "text", 
      success: function(response) {
        upcoming.startup(response);
      }, error: function(response) {
         console.log("ERROR:", response)
      }
    });
    
    if (upcoming.getTheDate() != "") {
      $("#current_date").html(upcoming.getTheDate());
      var args = {"date" : $("#current_date").html(),
                    "film" : $("#film").html() };
      dateSplit = $("#current_date").html().split("/");
      $.ajax({url: '/services/Screenings/'+upcoming.thepage, 
        data: $.param(args), 
        type: "GET", 
        cache: true, 
        dataType: "text", 
        success: function(response) {
           $(".ui-datepicker-trigger").html(upcoming.m_names[parseInt(dateSplit[0]) - 1]+'<br />'+dateSplit[1]);
           $("#today_screenings").html(response);
           
           $('.screening_list').jScrollPane({
              verticalDragMinHeight: 30,
          		verticalDragMaxHeight: 30,
              verticalGutter: 0	
            });
           
           if (upcoming.thepage == 'film') {
             //If we're in the hosting step, show the popup
            $(".screening_link").click( function(e) {
              e.preventDefault();
              if ($('#screening_invite').length > 0) {
                $("#screening").html( $(this).attr("title") );
                if (screening_room != undefined)
                  screening_room.invite();
              } else if ($('#screening_purchase').length > 0) {
                $("#screening").html( $(this).attr("title") );
                if (screening_room != undefined)
                  screening_room.pay();
              } else {
                console.log("On Init");
                $("#login_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
                $("#signup_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
                if (login != undefined)
                  login.showpopup();
              }
            });
           }
        }, error: function(response) {
            console.log("ERROR:", response)
        }
      });
    }
    
  },
  
  startup: function( response ) {
    
    upcoming.theDays = (eval("(" + response + ")"));
    if (upcoming.theDays != null)
      upcoming.specialDays = upcoming.theDays.days;
    
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    
    $( "#featured_datepicker" ).datepicker({
      showOn: "button",
      buttonText: upcoming.m_names[curr_month] + '<br />' + curr_date,
      minDate: 0,
      maxDate: 60,
      beforeShowDay: function(thedate) { 
        var theyear = thedate.getFullYear();
        var themonth = thedate.getMonth() + 1;
        var theday = thedate.getDate();
        if ((upcoming.specialDays != undefined) && (upcoming.specialDays[theyear] != undefined) && (upcoming.specialDays[theyear][themonth] != undefined)) {
          if(upcoming.specialDays[theyear][themonth][theday] == undefined ) return [true,""]; 
          return [true, "specialDate"]; 
        } else {
          return [true,""]; 
        }
      }, 
      onSelect: function(dateText, inst) {
        //console.log("Selected DP");
        $("#current_date").html(dateText);
        var args = {"date" : dateText,
                    "film" : $("#film").html() };
        dateSplit = dateText.split("/");
        $.ajax({url: '/services/Screenings/'+upcoming.thepage, 
          data: $.param(args), 
          type: "GET", 
          cache: true, 
          dataType: "text", 
          success: function(response) {
             $(".ui-datepicker-trigger").html(upcoming.m_names[parseInt(dateSplit[0]) - 1]+'<br />'+dateSplit[1]);
             $("#today_screenings").html(response);
             $('.screening_list').jScrollPane({
              verticalDragMinHeight: 30,
          		verticalDragMaxHeight: 30,
              verticalGutter: 0	
            });
            
             if (upcoming.thepage == 'film') {
               //If we're in the hosting step, show the popup
              $(".screening_link").click( function(e) {
                console.log("Upcoming Screening Click");
                e.preventDefault();
                if ($('#screening_invite').length > 0) {
                  $("#screening").html( $(this).attr("title") );
                  if (screening_room != undefined)
                    screening_room.invite();
                } else if ($('#screening_purchase').length > 0) {
                  $("#screening").html( $(this).attr("title") );
                  console.log("Paying for screening");
                  if (screening_room != undefined)
                    screening_room.pay();
                } else {
                  console.log("On Select");
                  $("#login_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
                  console.log("Set Destination");
                  $("#signup_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
                  console.log("Set Signup");
                  if (login != undefined)
                    login.showpopup();
                }
              });
             }
            
          }, error: function(response) {
              console.log("ERROR:", response)
          }
        });
      }
    });
  },
  
  getTheDate: function() {
    var regexS = "[\\?&]currentDate=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
      return "";
    else
      return results[1];
  }
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	upcoming.init();
	
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
