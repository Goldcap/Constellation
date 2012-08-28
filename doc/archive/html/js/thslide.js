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
