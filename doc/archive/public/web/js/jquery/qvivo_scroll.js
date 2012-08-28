/**
 * QVIVO Scrollbar Plugin
 *
 * Copyright (c) QVIVO Limited
 * Dual licensed under the MIT and GPL licenses:
 * Created by Liam McCallum
 *
 */
 
$.fn.extend({
	qvivoScroll: function() {
		this.each(function(i) {
			$(this).wrapInner('<div class="content_wrapper" />').prepend('<div class="scroll_track"><div class="scroll_track_footer"><div class="scroll_bar"><div class="scroll_bar_top"></div><div class="scroll_bar_bottom"></div></div></div></div>').wrapInner('<div class="scroll_wrapper" />');
			var scrollWrapper = $(this).children('.scroll_wrapper');
			var track = scrollWrapper.children('.scroll_track');
			var content = scrollWrapper.children('.content_wrapper');
			if ($(this).attr('id') == 'navigation')
				content.append('<div class="content_padding"></div>')
			var scrollBar = track.children('div').children('.scroll_bar');
			var mouseOver = false;
			scrollBar.hover(
				function () { mouseOver = true; }, 
				function () { mouseOver = false; }
			);
			var updateScroll = function() {
				if (!mouseOver){ // stops scrollbar jumping
					var ratio = (track.height() / content.height());
					if (ratio < 1) { // content is big enough to show the scrollbar
						var top = Math.min(Math.round(scrollWrapper.scrollTop() * ratio), content.height() - scrollBar.height());
						scrollBar.css({top: top + 'px'});
						if ((ratio * track.height()) < 100)
							scrollBar.css({height: '20%'});
						else
							scrollBar.css({height: (ratio * 100) + '%'});
						if ($.browser.msie && $.browser.version == 8 )
							scrollBar.show();
						else
							scrollBar.fadeIn(200);
					}
					else { // content is small enough to hide the scroll bar
						if ($.browser.msie && $.browser.version == 8 )
							scrollBar.hide();
						else
							scrollBar.fadeOut(200);
					}
				}
			}
			updateScroll();
			window.setInterval(function() { updateScroll(); }, 400);
			scrollWrapper.scroll(function() { updateScroll(); });
			scrollBar.draggable({ containment: 'parent' }).bind('drag', function(event, ui) {
				var actualDown = scrollBar.css('top').split('px')[0];
				var maxDown = scrollBar.parent().outerHeight() - $(this).outerHeight();
				var maxUp = content.height() - scrollWrapper.height();
				scrollWrapper.scrollTop(Math.floor((actualDown / maxDown) * maxUp));
			});
		});
	}
});