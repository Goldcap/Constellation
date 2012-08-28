var qanda_videoplayer = {
  
  stream: null,
  type: null,
  hostVersion: 2,
  guestVersion: 2,
  
  init: function() {
    //console.log("Q&A Video Player Init");
    qanda_videoplayer.stream = $("#room").html() + "_stream";
    if ($("#qanda_host").length > 0) {
      //console.log("Q&A Video Player Host");
      qanda_videoplayer.setHostControls();
    } else {
      //console.log("Q&A Video Player Guest");
      qanda_videoplayer.setViewerControls();
    }
    
  },
  
  //http://openvideoplayer.sourceforge.net/ovpfl/akamai_multi_player/Konfigurator.html
  setHostControls: function() {
      
      // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
      var swfVersionStr = "10.1.0";
      // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
      var xiSwfUrlStr = "/flash/playerProductInstall.swf";
      var flashvars = {streamName:qanda_videoplayer.stream, streamID:'45907', cpCode:'113557',
			bandwidthLimit:"20000", qualityLevel:"80%", keyFrameInterval:"1",
      viewerWidth:195, viewerHeight:145};
      var params = { wmode: 'transparent' };
      params.quality = "high";
      params.bgcolor = "#ffffff";
      params.allowscriptaccess = "sameDomain";
      params.allowfullscreen = "true";
      var attributes = {};
      attributes.id = "HostCam";
      attributes.name = "HostCam";
      attributes.align = "middle";
      swfobject.embedSWF(
          "/flash/HostCam_v"+qanda_videoplayer.hostVersion+".swf", "qanda_flashcontent", 
          "215", "138", 
          swfVersionStr, xiSwfUrlStr, 
          flashvars, params, attributes);
      // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
      swfobject.createCSS("#qanda_flashcontent", "display:block;text-align:left;");
  },
  
  //http://openvideoplayer.sourceforge.net/ovpfl/akamai_multi_player/Konfigurator.html
  setViewerControls: function() {
      //console.log("Q & A Joint Started ");
      // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
      var swfVersionStr = "10.1.0";
      // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
      var xiSwfUrlStr = "/flash/playerProductInstall.swf";
      var flashvars = {streamName:qanda_videoplayer.stream, streamID:'45907', cpCode:'113557', 
      auth:"yyyy", aifp:"zzzz",
      viewerWidth:195, viewerHeight:145};
      var params = { wmode: 'transparent' };
      params.quality = "high";
      params.bgcolor = "#000";
      params.allowscriptaccess = "sameDomain";
      params.allowfullscreen = "true";
      var attributes = {};
      attributes.id = "LiveStreamTest";
      attributes.name = "LiveStreamTest";
      attributes.align = "middle";
      swfobject.embedSWF(
          "/flash/LiveStream_v"+qanda_videoplayer.guestVersion+".swf", "qanda_flashcontent", 
          "195", "145", 
          swfVersionStr, xiSwfUrlStr, 
          flashvars, params, attributes);
      // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
      swfobject.createCSS("#qanda_flashcontent", "display:block;text-align:left;");
      
  }
	
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  qanda_videoplayer.init();
  
});
