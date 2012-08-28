// JavaScript Document
var colorme = {

	user: null,
	post: null,
	posl: null,
	icon: null,
	userElement: null,
	animating: false,
	status: null,
/*lpos: 0,
	rpos: null,
	tpos: 0,
	bpos: null,*/
	imagesize: null,
	imagesmall: 28,
	imagelarge: 50,
	scrollsize: 64,
	currentwidth: 0,
	COLORMES: null,

	isSmall: false,
	userOffset: 0,
	usersCollection: {},
	isUpdated: false,

	init: function() {
		colorme.COLORMES = [];
		colorme.imagesize = colorme.imagelarge;

		colorme.screeningId = $("#screening_id").html()

		this.attachPoints();
		this.attachEvents();
		colorme.attachTip();
		if (colorme.user != 0) {
			colorme.attachColorMeIcons();
		}

		colorme.shortBox();
		colorme.user = $("#userid").html();

		colorme.currentwidth = ($(window).width() - 440);
		$(".userblock").width(colorme.currentwidth);
		colorme.updatePagination();

		// colorme.getCurrentUsers();
		// colorme.getCurrentUsers(true);
	},
	attachPoints: function() {
		colorme.nextButton = $("#user_wrap_right");
		colorme.previousButton = $("#user_wrap_left");
		colorme.theaterIconContainer = $("#theater_icons");
		colorme.inboxContainer = $('#inbox ');
	},
	attachEvents: function() {

		colorme.nextButton.bind('click', colorme.nextClick);
		colorme.previousButton.bind('click', colorme.previousClick);

		$(window).bind('resize', colorme.resize);

	},
	attachColorMeIcons: function() {
		$(".color_icon").bind('click', colorme.onColorMeClick);
	},
	onColorMeClick: function(event, e) {
		colorme.icon = $(this).attr("class").replace("color_icon ", "");
		//if (colorme.status != colorme.icon)
		colorme.animateIcon(colorme.user, colorme.icon);
		colorme.status = colorme.icon;
		var args = {
			body: "colorme:" + colorme.icon
		};
		$.postJSON("/services/chat/post", args, colorme.finishPost);
	},
	attachTip: function() {
		colorme.toolTip = $('<div class="tooltip theater-user-tip theater-user-tip-footer"></div>');
		$('#footer').append(colorme.toolTip);

		$('#footer .colorme_user').live('mouseover', colorme.onUserMouseover);

	},
	onUserMouseover: function() {
		if (colorme.toolTipTarget) {
			colorme.toolTipTarget.unbind('mouseleave');
			colorme.toolTip.clearQueue();
		}

		colorme.toolTipTarget = $(this);
		var position = colorme.toolTipTarget.parents('.theater_icon').position();
		colorme.toolTipTarget.bind('mouseleave', colorme.onUserMouseleave);

		colorme.toolTip.stop().css({
			'top': position.top - 60,
			'left': position.left + 10 + parseInt(colorme.theaterIconContainer.css('left')),
			'display': 'block'
		}).animate({
			opacity: 1
		}, 100).html(colorme.toolTipTarget.attr('alt'));
	},
	onUserMouseleave: function() {
		colorme.toolTip.animate({
			opacity: 0
		}, 100, function() {
			colorme.toolTip.css({
				'display': 'none'
			})
		});
	},

	getCurrentUsers: function(init) {
		if (colorme.getCurrentUserTimer) {
			window.clearTimeout(colorme.getCurrentUserTimer);
		}

		var records, offset

		if (colorme.isSmall) {
			offset = colorme.userOffset * 2;
			records = Math.ceil(colorme.currentwidth / 16);
			if (records % 2 != 0) records++;
		} else {
			offset = colorme.userOffset;
			records = Math.ceil(colorme.currentwidth / 64);
		}


		$.ajax({
			url: '/services/Screenings/colorme?screening=' + colorme.screeningId + '&records=' + records + '&offset=' + offset,
			type: "GET",
			cache: false,
			dataType: "json",
			timeout: 3000,
			success: colorme.onGetCurrentUsersSuccess,
			error: function() {
				colorme.isUpdated = true;
			}
		});
		colorme.getCurrentUserTimer = window.setTimeout(colorme.getCurrentUsers, 10000);

	},
	onGetCurrentUsersSuccess: function(response) {

		if ((typeof response == 'object') && response.users) {

			if(response.totalresults != colorme.totalresults ){
				colorme.usersCollection = {};
				colorme.theaterIconContainer.empty();
			} 

			colorme.totalresults = response.totalresults;
			colorme.isSmall = response.totalresults > 50;

			var isOdd = true;
			var tempIds = [];
			for (var i = 0; i < response.users.length; i++) {
				// if ($("#user_wrap_" + response.users[i].userid).length == 0) {
				if(!colorme.usersCollection['user_' + response.users[i].userid]){
					var cssOptions = {
						opacity: 0
					};
					if (colorme.isSmall) {
						cssOptions.top = isOdd ? 0 : 32;
						cssOptions.left = isOdd ? (i + (colorme.userOffset * 2)) * 16 : (i - 1 + (colorme.userOffset * 2)) * 16;
					} else {
						cssOptions.top = 0;
						cssOptions.left = (i + colorme.userOffset) * 64;
					}

					var userDomnode = $('<div class="theater_icon" id="user_wrap_' + response.users[i].userid + '"><a href="/profile/' + response.users[i].userid + '" target="_blank"><img class="colorme_user" id="user_image_' + response.users[i].userid + '" src="' + response.users[i].image + '" alt="' + response.users[i].username + '" width="' + colorme.imagesize + '" /></a></div>').css(cssOptions).appendTo(colorme.theaterIconContainer);
					userDomnode.animate({
						opacity: 1
					}, 300);
					colorme.usersCollection['user_' + response.users[i].userid] = userDomnode;

					isOdd = !isOdd;
				}
				tempIds.push('user_' + response.users[i].userid);
			}


			for (var i in colorme.usersCollection) {
				if (_.indexOf(tempIds, i) == -1) {
					colorme.usersCollection[i].remove();
					delete colorme.usersCollection[i];
				}
			}


			if (colorme.isSmall) {
				var width = colorme.totalresults / 2 * (colorme.imagesize + 4);
				colorme.theaterIconContainer.css('width', width + 'px');
			} else {
				var width = colorme.totalresults * (colorme.imagesize + 14);
				colorme.theaterIconContainer.css('width', width + 'px');
			}

			colorme.boxSwitch();
			// colorme.resize();
			colorme.drawColorMes();
			$(".userblock").fadeIn(100);

			colorme.updatePagination();

		} else if ((typeof response == 'object') && response.users == null) {
			colorme.reset();
			colorme.getCurrentUsers();
		}
		colorme.isUpdated = true;

	},

	nextClick: function() {
		if (colorme.isUpdated) {
			colorme.isUpdated = false;
			colorme.userOffset++;
			colorme.animateIconContainer();
		}
	},
	previousClick: function() {
		if (colorme.userOffset != 0 && colorme.isUpdated) {
			colorme.isUpdated = false;
			colorme.userOffset--;
			colorme.animateIconContainer();
		}
	},
	animateIconContainer: function() {
		colorme.theaterIconContainer.animate({
			left: -1 * (colorme.userOffset * (colorme.isSmall ? 32 : 64))
		}, 100, function() {
			colorme.getCurrentUsers();
			// colorme.updatePagination();
		});
	},
	updatePagination: function() {
		if (colorme.userOffset == 0) {
			colorme.previousButton.fadeOut();
		} else {
			colorme.previousButton.fadeIn();
		}

		var maxOffset = Math.floor(colorme.currentwidth / (colorme.isSmall ? 16 : 64)) + (colorme.userOffset * (colorme.isSmall ? 2 : 1));

		if ((maxOffset >= colorme.totalresults) || Math.floor(colorme.currentwidth / (colorme.isSmall ? 16 : 64)) > colorme.totalresults) {
			colorme.nextButton.fadeOut();
		} else {
			colorme.nextButton.fadeIn();
		}
	},

	initColors: function(users) {
		if (users != undefined) {
			usobj = users.split(",");
			for (i = 0; i < usobj.length; i++) {
				user = usobj[i].split("|");
				if ((user[0] != undefined) && (user[1] != undefined)) {
					colorme.COLORMES[user[0]] = user[1].replace("colorme:", "");
				}
			}
			colorme.drawColorMes();
		}
	},

	drawColorMes: function() {
		for (key in colorme.COLORMES) {
			if (key != '') {
				colorme.setColor(key, colorme.COLORMES[key]);
			}
		}
	},

	animateIcon: function(user, icon) {
		if (colorme.animating == false) {
			colorme.animating = true;

			$("#color_node_" + user).remove();
			nclass = colorme.setColor(user, icon);
			colorme.COLORMES[user] = icon;

			var imageNode = $('<img id="color_node_' + user + '" class="' + nclass + '" src="/images/alt1/' + icon + '_icon.png" />')
				.appendTo($("#user_wrap_" + user, colorme.theaterIconContainer))

			imageNode.animate({
				opacity: 0,
				top: '-=50'
			}, 1500, function() {
				imageNode.remove();
			});
			colorme.animating = false;
		}
	},

	setColor: function(user, icon) {

		$("#user_wrap_" + user, colorme.theaterIconContainer).removeClass('happy sad wow none heart quest').addClass(icon);

		$(".chat_icon_user_" + user, colorme.inboxContainer).removeClass('happy_med sad_med wow_med none_med heart_med quest_med').addClass(icon + '_med');

		var nclass = "color_node";
		if (colorme.imagesize == colorme.imagesmall) {
			nclass = "color_node_small";
		}
		return nclass;

	},

	resize: function() {
		if (colorme.getCurrentUserTimer) {
			window.clearTimeout(colorme.getCurrentUserTimer);
		}
		if (!colorme.previousButton) {
			colorme.attachPoints()
		}

		colorme.currentwidth = ($(window).width() - 440);
		$(".userblock").width(colorme.currentwidth);
		colorme.updatePagination();

		colorme.getCurrentUserTimer = window.setTimeout(colorme.getCurrentUsers, 200);

	},


	//Decides when to switch from LARGE to SMALL icons
	boxSwitch: function() {
		if (colorme.totalresults > 50 && colorme.imagesize != colorme.imagesmall) {
			colorme.tallBox();
		} else if (colorme.totalresults < 50 && colorme.imagesize == colorme.imagesmall) {
			colorme.shortBox();
		}
	},

	//Make the chat icons small
	tallBox: function() {

		if (!colorme.theaterIconContainer) colorme.attachPoints()


		colorme.theaterIconContainer.css("left", "0px").removeClass('large-icons').addClass('small-icons');
		colorme.imagesize = colorme.imagesmall;
		colorme.scrollsize = colorme.imagesmall + 4;

		colorme.reset();
		colorme.getCurrentUsers();

	},

	//Make the chat icons big
	shortBox: function() {

		if (!colorme.theaterIconContainer) colorme.attachPoints()

		colorme.theaterIconContainer.css({
			"top": "0px",
			"left": "0px"
		}).removeClass('small-icons').addClass('large-icons');

		colorme.imagesize = colorme.imagelarge;
		colorme.scrollsize = colorme.imagelarge + 14;
		colorme.reset();

		colorme.getCurrentUsers();
		// colorme.resize();
	},
	reset: function() {
		colorme.userOffset = 0;
		colorme.usersCollection = {};
		colorme.theaterIconContainer.empty();
	},
/*
setChatSize: function() {


	$(".footer").css("height", "122px");
	$(".bottomblock").css("height", "122px");
	$(".colorblock").css("top", "70px");
	$(".userblock").css("height", "68px");

},
*/

	finishPost: function(response) {
		//console.log("Finished");
	},

	incomingMessage: function(message) {
		regexp = new RegExp("colorme:(.+)\</p\>");
		iconObj = regexp.exec(message.html);
		colorme.animateIcon(message.author, iconObj[1]);
	}

}

$(document).ready(function() {
	if (!window.console) window.console = {};
	if (!window.console.log) window.console.log = function() {};

	colorme.init();

});