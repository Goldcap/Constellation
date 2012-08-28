<?php

class testVisitorFlowAlreadyPurchased extends ConstellationTestBase
{

    public function testVisitorAlreadyPurchased()
    {

        //Use host login as the visitor creds can't get passed preuser
        $this->preLogin(HOST_LOGIN, HOST_PASSWORD);
        $this->Login(VISITOR_LOGIN, VISITOR_PASSWORD);
        $this->waitForElementPresent("id=your-screenings");
        $this->click("id=your-screenings");
		
        if ($this->isElementPresent("css=span.screening a")) {
          $this->click("css=span.screening a");
          
		  //Test entered theater, whether through intermediary lobby (if seat alrady taken) or not
		  $this->checkAndHandleLobby();
          
		  //Countdown clock if applicable
		  $this->checkTheaterCountdown();
		 
		  //Test chat
		  $this->checkTheaterChat(VISITOR_LOGIN, 'Visitor Chat'); 

		  if ($this->checkFilmInProgress()) {
			//Test SWF Loads
			$this->checkSWFLoads();
		  }
		  
		  //Leave Theater
		  //$this->leaveTheater();
 	    }         
    }
    

}


?>

