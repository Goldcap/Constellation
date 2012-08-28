var upcoming_alternate = {
  
  carouselstate: "screening",
  theDays: null,
  specialDays: null,
  thepage: 'date',
  m_names : new Array("Jan", "Feb", "Mar", 
        "Apr", "May", "Jun", "Jul", "Aug", "Sep", 
        "Oct", "Nov", "Dec"),
  
  init: function() {
    
     //If we're in the hosting step, show the popup
    $(".purchase_link").click( function(e) {
       e.preventDefault();
       console.log("purchase_link");
       screening_room.invite();
    });
    
    
    if (window.location.pathname.match(/\/film/)) {
      upcoming_alternate.thepage = 'film';
      getUrl = '/services/Screenings/upcoming?film='+$("#film").html();
    } else {
      getUrl = '/services/Screenings/upcoming';
    }
    
    $.ajax({url: getUrl, 
      type: "GET", 
      cache: true, 
      dataType: "text", 
      success: function(response) {
        upcoming_alternate.startup(response);
      }, error: function(response) {
         console.log("ERROR:", response)
      }
    });
    
  },
  
  startup: function( response ) {
    
    upcoming_alternate.theDays = (eval("(" + response + ")"));
    if (upcoming_alternate.theDays != null)
      upcoming_alternate.specialDays = upcoming_alternate.theDays.days;
    
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    
    $( "#featured_datepicker_alternate" ).datepicker({
      showOn: "button",
      buttonText: upcoming_alternate.m_names[curr_month] + '<br />' + curr_date,
      minDate: 0,
      maxDate: 60,
      beforeShowDay: function(thedate) { 
        var theyear = thedate.getFullYear();
        var themonth = thedate.getMonth() + 1;
        var theday = thedate.getDate();
        if ((upcoming_alternate.specialDays != undefined) && (upcoming_alternate.specialDays[theyear] != undefined) && (upcoming_alternate.specialDays[theyear][themonth] != undefined)) {
          if(upcoming_alternate.specialDays[theyear][themonth][theday] == undefined ) return [true,""]; 
          return [true, "specialDate"]; 
        } else {
          return [true,""]; 
        }
      },
      
      onSelect: function(dateText, inst) {
        console.log("Selected DPS");
        $("#current_date").html(dateText);
        upcoming_alternate.getUpcomingFeatures(dateText,inst);
        upcoming_alternate.getUpcomingShows(dateText,inst);
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
  },
  
  getUpcomingFeatures: function(dateText,inst) {
    var args = {"date" : dateText,
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
               upcoming_alternate.getScreeningByVal( $(this).attr("title") );
            });
            
          }, error: function(response) {
            console.log("ERROR:", response)
          }
    });
  },
  
  getUpcomingShows: function(dateText,inst) {
  
    
    var args = {"date" : dateText,
                "film" : $("#film").html() };
    dateSplit = dateText.split("/");
    $.ajax({url: '/services/Screenings/'+upcoming_alternate.thepage, 
          data: $.param(args), 
          type: "GET", 
          cache: true, 
          dataType: "text", 
          success: function(response) {
             $(".ui-datepicker-trigger").html(upcoming_alternate.m_names[parseInt(dateSplit[0]) - 1]+'<br />'+dateSplit[1]);
             
             console.log("Swapping classes");
             if (upcoming_alternate.carouselstate == "screening") {
                $("#last_screening").html($("#today_screenings").html());
             }
             $("#today_screenings").removeClass("alternate").addClass("showtimes");
             
             $("#image_gallery_alternate").fadeIn();
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
               upcoming_alternate.getScreeningByVal( $(this).attr("title") );
            });
            
            upcoming_alternate.carouselstate = "upcoming";
          
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
            upcoming_alternate.populateScreening( response );
          }, error: function(response) {
            console.log("Error");
             console.log("ERROR:", response)
          }
    });
    
  },
  
  populateScreening: function( response ) {
    
    upcoming_alternate.carouselstate = "screening";
    console.log("Populate Screening from "+  upcoming_alternate.carouselstate);
    
    $("#image_gallery_alternate").hide();
    $("#today_screenings").hide();
    
    $("#image_gallery_alternate").html("");
    
    if (upcoming_alternate.screenings_carousel != undefined) {
      upcoming_alternate.screenings_carousel = null;
    }
    
    //$("#today_screenings").fadeOut();
    //$("#alternate_container").fadeOut();
    
    //$("#last_screening").html("");
    $("#today_screenings").removeClass("showtimes").addClass("alternate");
    
    
    $("#alternate_film_name").html(response.film_name);
    $("#alternate_host").html(response.host);
    $("#alternate_date").html(response.date);
    $("#alternate_time").html(response.time);
    
    $("#thistime").html(response.currenttime);
    $("#counttime").html(response.counttime);
    $("#time_"+response.screening_key).html(response.time_val);
    $("#host_"+response.screening_key).html(response.host_val);
    
    var thatext = $("#last_screening").html();
    $("#last_screening").html("");
    $("#today_screenings").html(thatext);
    
    screening_room.linkPurchase();
    
    $("#today_screenings").fadeIn();
    //$("#alternate_container").fadeIn();
    
  }
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	upcoming_alternate.init();
	
});
