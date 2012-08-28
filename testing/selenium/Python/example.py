#!/usr/bin/env python
#

from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.common.keys import Keys
import time
import selenium.webdriver.firefox.webdriver as fwb

binary = fwb.FirefoxBinary(firefox_path='/usr/bin/firefox')
profile_dir = '/home/selenium/.mozilla/firefox/profile.default'
profile = fwb.FirefoxProfile(profile_directory=profile_dir)
browser = fwb.WebDriver(firefox_profile=profile, firefox_binary=binary)

#browser = webdriver.Firefox(firefox_profile=profile, firefox_binary=binary) # Get local session of firefox
browser.get("http://www.yahoo.com") # Load page
assert "Yahoo!" in browser.title
elem = browser.find_element_by_name("p") # Find the query box
elem.send_keys("seleniumhq" + Keys.RETURN)
time.sleep(0.2) # Let the page load, will be added to the API
try:
    browser.find_element_by_xpath("//a[contains(@href,'http://seleniumhq.org')]")
except NoSuchElementException:
    assert 0, "can't find seleniumhq"
browser.close()
