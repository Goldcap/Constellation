var lastUpdatedAtFromAjax = 0;
var updatePanel = '';
var processing = false;
var newActivityLogs = new Array();
var firstCountdown = true;

newActivityLogs['heights'] = new Array();
newActivityLogs['content'] = new Array();

initializeCounter = function(date){
	//var startDate = new Date(date);
	
	if(firstCountdown)
	{
		firstCountdown = false;
		$('#countdown').countdown({until: date, format: 'D days H:M:S', compact: true, description: '',  compactLabels: ['y', 'm', 'w', ' days'], compactLabels1: ['y', 'm', 'w', ' day']  });
	}
	else
	{
		$('#countdown').countdown('change', { until: date });
	}
};

elementFadeIn = function($insert, newHeight){
	var lastActivityLogsCount = $('#activity-stream li').length;
	var lastActivityLogHeight = $('#activity-stream li:last').height();
	
	if(lastActivityLogsCount >= 7)
	{
		$('#activity-stream li:last').fadeOut(800, function(){ $(this).remove(); $insert.fadeIn(800, function(){ processing = false; startPushingElements() })});
	}
	else
	{
		$insert.fadeIn(800, function(){ processing = false; startPushingElements(); });
	}
	
};

startPushingElements = function(){

	if(!processing)
	{
		processing = true;

		var currentItemContent = newActivityLogs['content'].shift(); 
		var currentItem = $(currentItemContent);
		var $insert = currentItem.css({ display : 'none' }).prependTo('#activity-stream');
		var newHeight = newActivityLogs['heights'].shift();
		$('.period').timeago();
		
		if(typeof newHeight != 'undefined' && newHeight)
		{
			elementFadeIn($insert, parseInt(newHeight));
		}
		else
		{
			processing = false;
		}
	}
};

ajaxRefresh = function(lastUpdate, clickedScreeningId){

	var requestData = 'screeningId='+screeningId+'&lastUpdatedAt='+lastUpdatedAtFromAjax;
	
	if(typeof clickedScreeningId != 'undefined' && clickedScreeningId)
	{
		screeningId = clickedScreeningId;
		$('#activity-stream').html('');
		requestData = requestData + '&mostRecent=true';
		clickedScreeningId = null;
	}
	
	$.ajax({
		method: "GET",
		url: updaterUrl,
		data: requestData,
		success: function(response){
			$('.screening-activity-box .overlay').hide();
			lastUpdatedAtFromAjax = lastUpdate;			
			
			if(response.length)
			{
				$('.screening-activity-box').fadeIn();
				
				response = eval('(' + response + ')');
				
				/************** screening update host info *************************/
				if(response['host_name'] == null) // public screening
				{
					$('.screening-info-box').hide();
				}
				else
				{
					$('.screening-info-box').show();
					
					if(typeof response['host_name'] != 'undefined')
					{
						$('#host_name').html(response['host_name']);
						
						if(typeof response['host_image'] != 'undefined')
						{
							$('#host_image').attr('src', response['host_image']);
						}
						if(typeof response['host_description'] != 'undefined')
						{
							$('#host_description p').html(response['host_description']);
						}
					}
				}

				/********************* update seats available and time left *******/
				if(typeof response['available_seats'] != 'undefined')
				{
					$('#available_seats').html(response['available_seats']);
				}
				if(typeof response['js_formatted_start_date'] != 'undefined' && response['js_formatted_start_date'].length)
				{
					initializeCounter(response['js_formatted_start_date']);
				}
				if(typeof response['formatted_start_date'] != 'undefined')
				{
					var formatedDate = response['formatted_start_date'].split(',');
					var tzTime = $('#tzTime');
					$('#tzDate').text(formatedDate[0]);
					$(tzTime).text(formatedDate[1]);
					$('#tzDate').append(tzTime);
					
					$('.timezone-select').html(response['formatted_start_date']);
					$('.timezone-select').attr('name', response['edt_timestamp']);
					$('.timezone-select').attr('lang', 'dddd mmmm d, h:MMtt');
					bindTimezoneEvents();
				}			

				/********************* update activity log ************************/
				for(i in response)
				{
					if(i != 'available_seats' && i != 'start_date' && i != 'formatted_start_date' && i != 'timestamp' && i!='js_formatted_start_date' && i != 'edt_timestamp' && i != 'current_offset' && i != 'current_abbreviation')
					{
						newActivityLogs['content'].unshift(response[i]);
						$('#offlefted').html(response[i]);
						newActivityLogs['heights'].unshift($('#offlefted li').height());
					}
				
				}
				startPushingElements();
				
				/*******************************************************************************************/
			}
		}
	});
};

updateContent = function(){
	var goWithGet= false;

	$.head(staticUpdater+"?screeningId="+screeningId+"&lastUpdatedAt="+lastUpdatedAtFromAjax,{},
			function(headers) {
					$.each(headers,function(key,header){ 
										if(key == "X-Go-With-Get")
										{ 
											ajaxRefresh(header);
										}
										
						});
	});
};

$(document).ready(function(){
	if(typeof startDate != 'undefined' && startDate.length)
	{
		initializeCounter(startDate);
	}
	$('.period').timeago();
	setInterval( "updateContent()", refreshInterval);
});