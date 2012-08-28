/* Horizontal Accordion script
* Created: Oct 27th, 2009. This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
*/

var haccordion={
	//customize loading message if accordion markup is fetched via Ajax:
	ajaxloadingmsg: '<div style="margin: 1em; font-weight: bold"><img src="ajaxloadr.gif" style="vertical-align: middle" /></div>',

	accordioninfo: {}, //class that holds config information of each haccordion instance

	expandli:function(accordionid, targetli){
		var config=haccordion.accordioninfo[accordionid]
		var $targetli=(typeof targetli=="number")? config.$targetlis.eq(targetli) : (typeof targetli=="string")? jQuery('#'+targetli) : jQuery(targetli)
		if (typeof config.$lastexpanded!="undefined") //targetli may be an index, ID string, or DOM reference to LI
			config.$lastexpanded.stop().animate({width:config.paneldimensions.peekw}, config.speed) //contract last opened content
		$targetli.stop().animate({width:$targetli.data('hpaneloffsetw')}, config.speed) //expand current content
		config.$lastexpanded=$targetli
	},

	rotateAccordion:function(accordionid) {
		var config=haccordion.accordioninfo[accordionid];
		var nextli = $(config.$lastexpanded).next()[0];
		if (nextli == null) {
			haccordion.expandli(accordionid, 0);
		} else {
			haccordion.expandli(accordionid, nextli);
		}
	},


	urlparamselect:function(accordionid){
		var result=window.location.search.match(new RegExp(accordionid+"=(\\d+)", "i")) //check for "?accordionid=index" in URL
		if (result!=null)
			result=parseInt(RegExp.$1)+"" //return value as string so 0 doesn't test for false
		return result //returns null or index, where index is the desired selected hcontent index
	},

	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i") //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value){
		document.cookie = name + "=" + value + "; path=/"
	},


	loadexternal:function($, config){ //function to fetch external page containing the entire accordion content markup
		var $hcontainer=$('#'+config.ajaxsource.container).html(this.ajaxloadingmsg)
		$.ajax({
			url: config.ajaxsource.path, //path to external content
			async: true,
			error:function(ajaxrequest){
				$hcontainer.html('Error fetching content.<br />Server Response: '+ajaxrequest.responseText)
			},
			success:function(content){
				$hcontainer.html(content)
				haccordion.init($, config)
			}
		})
	},


	init:function($, config){
			haccordion.accordioninfo[config.accordionid]=config //cache config info for this accordion
			var $targetlis=$('#'+config.accordionid).find('ul:eq(0) > li') //find top level LIs
			config.$targetlis=$targetlis
			config.selectedli=config.selectedli || [] //set default selectedli option
			config.speed=config.speed || "normal" //set default speed
			$targetlis.each(function(i){
				var $target=$(this).data('pos', i) //give each li an index #
				$target.data('hpaneloffsetw', $target.find('.hpanel:eq(0)').outerWidth()) //get offset width of each .hpanel DIV (config.dimensions.fullw + any DIV padding)
				$target.mouseenter(function(){
						haccordion.expandli(config.accordionid, this)
					config.$lastexpanded=$(this)
				})
				if (config.collapsecurrent){ //if previous content should be contracted when expanding current
					$target.mouseleave(function(){
						$(this).stop().animate({width:config.paneldimensions.peekw}, config.speed) //contract previous content
					})
				}				
			}) //end $targetlis.each
			var selectedli=haccordion.urlparamselect(config.accordionid) || ((config.selectedli[1] && haccordion.getCookie(config.accordionid))? parseInt(haccordion.getCookie(config.accordionid)) : config.selectedli[0])
			selectedli=parseInt(selectedli)
			if (selectedli>=0 && selectedli<config.$targetlis.length){ //if selectedli index is within range
				config.$lastexpanded=$targetlis.eq(selectedli)
				config.$lastexpanded.css('width', config.$lastexpanded.data('hpaneloffsetw')) //expand selected li
			}
			$(window).bind('unload', function(){ //clean up and persist on page unload
				haccordion.uninit($, config)
			}) //end window.onunload
	},

	uninit:function($, config){
		var $targetlis=config.$targetlis
		var expandedliindex=-1 //index of expanded content to remember (-1 indicates non)
		$targetlis.each(function(){
			var $target=$(this)
			$target.unbind()
			if ($target.width()==$target.data('hpaneloffsetw'))
				expandedliindex=$target.data('pos')
		})
		if (config.selectedli[1]==true) //enable persistence?
			haccordion.setCookie(config.accordionid, expandedliindex)
	},

	setup:function(config){
		//Use JS to write out CSS that sets up initial dimensions of each LI, for JS enabled browsers only
		document.write('<style type="text/css">\n')
		document.write('#'+config.accordionid+' li{width: '+config.paneldimensions.peekw+';\nheight: '+config.paneldimensions.h+';\n}\n')
		document.write('#'+config.accordionid+' li .hpanel{width: '+config.paneldimensions.fullw+';\nheight: '+config.paneldimensions.h+';\n}\n')
		document.write('<\/style>')
		jQuery(document).ready(function($){ //on Dom load
			if (config.ajaxsource) //if config.ajaxsource option defined
				haccordion.loadexternal($, config)
			else
				haccordion.init($, config)
		}) //end DOM load
	}

}