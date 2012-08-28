// JavaScript Document

var HUD = {
	
	errorSleepTime: 5000,
	
	init: function() {
	
		$(".show_hud").fadeIn();
    $(".show_log").fadeIn();    
		
    $("#hud_save").click(function(e){
			e.preventDefault();
			HUD.set();
		});
		
		$(".applyButton").click(function(e){
			e.preventDefault();
			HUD.setDynamicValue($(this));
		});
		
		$(".hud-click").click(function(e){
			$("#hud-popup").fadeIn();
      $("#video_stream").width("320px");
  		$("#video_stream").height("240px");
			$(".show_hud").fadeOut();
		});
		
		$(".hud_close").click(function(e){
			$("#hud-popup").fadeOut();
      $("#video_stream").width("100%");
  		$("#video_stream").height("100%");
      //May need to add this back in...
      //$("#video_stream").css("margin-right","300px");
			$(".show_hud").fadeIn();
		});
		
		$("#hud_reload").click(function(e){
			e.preventDefault();
			//console.log("Restarting Host Viewer");
			videoplayer.camViewerReStart();
			var args = {body:"hostset:reload|hostval:null"};
			$.postJSON("/services/chat/post", args, HUD.finishPost );
		});
		
		$("#qualityLevel_display").html($("#qualityLevel").val());
		$("#qualityLevel_slider" ).slider({
											value:$("#qualityLevel").val(),
											min: 0,
											max: 100,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#qualityLevel").val(ui.value);
												$("#qualityLevel_display").html(ui.value);
											}
										});
		
		
		$("#keyFrameInterval_display").html($("#keyFrameInterval").val());		
		$("#keyFrameInterval_slider" ).slider({
											value:$("#keyFrameInterval").val(),
											min: 0,
											max: 48,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#keyFrameInterval").val(ui.value);
												$("#keyFrameInterval_display").html(ui.value);
											}
										});
										
		$("#captureFps_display").html($("#captureFps").val());
		$("#captureFps_slider" ).slider({
											value:$("#captureFps").val(),
											min: 0,
											max: 120,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#captureFps").val(ui.value);
												$("#captureFps_display").html(ui.value);
											}
										});
		
		$("#bufferMin_display").html($("#bufferMin").val());
		$("#bufferMin_slider" ).slider({
											value:$("#bufferMin").val(),
											min: 0,
											max: 120,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#bufferMin").val(ui.value);
												$("#bufferMin_display").html(ui.value);
											}
										});
		
		$("#bufferMax_display").html($("#bufferMax").val());
		$("#bufferMax_slider" ).slider({
											value:$("#bufferMax").val(),
											min: 0,
											max: 240,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#bufferMax").val(ui.value);
												$("#bufferMax_display").html(ui.value);
											}
										});
										
		$("#micRate_display").html($("#micRate").val());
		$("#micRate_slider" ).slider({
											value:$("#micRate").val(),
											min: 1,
											max: 5,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												switch (ui.value) {
													case 1:
														var thevla = 5;
														break;
													case 2:
														var thevla = 8;
														break;
													case 3:
														var thevla = 11;
														break;
													case 4:
														var thevla = 22;
														break;
													case 5:
														var thevla = 44;
														break;
												}
												$("#micRate").val(thevla);
												$("#micRate_display").html(thevla);
											}
										});
										
		$("#micGain_display").html($("#micGain").val());
		$("#micGain_slider" ).slider({
											value:$("#micGain").val(),
											min: 0,
											max: 100,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#micGain").val(ui.value);
												$("#micGain_display").html(ui.value);
											}
										});
										
		$("#motionTimeout_display").html($("#motionTimeout").val());
		$("#motionTimeout_slider" ).slider({
											value:$("#motionTimeout").val(),
											min: 0,
											max: 120000,
											step: 1000,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#motionTimeout").val(ui.value);
												$("#motionTimeout_display").html(ui.value);
											}
										});
										
		$("#silenceLevel_display").html($("#silenceLevel").val());
		$("#silenceLevel_slider" ).slider({
											value:$("#silenceLevel").val(),
											min: 0,
											max: 100,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#silenceLevel").val(ui.value);
												$("#silenceLevel_display").html(ui.value);
											}
										});
										
		$("#micSilenceTimeout_display").html($("#micSilenceTimeout").val());
		$("#micSilenceTimeout_slider" ).slider({
											value:$("#micSilenceTimeout").val(),
											min: 0,
											max: 120000,
											step: 1000,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#micSilenceTimeout").val(ui.value);
												$("#micSilenceTimeout_display").html(ui.value);
											}
										});
										
	},
	
	get: function() {
		var args = {"host":$("#hostid").html()};
		$.ajax({url: "/services/HUD/get", 
              type: "GET", 
              cache: false, 
              dataType: "json",
              data: $.param(args),
              timeout: HUD.errorSleepTime,
              success: HUD.setDefaults,
              error: HUD.onError});
	},
	
	setDefaults: function( response ) {
		for (var key in response.hudSettings) {
			//console.log("Setting "+key+" to "+response.hudSettings[key]);
			switch (key) {
				case "bandwidthLimit":
					$("#bandwidthLimit").val(response.hudSettings[key]);
					break;
				case "echoSuppression":
					if (response.hudSettings[key] == "true") {
						$("#echoSuppression_on").attr("checked","checked");
					} else {
						$("#echoSuppression_off").attr("checked","checked");
					}
					break;
				case "enhancedMicrophone":
					if (response.hudSettings[key] == "true") {
						$("#enhancedMicrophone_on").attr("checked","checked");
					} else {
						$("#enhancedMicrophone_off").attr("checked","checked");
					}
					break;
				case "enableVAD":
					if (response.hudSettings[key] == "true") {
						$("#enableVAD_on").attr("checked","checked");
					} else {
						$("#enableVAD_off").attr("checked","checked");
					}
					break;
				default:
					$("#"+key+"_display").html(response.hudSettings[key]);
					$("#"+key+"_slider" ).slider({"value":response.hudSettings[key]});
					break;
			}
	  }
		
		
	},
	
	set: function() {
		
    var settings = $("#hud_settings").formToDict();
		var args = {"host":$("#hostid").html(),
								"settings":settings};
		$.ajax({url: "/services/HUD/set", 
              type: "GET", 
              cache: false, 
              dataType: "text",
              data: $.param(args),
              timeout: HUD.errorSleepTime,
              success: HUD.onSuccess,
              error: HUD.onError});
	},
	
	onSuccess: function(response) {
		$("#hud_message").fadeIn();
		$("#hud_message").html("Success!").delay(3000).fadeOut();
	},
	
	onError: function(error) {
		$("#hud_message").fadeIn();
		$("#hud_message").html("There was an error.").delay(3000).fadeOut();
	},
	
	setDynamicValue: function( element ) {
		var elem = $(element).attr("id").replace("Submit","");
		var funcname ='host_update_'+elem;
		switch(funcname){
			case "host_update_bandwidthLimit":
				value = $("#"+elem).val();
			break;
			case "host_update_qualityLevel":
				value = $("#"+elem).val();
			break;
			case "host_update_keyFrameInterval":
				value = $("#"+elem).val();
			break;
			case "host_update_captureFps":
				value = $("#"+elem).val();
			break;
			case "host_update_bufferMin":
				value = $("#"+elem).val();
			break;
			case "host_update_bufferMax":
				value = $("#"+elem).val();
			break;
			case "host_update_micGain":
				value = $("#"+elem).val();
			break;
			case "host_update_motionTimeout":
				value = $("#"+elem).val();
			break;
			case "host_update_echoSuppression":
				if ($('#echoSuppression_on:checked').val() == 1) {
					value = "true";
				} else {
					value = "false";
				}
			break;
			case "host_update_enhancedMicrophone":
				if ($('#echoSuppression_on:checked').val() == "true") {
					value = "true";
				} else {
					value = "false";
				}
			break;
			case "host_update_silenceLevel":
				value = $("#"+elem).val();
			break;
			case "host_update_micSilenceTimeout":
				value = $("#"+elem).val();
			break;
			case "host_update_enableVAD":
				if ($('#enableVAD_on:checked').val() == "true") {
					value = "true";
				} else {
					value = "false";
				}
			break;
		}
		//console.log(funcname+" "+value);
		if (document.getElementById('myHost') != undefined) {
			//console.log("HOST!");
			HUD.pushDynamicValue( funcname, value );
		} else {
			//console.log("USER!");
			HUD.callDynamicFunction( funcname, value );
		}
	},
	
	pushDynamicValue: function( funcname, value ) {
		switch(funcname){
			case "host_update_bandwidthLimit":
		   	$("#bandwidthLimit").val(value);
				document.getElementById('myHost').host_update_bandwidthLimit(value);
			break;
			case "host_update_qualityLevel":
		   	$("#qualityLevel_display").html(value);
				$("#qualityLevel_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_qualityLevel(value);
			break;
			case "host_update_keyFrameInterval":
		   	$("#keyFrameInterval_display").html(value);
				$("#keyFrameInterval_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_keyFrameInterval(value);
			break;
			case "host_update_captureFps":
		   	$("#captureFps_display").html(value);
				$("#captureFps_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_captureFPS(value);
			break;
			case "host_update_bufferMin":
		   	$("#bufferMin_display").html(value);
				$("#bufferMin_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_bufferMin(value);
			break;
			case "host_update_bufferMax":
		   	$("#bufferMax_display").html(value);
				$("#bufferMax_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_bufferMax(value);
			break;
			case "host_update_micGain":
		   	$("#micGain_display").html(value);
				$("#micGain_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_micGain(value);
			break;
			case "host_update_motionTimeout":
		   	$("#motionTimeout_display").html(value);
				$("#motionTimeout_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_motionTimeout(value);
			break;
			case "host_update_echoSuppression":
				if (value == "true") {
					$("#echoSuppression_on").attr("checked","checked");
				} else {
					$("#echoSuppression_off").attr("checked","checked");
				}
				document.getElementById('myHost').host_update_echoSuppression(value);
			break;
			case "host_update_enhancedMicrophone":
		   	if (value == "true") {
					$("#enhancedMicrophone_on").attr("checked","checked");
				} else {
					$("#enhancedMicrophone_off").attr("checked","checked");
				}
				document.getElementById('myHost').host_update_enhancedMicrophone(value);
			break;
			case "host_update_silenceLevel":
		   	$("#silenceLevel_display").html(value);
				$("#silenceLevel_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_silenceLevel(value);
			break;
			case "host_update_micSilenceTimeout":
		   	$("#micSilenceTimeout_display").html(value);
				$("#micSilenceTimeout_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_micSilenceTimeout(value);
			break;
			case "host_update_enableVAD":
		   	if (value == "true") {
					$("#enableVAD_on").attr("checked","checked");
				} else {
					$("#enableVAD_off").attr("checked","checked");
				}
				document.getElementById('myHost').host_update_enableVAD(value);
			break;
		}
	},
	
	callDynamicFunction: function( name, value ) {
		var args = {body:"hostset:"+name+"|hostval:"+value};
		$.postJSON("/services/chat/post", args, HUD.finishPost );
	},
	
	incomingMessage: function( message ) {
		vals = message.html.split("|");
		var name=vals[0].split(":");
		var value=vals[1].split(":");
		console.log(name[1]+" is set to "+ value[1]);
		if (name[1]=="reload") {
			console.log("restarting");
			videoplayer.camHostReStart();
		} else {
			console.log("setting new value of "+name[1]+" " + value[1]);
			if (document.getElementById('myHost') != undefined) {
				HUD.pushDynamicValue(name[1],value[1]);
			}
		}
	},
	
	finishPost: function( response ) {
		//console.log("Finished");
	}
	
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if ($("#hud-popup").length == 1) {
	    HUD.init();
	    HUD.get();
	  }
});
