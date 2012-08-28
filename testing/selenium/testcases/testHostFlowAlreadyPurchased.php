<?php

class testHostFlowAlreadyPurchased extends ConstellationTestBase
{

    public function testHostAlreadyPurchased()
    {

        //Use host login as the visitor creds can't get passed preuser
        $this->preLogin(HOST_LOGIN, HOST_PASSWORD);
        $this->Login(HOST_LOGIN, HOST_PASSWORD);
        $this->waitForElementPresent("id=your-screenings");
        $this->click("id=your-screenings");
		
        if ($this->isElementPresent($element_id = "css=div#your-hosting-ajax-load.screening-payload div.row span:nth-child(3) a")) {
          $this->click($element_id);
		  
          //Test entered theater, whether through intermediary lobby (if seat alrady taken) or not
		  $this->checkAndHandleLobby();
		  
		  //Countdown clock if applicable
		  $this->checkTheaterCountdown();
		  
		  //Test chat
		  $this->checkTheaterChat(HOST_LOGIN, 'Hi, I am the host'); 
		  
		  //Check watching now count = 1 since you're hosting and first in
		  $this->checkWatchingNowCountEquals('1');
       
		  if ($this->checkFilmInProgress()) {
			//Test SWF Loads
			$this->checkSWFLoads();
		  }
		  
          //Use this to leave theater
          //$this->leaveTheater();
       }
    }
    
}


?>

	