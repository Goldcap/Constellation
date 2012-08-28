var error = {
  
  showing: false,
  queue: Array,
  
  showError: function( type, title, message, arg, callback ) {
    
    //We're already showing an error, so add this baby to the queue;
    if (error.showing) {
      error.queue[error.queue.length - 1] = {"type":type, "title":title, "message":message, "arg":arg, "callback":callback};
      return false;
    }
    error.showing = true;
    
    if (callback == undefined) {
      callback = "void";
    }
    
    switch (type) {
      case "notice":
        if (message == undefined) {
          message = '';
        } else {
          message = '<div class="error_notice_message">'+message+'</div>';
        }
        if ((arg === undefined) || (arg == "right")) {
          thleft = '';
          thright = '10px';
        } else {
          thleft = '10px';
          thright = '';
        }
        $.blockUI({ 
            message: '<div class="error_notice"><a href="javascript: void(0)" onclick="'+callback+'(\'click\')"><h2>'+title+'</h2></a>'+message+'</div>', 
            fadeIn: 700, 
            fadeOut: 700, 
            timeout: 3000, 
            showOverlay: false, 
            centerY: false, 
            css: {
                width: '350px',
                height: '70px',
                top: '10px', 
                left: thleft, 
                right: thright, 
                border: 'none', 
                padding: '10px', 
                backgroundColor: '#CC6600',
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: 1, 
                color: '#fff' 
            },
            onUnblock: error.release
        }); 
        break;
      case "error":
        $("#growler_title").html(title);
        if (message != undefined) {
          $("#growler p").show();
          $("#growler_body").html(message);
        } else {
          $("#growler p").hide();
        }
        
        if (arg == 0) {
          var to = false;
        } else if (arg != undefined) {
          var to = arg;
        } else {
          var to = 5000;
        }
        $.blockUI({ 
            message: $("#growler"), 
            timeout: to,
            css: {
              backgroundColor: 'transparent', border: '0px solid transparent'
            },
            onUnblock: error.release
        });
        $('#error_close').click(function() { 
            $.unblockUI(); 
            error.release();
            if (callback) {
              eval(callback+"('close')");
            }
            return false; 
        });
        break;
      default:
        //$.growlUI.settings.dockTemplate = '<div class="growlDefault"></div>';
        if (message == undefined) {
          message = '';
        } else {
          message = '<div class="error_notice_message">'+message+'</div>';
        }
        if ((arg === undefined) || (arg == "right")) {
          thleft = '';
          thright = '10px';
        } else {
          thleft = '10px';
          thright = '';
        }
        $.blockUI({ 
            message: '<div class="error_notice"><a href="javascript: void(0)" onclick="'+callback+'(\'click\')"><h2>'+title+'</h2></a>'+message+'</div>', 
            showOverlay: false, 
            centerY: false, 
            css: {
                width: '350px',
                height: '70px',
                top: '10px', 
                left: thleft, 
                right: thright, 
                border: 'none', 
                padding: '10px', 
                backgroundColor: '#CC6600',
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: 1, 
                color: '#fff' 
            },
            onUnblock: error.release
        });
        break;
    }
    
    if (typeof videoplayer  != "undefined") {
      if (document.getElementById(videoplayer.div) != undefined) {
				// let's not do this for now
        // document.getElementById(videoplayer.div).displayMessage("notice", title, message);
      }
    }
  },
  
  release: function(element, options) {
    error.showing = false;
    error.processQueue();
  },
  
  processQueue: function() {
    if (error.queue == undefined)
        return;
    if (error.queue.length == 0) {
      return;
    }
    for (i=0;i<error.queue.length;i++) {
      if (error.queue[i] == undefined)
        continue;
      error.showError( error.queue[i]["type"], error.queue[i]["title"], error.queue[i]["message"], error.queue[i]["args"], error.queue[i]["callback"] );
    }
    error.queue = null;
  }
  
}

function showError( type, title, message, side ) {
  error.showError( type, title, message, side );
}

$(document).ready(function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
});
