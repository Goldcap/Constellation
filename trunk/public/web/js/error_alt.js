var error = {
  
  showing: false,
  queue: new Array(),
  
	//Type: notice|alert|error|default
  //Arg: left|right (in the case of 'notice' or 'default') |timeout (in the case of 'error')
  //Callback: return function
  showError: function( type, title, message, arg, callback, fromQueue ) {
    
    if (callback == undefined) {
      callback = "void";
    }
    
    if (fromQueue == undefined) {
      fromQueue = false;
    }
    
    //We're already showing an error, so add this baby to the queue;
    if ((error.showing) && (! fromQueue))  {
			//error.queue[error.queueIndex] = {"type":type, "title":title, "message":message, "arg":arg, "callback":callback};
      var item = {"type":type, "title":title, "message":message, "arg":arg, "callback":callback};
      error.queue.push(item);
    }
    
    if (error.showing) {
			return false;
		}
		
		//console.log("Error Type " + type);
    error.showing = true;
    
    switch (type) {
      case "notice":
        //console.log("Mark Notice");
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
                opacity: 1, 
                color: '#fff' 
            },
            onUnblock: error.release
        }); 
        break;
      case "alert":
        //console.log("Mark Alert");
				$("#alert_title").html(title);
        if (message != undefined) {
          $("#alertAlt p").show();
          $("#alert_body").html(message);
        } else {
          $("#alertAlt p").hide();
        }
        
        if (arg == 0) {
          var to = false;
        } else if (arg != undefined) {
          var to = arg;
        } else {
          var to = 5000;
        }
        $.blockUI({
            message: $("#alertAlt"), 
            timeout: to,
            css: {
              backgroundColor: 'transparent', border: '0px solid transparent'
            },
            onUnblock: error.release
        });
        $('.error_close').click(function() { 
            $.unblockUI(); 
            error.release();
            if (callback) {
              eval(callback+"('close')");
            }
            return false; 
        });
        break;
			case "error":
        //console.log("Mark Error");
				$("#growler_title").html(title);
        if (message != undefined) {
          $("#growlerAlt p").show();
          $("#growlerAlt #growler_body").html(message);
        } else {
          $("#growlerAlt p").hide();
        }
        
        if (arg == 0) {
          var to = false;
        } else if (arg != undefined) {
          var to = arg;
        } else {
          var to = 5000;
        }
        //console.log("Arguments are " + arg);
        //console.log("To is " + to);
        
        $.blockUI({ 
            message: $("#growlerAlt"), 
            timeout: to,
            css: {
              backgroundColor: 'transparent', border: '0px solid transparent'
            },
            onUnblock: error.release
        });
        $('.error_close').click(function() {
        		//console.log("Mark Error Close");
            $.unblockUI(); 
            error.release();
            if (callback) {
              eval(callback+"('close')");
            }
            return false; 
        });
        break;
      default:
      	//console.log("Mark Default");
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
                backgroundColor: '#CC6600',
                opacity: 1 
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
  
  unblock: function() {
		$.unblockUI();
		error.showing = false;
		if (typeof modal != "undefined")
			    	modal.modalDestroy();
	},
	
  release: function(element, options) {
  	//console.log("Error Release");
    error.showing = false;
    if (error.queue.length == 0) {
      return;
    }
    error.processQueue();
  },
  
  processQueue: function() {
  	//console.log("PROCESS QUEUE");
    if (error.queue == undefined)
        return;
    var theerror = error.queue.shift();
    error.showError( theerror.type, theerror.title, theerror.message, theerror.arg, theerror.callback, true );
    
    //error.queue = null;
  }
  
}

function showError( type, title, message, side ) {
  error.showError( type, title, message, side );
}

$(document).ready(function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
});
