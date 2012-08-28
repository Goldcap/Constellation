var bandwidth = {
  
  version: 1,
  //file: "http://s3.amazonaws.com/cdn.constellation.tv/prod/images/bandwidth_test.jpg",
  file: "http://s3.amazonaws.com/cdn.constellation.tv/prod/images/c8388430.jpg",
  bandwidth: -1,
  threshold: 499,
  testCount: 0,
  thid: null,
  
  init: function() {
     
     if (typeof timekeeper == 'undefined') {
      bandwidth.testBandwidth(); 
     } else if (timekeeper.currentPanel != 'video_panel') {
      if ($("#starttime").html() > $("#defaulttime").html()) {
        bandwidth.testBandwidth();
      }
     }
    
  },
  
	testBandwidth: function() {
			
      bandwidth.thid = new Date().getTime();
			var flashvars =  {filePath: bandwidth.file};
    		
      var params = 
        {
          allowScriptAccess: 'always',
          wmode: 'transparent',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'bwTest_'+bandwidth.thid,
          name: 'bwTest'+bandwidth.thid
        };
      
      swfobject.embedSWF('/flash/BandwidthCheckTester_v'+bandwidth.version+'.swf', 'bandwidth_test', '10', '10', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	reportResult: function(speed) {
		
		//console.log("BANDWIDTH SPEED: " + speed + " kb/sec");
		//bandwidth.bandwidth = speed;
		bandwidth.bandwidth = speed / 2 * 8;
    $.post("/services/BandwidthTester/report", { cud: $("#cud").html(), bandwidth: bandwidth.bandwidth } );
		//If we haven't shown the bandwidth warning...
		//Remove the Bandwidth Tester
		$("#bandwidth_wrapper").hide();
		if (screening_room == undefined) {
			return;
		}
		if ($(".bandwidth_warning").css("display") == "none") {
			if ((bandwidth.bandwidth > -1) && (bandwidth.bandwidth < bandwidth.threshold)){
				$(".current_bandwidth").html(bandwidth.bandwidth);
				$(".bandwidth_warning").fadeIn(100);
			}
		}
	}
}

function speedResult(speed) {
	bandwidth.bandwidth += speed;
	
	if (bandwidth.testCount == 1) {
		bandwidth.reportResult(bandwidth.bandwidth);
	} else {
	  swfobject.removeSWF('bwTest_'+bandwidth.thid);
	  $("body").append('<div id="bandwidth_test"></div>');
		bandwidth.testCount++;
	  bandwidth.testBandwidth();
	}
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
  bandwidth.init();
  
});
