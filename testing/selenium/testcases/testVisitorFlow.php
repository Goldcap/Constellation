<?

class testVisitorFlow extends ConstellationTestBase
{

    public function testVisitor()
    {

        //Use host login as the visitor creds can't get passed preuser
        $this->preLogin(HOST_LOGIN, HOST_PASSWORD);
        $this->Login(VISITOR_LOGIN, VISITOR_PASSWORD);

        $this->waitForElementPresent("css=img[name='The Lottery']");
       	$this->click("css=img[name='The Lottery']");
        $this->waitForElementPresent("id=carousel_film_link");
		$this->customAssert('assertElementPresent', array('target'=>'id=carousel_host_link', 
															'label'=>'Asserting Clicking Film Popup Worked'));
        $this->click("id=carousel_film_link");
		
		$this->waitForElementPresent("css=a.screening_link", 2000);
		if ($this->isElementPresent('css=a.screening_link')) {
			//If a specific screening is setup for today, join the first one
			$this->click("css=a.screening_link");
        } else {
		    //Click Watch Now
			$this->waitForElementPresent("css=a#hbr_request_button button.btn_tiny_og");
            $this->click("css=a#hbr_request_button button.btn_tiny_og");
       	}	
        
		$this->customAssert('assertElementPresent', array('target'=>'id=purchase_submit', 
															'label'=>'Asserting Payment Form Appears'));
        
		$this->type("id=host-fld-cc_city", "New York");
        $this->type("id=host-fld-cc_zip", "10024");
        
        $this->click("id=purchase_submit");
		$this->customAssert('assertElementPresent', array('target'=>'id=screening_click_link',
														  'label'=>'Asserting Purchase Worked'));
														  
        $this->customAssert('assertTextPresent', array('target'=>'Enter Theater',
													 'label'=>'Asserting Enter Theater Button Appears'));
       
        $this->click("id=screening_click_link");
        $this->waitForPageToLoad("30000");
        
		//Test entered theater, whether through intermediary lobby (if seat alrady taken) or not
        $this->checkAndHandleLobby();
	    
		//Countdown clock if applicable
		$this->checkTheaterCountdown();
		
		//Test chat
        $this->checkTheaterChat(VISITOR_LOGIN);
        
		if ($this->checkFilmInProgress()) {
			//Test SWF Loads
			$this->checkSWFLoads();
		}
		
		//Use this to leave theater test
        //$this->leaveTheater();
        
    }
    

}


?>

