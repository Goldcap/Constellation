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
