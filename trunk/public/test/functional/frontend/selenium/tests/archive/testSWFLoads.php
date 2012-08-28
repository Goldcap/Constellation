<?php

class testSWFLoads extends ConstellationTestBase
{

    public function testMovieStarts()
    {

        //Use host login as the visitor creds can't get passed preuser
        $this->preLogin(HOST_LOGIN, HOST_PASSWORD);
        $this->Login(HOST_LOGIN, HOST_PASSWORD);
        $this->waitForElementPresent("id=your-screenings");
        $this->click("id=your-screenings");
		
        if ($this->isElementPresent($element_id = "css=div#your-hosting-ajax-load.screening-payload div.row span:nth-child(3) a")) {
          $this->click($element_id);
          $this->checkAndHandleLobby();
		  $this->checkSWFLoads();
		 
		 //Use this to leave theater
         //$this->leaveTheater();
        } 

       
    
    }
    
}


?>

	