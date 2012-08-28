// JavaScript Document

var test = {
  
  msgbad : "<img src=\"/images/Neu/16x16/status/important.png\" height=\"16\" width=\"16\" />",
  msgok : "<img src=\"/images/bo-success.png\" height=\"16\" width=\"16\" />",
  testCount: 0,
  bandwidth: -1,
  file: "http://s3.amazonaws.com/cdn.constellation.tv/prod/images/c8388430.jpg",
  //file: "http://s3.amazonaws.com/cdn.constellation.tv/prod/video_mq.flv",
  thid: null,
  
  submitForm: function() {
    
    if (($("input[name=email]").val() != "") && ($("input[name=message]").val() != "") && ($("input[name=javascript]").val() != "false")) {
      return true;
    }
    
    error.showError("error","Please complete all fields.","Please run the test, then enter your email<br /> and a brief explanation.");
    return false;
  },
  
  init: function() {
    $(".testResults").show();
    var bwt = $('<div id="bandwidth_test"/>');
    $('body').append(bwt);
    test.showFPVersion();
  },
  
  showFPVersion: function() {
    var version = swfobject.getFlashPlayerVersion();
    $("input[name=javascript]").val("true");
    $("input[name=flash]").val(version['major'] +"."+ version['minor']);
    $('.jscript').addClass('success-text').html(test.msgok + " Javascript is enabled in your browser.");
    if (version['major'] > 9) {
      if (version['major'] > 10) {
        $('.fversion').addClass('success-text').html(test.msgok + " You have Flash player "+ version['major'] +"."+ version['minor'] +" installed.");
      } else if ((version['major'] == 10) && ((version['minor'] < 1))) {
    	  $('.fversion').addClass('error-text').html(test.msgbad + " You need to upgrade your version of the Flash Player (Version 10.1 or above).");
      }
    } else if (version['major'] > 0) {
      $('.fversion').addClass('error-text').html(test.msgbad + " You need to upgrade your version of the Flash Player (Version 10.1 or above).");
    } else {
    	$('.fversion').addClass('error-text').html(test.msgbad + " You need to install the Flash Player in your browser (Version 10.1 or above).");
    }
	 test.testBandwidth();
  },
  
  testBandwidth: function() {
			
			test.thid = new Date().getTime();
			var flashvars =  {filePath: test.file};
    		
      var params = 
        {
          allowScriptAccess: 'always',
          wmode: 'transparent',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'bwTest_'+test.thid,
          name: 'bwTest'+test.thid
        };
      
      swfobject.embedSWF('/flash/BandwidthCheckTester_v'+bandwidth.version+'.swf', 'bandwidth_test', '10', '10', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	reportResult: function(speed) {
		//console.log("BANDWIDTH SPEED: " + speed + " kb/sec");
		test.bandwidth = speed / 2 * 8;
    $("input[name=bandwidth]").val(test.bandwidth);
    if (test.bandwidth > 499) {
      $('.bandwidth').addClass('success-text').html(test.msgok+" Your current bandwidth is "+ parseInt(test.bandwidth)+" kbps."); 
    } else {
      $('.bandwidth').addClass('error-text').html(test.msgbad+" Your current bandwidth is "+parseInt(test.bandwidth)+" kbps.");
      $(".final").html("You may experience streaming problems if your bandwidth is below 500 kbps.  Try closing any other open windows or tabs, and pausing any active downloads. ");
      $(".testStatus").fadeIn();    
    }
		$(".testbutton").fadeOut();
  }
}

function speedResult(speed) {
  test.bandwidth += speed;
  console.log("Test " + (test.testCount + 1 ) + ":: Speed in KBits - " + speed);
	console.log("Total Download Count - " + test.bandwidth);
	if (test.testCount == 1) {
		test.reportResult(test.bandwidth);
	} else {
	  swfobject.removeSWF('bwTest_'+test.thid);
	  $("body").append('<div id="bandwidth_test"></div>');
		test.testCount++;
	  test.testBandwidth();
	}
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};

});
