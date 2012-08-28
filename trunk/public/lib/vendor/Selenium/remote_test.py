#!/usr/bin/env python

from selenium import webdriver 
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities

browser = webdriver.Remote(
				   command_executor='http://localhost:4444/wd/hub/',
				   desired_capabilities=DesiredCapabilities.FIREFOX)
#browser = webdriver.Remote( browser_name="firefox", platform="ANY" )
   
#browser = webdriver.Firefox() # Get local session of firefox
#browser.get("http://www.yahoo.com") # Load page
#assert "Yahoo!" in browser.title
#elem = browser.find_element_by_name("p") # Find the query box
#elem.send_keys("seleniumhq" + Keys.RETURN)
#time.sleep(0.2) # Let the page load, will be added to the API
#try:
#    browser.find_element_by_xpath("//a[contains(@href,'http://seleniumhq.org')]")
#except NoSuchElementException:
#    assert 0, "can't find seleniumhq"
#browser.close()
