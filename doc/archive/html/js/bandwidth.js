var bandwidth = {
  
  version: 1,
  //file: "http://s3.amazonaws.com/cdn.constellation.tv/prod/images/bandwidth_test.jpg",
  file: "https://www.constellation.tv/images/bandwidth_test.jpg",
  bandwidth: -1,
  threshold: 600,
  
  init: function() {
    
     bandwidth.testBandwidth();
    
  },
  
	testBandwidth: function() {
			
      var flashvars =  {filePath: bandwidth.file};
    		
      var params = 
        {
          allowScriptAccess: 'always',
          wmode: 'opaque',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'bwTest',
          name: 'bwTest'
        };
      
      swfobject.embedSWF('/flash/BandwidthCheckTester_v'+bandwidth.version+'.swf', 'bandwidth_test', '10', '10', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	reportResult: function(speed) {
		console.log("BANDWIDTH SPEED: " + speed + " kb/sec");
		bandwidth.bandwidth = speed;
		//If we haven't shown the bandwidth warning...
		if ((screening_room != undefined) && ($(".bandwidth_warning").css("display") == "none")) {
			if ((bandwidth.bandwidth > -1) && (bandwidth.bandwidth < bandwidth.threshold)){
				$(".current_bandwidth").html(bandwidth.bandwidth);
				$(".bandwidth_warning").fadeIn(100);
			}
		}
	}
}

function speedResult(speed) {
	bandwidth.reportResult(speed);
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
  bandwidth.init();
  
});
