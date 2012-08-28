/**
 * cust_checkbox_plugin.js
 * Copyright (c) 2009 myPocket technologies (www.mypocket-technologies.com)
 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * View the GNU General Public License <http://www.gnu.org/licenses/>.

 * @author Darren Mason (djmason9@gmail.com)
 * @date 7/3/2009
 * @projectDescription	Replaces the standard HTML form checkbox or radio buttons. Allows for disable, and very customizable.
 * @version 1.0.6
 * 
 * @requires jquery.js (tested with 1.3.2)
 * 
 * @param disable_all:	false,
 * @param hover:	true,
 * @param wrapperclass:	"group"
 * @param callback:	function(){ * your code here * }
 */

(function($) {	
	$.fn.custCheckBox = function(options){
		
		var defaults = {
				disable_all:	false,				//disables all the elements
				hover:	true,						//adds a hover state to the tag
				wrapperclass:	"group",			//the class name of the wrapper tag
				callback:	function(){}			//a click event call back
			};
		//override defaults
		var opts = $.extend(defaults, options);
		
		return this.each(function() { 
	 		 var obj = $(this);
	 		 
		$.fn.buildbox = function(thisElm){
			
			$(thisElm).css({display:"none"}).before("<span class=\"cust_checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;</span>");
			
			var isChecked = $(thisElm).attr("checked");
			var boxtype = $(thisElm).attr("type");
			var disabled = $(thisElm).attr("disabled");
			
			if(boxtype === "checkbox")
			{
				$(thisElm).prev("span").addClass("checkbox");
				if(disabled || opts.disable_all){boxtype = "checkbox_disabled";}
			}
			else
			{
				$(thisElm).prev("span").addClass("radio");
				if(disabled || opts.disable_all){boxtype = "radio_disabled";}
			}
			
			if(isChecked)
				$(thisElm).prev("span").addClass("cust_"+boxtype+"_on");
			else
				$(thisElm).prev("span").addClass("cust_"+boxtype+"_off");
			
			if(opts.disable_all)
				$(thisElm).attr("disabled","disabled");
			
			
			//attach a click event for each label.
			
			$(thisElm).prev("span").prev("label").unbind().click(function(){
						
				if(!opts.disable_all)
				{
					var custbox = $(this).next("span");
					var boxtype = $(custbox).next("input").attr("type");
					var disabled = $(custbox).next("input").attr("disabled");

					if($(custbox).hasClass("checkbox"))
					{
						if($(custbox).hasClass("cust_"+boxtype+"_off") && !disabled)
						{
							$(custbox).removeClass("cust_"+boxtype+"_off").addClass("cust_"+boxtype+"_on").next("input").attr("checked","checked").change(); //turn on
						}
						
						else if(!disabled)		
						{
							$(custbox).removeClass("cust_"+boxtype+"_on").addClass("cust_"+boxtype+"_off").next("input").removeAttr("checked").change(); //turn off
							$(custbox).removeClass("cust_"+boxtype+"_hvr");
						}
						
						
					}
					else if(!disabled)
					{
						$(custbox).parent().parent().find(".cust_checkbox").removeClass("cust_"+boxtype+"_on").addClass("cust_"+boxtype+"_off").next("input").removeAttr("checked").change();
						$(custbox).removeClass("cust_"+boxtype+"_off").addClass("cust_"+boxtype+"_on").next("input").attr("checked","checked").change(); //turn on
						$(custbox).removeClass("cust_"+boxtype+"_hvr");
					}
					
					opts.callback.call(this);					 
					
				}
						
			}).hover(function(){
				var custbox = $(this).next("span");
				if($(custbox).hasClass("cust_checkbox") && $(custbox).hasClass("checkbox") && opts.hover)
					$(custbox).addClass("cust_checkbox_hvr");
				else if($(custbox).hasClass("cust_checkbox") && $(custbox).hasClass("radio") && opts.hover)
					$(custbox).addClass("cust_radio_hvr");
				
			},function(){
				var custbox = $(this).next("span");
				if($(custbox).hasClass("cust_checkbox") && opts.hover)
					$(custbox).removeClass("cust_checkbox_hvr").removeClass("cust_radio_hvr");
				
			});
		
			//attach a click event for each checkbox.
			$(thisElm).prev("span").unbind().click(function(){
			
				if(!opts.disable_all)
				{
					var boxtype = $(this).next("input").attr("type");
					var disabled = $(this).next("input").attr("disabled");

					if($(this).hasClass("checkbox"))
					{
						if($(this).hasClass("cust_"+boxtype+"_off") && !disabled)
							$(this).removeClass("cust_"+boxtype+"_off").addClass("cust_"+boxtype+"_on").next("input").attr("checked","checked").change(); //turn on
						else if(!disabled)
						{
							$(this).removeClass("cust_"+boxtype+"_on").addClass("cust_"+boxtype+"_off").next("input").removeAttr("checked").change(); //turn off
							$(this).removeClass("cust_"+boxtype+"_hvr");
						}
					}
					else if(!disabled)
					{
						$(this).parent().parent().find(".cust_checkbox").removeClass("cust_"+boxtype+"_on").addClass("cust_"+boxtype+"_off").next("input").removeAttr("checked").change();
						$(this).removeClass("cust_"+boxtype+"_off").addClass("cust_"+boxtype+"_on").next("input").attr("checked","checked").change(); //turn on
						$(this).removeClass("cust_radio_hvr");
					}

					opts.callback.call(this);

				}
			}).hover(function(){
				if($(this).hasClass("cust_checkbox") && $(this).hasClass("checkbox") && opts.hover)
					$(this).addClass("cust_checkbox_hvr");
				else if($(this).hasClass("cust_checkbox") && $(this).hasClass("radio") && opts.hover)
					$(this).addClass("cust_radio_hvr");
			},function(){
				if($(this).hasClass("cust_checkbox") && opts.hover)
					$(this).removeClass("cust_checkbox_hvr").removeClass("cust_radio_hvr");
			});			
			
		};
		
		//build the boxes
		$.fn.buildbox($(obj));
		
		
		
		
	}); 	
};
	
})(jQuery);
