<?php

/**
 * _widget components.
 *
 * @package    nokiasource
 * @subpackage _widget
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultComponents extends sfComponents
{
  
  
	/**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeAccountFilm()
  {
  }
  
	/**
	/**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeHeadsUpDisplay()
  {
  }
  
	/**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeLoginopts()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeLoginAlt()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeLoginAltFree()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeSignup()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeScreeninglist()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeScreeninglistAlt()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeHomescreenings()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeTimezone()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeTimezoneAlt()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeCurrenttime()
  {
  }
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeFilminfo()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeCarousel()
  {
  }

   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeCarouselAlt()
  {
  }
   
    /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeMarqueeCarousel()
  {
  }
  
    /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeShortCarousel()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeFilmBlock()
  {
  }
  
	/**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeAccountScreening()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeHistory()
  {
  }
   
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeGrowler()
  {
  } 
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeGrowlerAlt()
  {
  } 
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeFeedback()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeMailChimp()
  {
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeAlternateTemplate1()
  {
    
    $d = new WTVRData(null,null,null);
    $startdate = time();
    
    $times = explode(":",$this -> film["film_running_time"]);
    $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
    $enddate = strtotime($_GET["date"]." 00:00:00") + 86400 + $totaltime;
  
    //sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO ".formatDate($enddate,"W3XMLIN")."]");
    sfConfig::set("startdate","[".formatDate($startdate,"W3XMLIN")." TO * ]");
    sfConfig::set("film_id",$this->film["film_id"]);
    //dump(sfConfig::get("startdate"));
    //$this -> showData();
    $list = $d ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningSponsor_list_datamap.xml");
    $this -> screening = $list["data"][0];
    
    //dump($this -> screening);
    $this -> thistime = date("Y|m|d|H|i|s");
    $this -> counttime = date("Y|m|d|H|i|s",strtotime($this->screening["screening_date"]));
    
    if( ! is_null($this -> screening["screening_id"])) {
      $sql = "select count(audience_id) 
              from audience 
              inner join payment
              on fk_payment_id = payment_id
              where audience.fk_screening_id = ".$this -> screening["screening_id"]." 
              and payment.payment_status = 2";
      $res = $d -> propelQuery($sql);
      while ($row = $res -> fetch()) {
        $a_seatcount = $row[0];
      }
      $this -> seatcount = $this -> screening["screening_total_seats"] - $a_seatcount;
    }//dump($this -> counttime);
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeAlternateTemplate2()
  {
    
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeHost()
  {
    
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeHostFilmAlt()
  {
    
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executePurchase()
  {
    
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executePurchaseFilmAlt()
  {
    
  }
  
  /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executePurchaseFilmSponsorAlt()
  {
    
  }
  
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executePurchaseAlt()
  {
    
  }
  
	/**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executePurchaseSponsorAlt()
  {
    
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeWBR()
  {
    
  }
  
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeWBRFilmAlt()
  {
    
  }
  
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeGEOIP()
  {
    
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeTheaterInvites()
  {
    
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeInvites()
  {
    
  }
  
	/**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeInviteAlt()
  {
  }
  
   /**
  * Executes promotions action
  *
  * @param sfRequest $request A request object
  */
  public function executeAllfilms()
  {
    $d = new WTVRData(null,null,null);
    $sql = "select distinct(film_name),
							(select count(screening_id) from screening
							where fk_film_id = film_id
							and screening.screening_date >= now()) as count
              from film
							order by count desc
							limit 21";
      $res = $d -> propelQuery($sql);
      while ($row = $res -> fetch()) {
        $film["name"] = $row[0];
        $film["count"] = $row[1];
        $films[] = $film;
      }
      $this -> films = $films;
  }


  public function executeCheckoutMain()
  {
    
  }
  public function executeCheckoutInvite()
  {
    
  }
  public function executeCheckoutBilling()
  {
    
  }
  public function executeCheckoutPayment()
  {
    
  }
  public function executeCheckoutConfirmation()
  {
    
  }
  public function executeUploadFilmDetails()
  {
    
  }
  public function executeUploadSettings()
  {
    
  }
  public function executeUploadScreenings()
  {
    
  }
  public function executeUploadFile()
  {
    
  }
  public function executeTheaterContent()
  {
    
  }
  public function executeTheaterQa()
  {
    
  } 
  public function executeTheaterCredit()
  {
    
  }  
  public function executeTheaterChat()
  {
    
  }  
  public function executeTheaterColor()
  {
    
  }
  public function executeTheaterFooter()
  {
    
  }   
}
