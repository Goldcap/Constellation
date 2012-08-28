browserMob.quickStop();
browserMob.stopExpected();

var selenium = browserMob.openBrowser();
var c = browserMob.getActiveHttpClient();

var screening = "nANSQb7SH3pVZ";
var has_screening = true;
//This is two hours, 60 * 60 * 2 * 1000
var total_time = 7200000;

function postChat( browserMob, selenium ) {
		browserMob.log("Chatting Now");
		//try {
			browserMob.beginStep("Screening View Chat");
			var time = new Date().getTime();
			selenium.type("id=message", "Chat Message " + time);
			selenium.click("id=chat-submit");
			browserMob.endStep();
			//Chat somewhere between 4 and 34 seconds
			var randomnumber=(Math.floor(Math.random()*31) * 1000) + 4000;
			total_time = total_time - randomnumber;
			browserMob.log("Pausing Chat for : " + randomnumber / 1000 + " Seconds");
			selenium.pause(randomnumber);
			return total_time;
		//} catch (e) {
		//}
}

function colorMe( browserMob, selenium, type ) {
		browserMob.log("Coloring "+ type + " Now");
		try {
			browserMob.beginStep("Screening View Color");
			selenium.click("css=span.color_icon."+type);
			browserMob.endStep();
			//Chat somewhere between 4 and 34 seconds
			var randomnumber=(Math.floor(Math.random()*31) * 1000) + 4000;
			total_time = total_time - randomnumber;
			browserMob.log("Pausing Chat for : " + randomnumber / 1000 + " Seconds");
			selenium.pause(randomnumber);
		} catch (e) {
		}
}


c.blacklistRequests("http(s)?://www\\.google-analytics\\.com/.*", 200);
c.blacklistRequests("http://.*\\.quantserve.com/.*", 200);
c.blacklistRequests("http://www\\.quantcast.com/.*", 200);
c.blacklistRequests("http://c\\.compete\\.com/.*", 200);
c.blacklistRequests("http(s)?://s?b\\.scorecardresearch\\.com/.*", 200);
c.blacklistRequests("http://s\\.stats\\.wordpress\\.com/.*", 200);
c.blacklistRequests("http://partner\\.googleadservices\\.com/.*", 200);
c.blacklistRequests("http://ad\\.adtegrity\\.net/.*", 200);
c.blacklistRequests("http://ad\\.doubleclick\\.net/.*", 200);
c.blacklistRequests("http(s)?://connect\\.facebook\\.net/.*", 200);
c.blacklistRequests("http://platform\\.twitter\\.com/.*", 200);
c.blacklistRequests("http://.*\\.addthis\\.com/.*", 200);
c.blacklistRequests("http://widgets\\.digg\\.com/.*", 200);
c.blacklistRequests("http://www\\.google\\.com/buzz/.*", 200);

//Max Action time is 40 seconds
var timeout = 40000;
selenium.setTimeout(timeout);

var tx = browserMob.beginTransaction();
selenium.open("http://test.constellation.tv/film/56");
	
if (!selenium.isElementPresent("link=Log In")) {
	browserMob.log("User Already Present");
} else {
	browserMob.log("Signing Up");
  selenium.pause(3000);
	var step = browserMob.beginStep("Sign Up");
	var time = new Date().getTime();
	var user = "user-" + browserMob.getUserNum() + "-" + time;
	var email = "test-" + browserMob.getUserNum() + "-" + time + "@example.com";
	var pass =  "testpass_" + browserMob.getUserNum();
	selenium.click("link=Log In");
	selenium.click("id=main-choose-signup");
	selenium.type("id=main-signup-name", user);
	selenium.type("id=main-signup-username", user);
	selenium.type("id=main-signup-email", email);
	selenium.type("id=main-signup-password", pass);
	selenium.type("id=main-signup-password", pass);
	selenium.type("id=main-signup-password2", pass);
	selenium.type("id=main-signup-password2", pass);
	selenium.click("//form[@id='sign-up_form']/div[8]/input");
	selenium.waitForPageToLoad(timeout);
	browserMob.endStep();
}

browserMob.beginStep("Screening Select");
selenium.pause(3000);

try {
	selenium.waitForElementPresent("css=a[title=\""+screening+"\"] > img");
} catch(e) {
	has_screening = false;
	browserMob.log("Screening Unavailable");
}
browserMob.endStep();
	
if (has_screening) {
	browserMob.log("Entering Theater");
	selenium.open("http://test.constellation.tv/theater/"+screening);
	//selenium.click("css=a[title=\""+screening+"\"] > img");
	selenium.waitForPageToLoad(timeout);
	browserMob.endStep();
	
	browserMob.beginStep("Screening Payment");
	if (!selenium.isElementPresent("css=div.prescreen_purchase > a > img")) {
		has_screening = false;
		browserMob.log("Ticket Already Purchased");
	} else {
		if (selenium.isElementPresent("id=fld-cc_first_name")) {
			browserMob.log("Purchasing Ticket");
			selenium.click("css=div.prescreen_purchase > a > img");
			selenium.type("id=fld-cc_first_name", "Test");
			selenium.type("id=fld-cc_last_name", "User");
			selenium.type("id=fld-cc_city", "New York");
			selenium.select("id=fld-cc_state", "label=New York");
			selenium.click("css=img.purchase_next");
			selenium.type("id=fld-cc_number", "4646808641270978");
			selenium.type("id=fld-cc_security_number", "123");
			selenium.select("id=fld-cc_exp_month", "label=09");
			selenium.select("id=fld-cc_exp_year", "label=2016");
			selenium.type("id=fld-cc_address1", "1 Main Street");
			selenium.type("id=fld-cc_address2", "Apt 3");
			selenium.type("id=fld-cc_zip", "11222");
			selenium.click("id=purchase_submit");
			try {
				selenium.waitForVisible("id=screening_click_link");
				selenium.click("id=screening_click_link");
				selenium.waitForPageToLoad(timeout);
			} catch (e) {
				//has_screening = false;
				//browserMob.log("Purchase Failed");
			}
			browserMob.endStep();
		} else {
			has_screening = false;
			browserMob.log("Payment Unvailable");
		}
	}
	
	if (has_screening) {
    		selenium.pause(3000);
		while (total_time > 0) {
			total_time = postChat(browserMob, selenium);
			browserMob.log("Time Remaining: " + total_time / 60000 + " Minutes");
		}
		//colorMe(browserMob, selenium, "happy");
		
	}
	
	//browserMob.beginStep("Screening Show Hide Chat");
	//selenium.click("css=#chat_panel > h4 > img");
	//selenium.click("link=Show Chat");
	//browserMob.endStep();
}

browserMob.endTransaction();
