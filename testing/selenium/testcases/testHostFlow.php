<?php

class testHostFlow extends ConstellationTestBase
{

    //Login and Host Lottery
    public function testHost()
    {
        $this->preLogin(HOST_LOGIN, HOST_PASSWORD);
        $this->Login(HOST_LOGIN, HOST_PASSWORD);

        $this->waitForElementPresent("css=img[name='The Lottery']");
       	$this->click("css=img[name='The Lottery']");
		
        $this->waitForElementPresent("id=carousel_host_link");
		$this->customAssert('assertElementPresent', array('target'=>'id=carousel_host_link', 
															'label'=>'Asserting Clicking Film Popup Worked'));
        $this->click("id=carousel_host_link");

        $this->waitForElementPresent("id=host_date");
     
	    $this->click("id=host_date");
        $this->customAssert('assertElementPresent', array('target'=>'css=div.ui-datepicker-group table.ui-datepicker-calendar tbody tr td.ui-datepicker-days-cell-over a.ui-state-default',
															'label'=>'Asserting Date Popup Worked'));
        //Select Today's date using CSS
        $this->click("css=div.ui-datepicker-group table.ui-datepicker-calendar tbody tr td.ui-datepicker-days-cell-over a.ui-state-default");
        $this->customAssert('assertElementValueEquals', array('target'=>'id=host_date',
															  'value'=>date('m/d/Y'),
															  'label'=>'Asserting Clicking Todays Date Worked'));
															  
        $this->click("id=host_time");
 
        //Click Now Button
        //$this->click("css=button.ui-datepicker-current.ui-state-default.ui-priority-secondary.ui-corner-all");
        //Click the slider for hours - but how to slide?
        //$this->click("//dd[@id='ui_tpicker_hour_host_time']/a[1]");
		
        //Type in time for now until figure out how to drag the slider
        $current_time_plus_30 = date('h:i a', strtotime('+ 30 minutes'));
        $this->typeKeys("id=host_time", $current_time_plus_30);
	   
        //Click Done Button
        $this->click("css=button.ui-datepicker-close.ui-state-default.ui-priority-primary.ui-corner-all");
        $this->customAssert('assertElementValueEquals', array('target'=>'id=host_time',
															  'value'=>$current_time_plus_30,
															  'label'=>'Asserting Setting Time 30 Minutes In the Future Worked'));
		
		//Click Invite friends and pay
        $this->click("id=host_submit");
		$this->customAssert('assertElementPresent', array('target'=>'id=host_purchase_submit',
														  'label'=>'Asserting Payment Form Appears'));
		
        $this->type("id=host-fld-cc_city", "New York");
        $this->type("id=host-fld-cc_zip", "10024");
        $this->click("id=host_purchase_submit");
		$this->waitForElementPresent("id=host_screening_link");
		$this->customAssert('assertElementPresent', array('target'=>'id=host_screening_link',
														  'label'=>'Asserting Purchase Worked'));
        
		$this->customAssert('assertTextPresent', array('target'=>'Enter Screening',
													 'label'=>'Asserting Enter Screening Button Appears'));
    
        $this->click("css=button.btn_small");
        $this->waitForPageToLoad("30000");
        
		
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
		
		//Use this to leave theater test
        //$this->leaveTheater();
    }

}


?>

