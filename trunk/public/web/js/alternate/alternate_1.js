var alternate_1 = {
  
  carouselstate: "screening",
  theDays: null,
  specialDays: null,
  thepage: 'date',
  m_names : new Array("Jan", "Feb", "Mar", 
        "Apr", "May", "Jun", "Jul", "Aug", "Sep", 
        "Oct", "Nov", "Dec"),
  
  init: function() {
    
    if (screening_room != undefined) {
      screening_room.linkInvite();
      screening_room.linkPurchase();
    }
    
    if (window.location.pathname.match(/\/film/)) {
      alternate_1.thepage = 'film';
      getUrl = '/services/Screenings/upcoming?film='+$("#film").html();
    } else {
      getUrl = '/services/Screenings/upcoming';
    }
    
    $.ajax({url: getUrl, 
      type: "GET", 
      cache: true, 
      dataType: "text", 
      success: function(response) {
        alternate_1.startup(response);
      }, error: function(response) {
         console.log("ERROR:", response)
      }
    });
    
  },
  
  startup: function( response ) {
    
    $("ui-datepicker-trigger").html("");
    
    alternate_1.theDays = (eval("(" + response + ")"));
    if (alternate_1.theDays != null)
      alternate_1.specialDays = alternate_1.theDays.days;
    
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    
    $( "#featured_datepicker_alternate" ).datepicker({
      showOn: "button",
      buttonText: alternate_1.m_names[curr_month] + '<br />' + curr_date,
      minDate: 0,
      maxDate: 60,
      beforeShowDay: function(thedate) { 
        var theyear = thedate.getFullYear();
        var themonth = thedate.getMonth() + 1;
        var theday = thedate.getDate();
        if ((alternate_1.specialDays != undefined) && (alternate_1.specialDays[theyear] != undefined) && (alternate_1.specialDays[theyear][themonth] != undefined)) {
          if(alternate_1.specialDays[theyear][themonth][theday] == undefined ) return [true,""]; 
          return [true, "specialDate"]; 
        } else {
          return [true,""]; 
        }
      },
      
      onSelect: function(dateText, inst) {
        console.log("Selected DPS");
        $("#current_date").html(dateText);
        alternate_1.getUpcomingFeatures(dateText,inst);
        alternate_1.getUpcomingShows(dateText,inst);
      }
    });
    
    $("#screening").html($("#alt_screening").html());
  },
  
  getTheDate: function() {
    var regexS = "[\\?&]currentDate=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
      return "";
    else
      return results[1];
  },
  
  getUpcomingFeatures: function(dateText,inst) {
    var args = {"date" : dateText,
                "template": $("#alt_template").html(),
                "film" : $("#film").html() };
    dateSplit = dateText.split("/");
    $.ajax({url: '/services/Screenings/featured', 
          data: $.param(args), 
          type: "GET", 
          cache: true, 
          dataType: "text", 
          success: function(response) {
            console.log("Upcoming Feature Success!");
            $("#image_gallery_alternate").html(response);
            screenings_carousel.init();
            
             //If we're in the hosting step, show the popup
            $(".joinlink").click( function(e) {
               e.preventDefault();
               console.log("joinlink");
               alternate_1.getScreeningByVal( $(this).attr("title") );
            });
            
          }, error: function(response) {
            console.log("ERROR:", response)
          }
    });
  },
  
  getUpcomingShows: function(dateText,inst) {
  
    var args = {"date" : dateText,
                "template": $("#alt_template").html(),
                "film" : $("#film").html() };
    dateSplit = dateText.split("/");
    $.ajax({url: '/services/Screenings/'+alternate_1.thepage, 
          data: $.param(args), 
          type: "GET", 
          cache: true, 
          dataType: "text", 
          success: function(response) {
             $(".ui-datepicker-trigger").html(alternate_1.m_names[parseInt(dateSplit[0],10) - 1]+'<br />'+dateSplit[1]);
             
             console.log("Swapping classes");
             if (alternate_1.carouselstate == "screening") {
                $("#last_screening").html($("#today_screenings").html());
             }
             $("#today_screenings").removeClass("alternate").addClass("showtimes");
             
             $("#image_gallery_alternate").fadeIn();
             $(".alternate_container h4:first-child").remove();
             
             $("#today_screenings").html(response);
             $('.screening_list').jScrollPane({
              verticalDragMinHeight: 30,
          		verticalDragMaxHeight: 30,
              verticalGutter: 0	
            });
            
             //If we're in the hosting step, show the popup
            $(".screening_link").click( function(e) {
               e.preventDefault();
               console.log("screening_link");
               $("#screening").html($(this).attr("title"));
               screening_room.invite();
               //alternate_1.getScreeningByVal( $(this).attr("title") );
            });
            
            alternate_1.carouselstate = "upcoming";
            
            var title = 'Showtimes for '+dateText;
            //This hack determines the length of the html
            if (response.length < 5) {
              title = 'No Shows Available: '+dateText;
            }
            
            htmlbutton = '<h4>'+title+'<a href="javascript: void(0)" class="btn_tiny" onclick="alternate_1.backToScreening()" style="margin-left: 20px;">back</a></h4>';
            $(".alternate_container").prepend(htmlbutton);
            
          }, error: function(response) {
              console.log("ERROR:", response)
          }
        });
        
  },
  
  
  getScreeningByVal: function( val ) {
    
    console.log(val);
    
    var args = {"screening" : val,
                "template": $("#alt_template").html(),
                "film" : $("#film").html() };
    
    $.ajax({url: '/services/Screenings/alternate', 
          data: $.param(args), 
          type: "GET", 
          cache: true, 
          dataType: "json", 
          success: function(response) {
            console.log("Success");
            alternate_1.populateScreening( response );
          }, error: function(response) {
            console.log("Error");
             console.log("ERROR:", response)
          }
    });
    
  },
  
  populateScreening: function( response ) {
    
    alternate_1.carouselstate = "screening";
    console.log("Populate Screening from "+  alternate_1.carouselstate);
    
    $("#image_gallery_alternate").hide();
    $("#today_screenings").hide();
    
    $("#image_gallery_alternate").html("");
    
    if (alternate_1.screenings_carousel != undefined) {
      alternate_1.screenings_carousel = null;
    }
    
    //$("#today_screenings").fadeOut();
    //$("#alternate_container").fadeOut();
    
    //$("#last_screening").html("");
    $("#today_screenings").removeClass("showtimes").addClass("alternate");
    
    
    $("#alternate_film_name").html(response.film_name);
    $("#alternate_host").html(response.host);
    $("#alternate_date").html(response.date);
    $("#alternate_time").html(response.time);
    
    $("#alt_screening").html(response.screening_key);
    $("#screening").html(response.screening_key);
    
    $("#thistime").html(response.currenttime);
    $("#counttime").html(response.counttime);
    $("#time_"+response.screening_key).html(response.time_val);
    $("#host_"+response.screening_key).html(response.host_val);
    
    var thatext = $("#last_screening").html();
    $("#last_screening").html("");
    $("#today_screenings").html(thatext);
    
    screening_room.linkInvite();
    screening_room.linkPurchase();
    
    $("#today_screenings").fadeIn();
    //$("#alternate_container").fadeIn();
    
  },
  
  backToScreening: function() {
    
    alternate_1.carouselstate = "screening";
    console.log("Populate Screening from "+  alternate_1.carouselstate);
    $(".alternate_container h4:first-child").remove();
    
    $("#image_gallery_alternate").hide();
    $("#today_screenings").hide();
    
    $("#image_gallery_alternate").html("");
    
    if (alternate_1.screenings_carousel != undefined) {
      alternate_1.screenings_carousel = null;
    }
    
    //$("#last_screening").html("");
    $("#today_screenings").removeClass("showtimes").addClass("alternate");
    
    var thatext = $("#last_screening").html();
    $("#last_screening").html("");
    $("#today_screenings").html(thatext);
    
    $("#screening").html($("#alt_screening").html());
    
    screening_room.linkInvite();
    screening_room.linkPurchase();
    
    $("#today_screenings").fadeIn();
    
  }
  
}

function onExpiry() {
  $("#ocb").show();
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	alternate_1.init();
	
});
