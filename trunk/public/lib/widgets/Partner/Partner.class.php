<?php
  
class Partner_PageWidget extends Widget_PageWidget {
	
	var $XMLForm;
	var $crud;
	
    function __construct( $wvars, $pvars, $context ) {
	    $this -> widget_vars = $wvars;
	    $this -> page_vars = $pvars;
	    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
	    parent::__construct( $context );
    }

	function parse() {
	  
		if (! $this -> getVar("film_id") || ! $this -> getVar("partner")) {
	    	$this -> failure();
		}
			  
		$programObj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Partner/query/Program_list_datamap.xml");
		$filmObj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Partner/query/Film_list_datamap.xml");

	    if (empty($programObj['data']) || empty($filmObj['data'])) {
	    	$this -> failure();
		}

		$this -> setgetVar("op",$this -> getVar("film_id"));
		$FilmList = new FilmList_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context );
		$FilmList -> parse();
		
		$this -> setgetVar("program_id", $programObj["data"][0]["program_id"]);
		$FilmCarousel = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Partner/query/film_related_list_datamap.xml");

		$featuredFilms = array();

		if($FilmCarousel["data"]){
			foreach ($FilmCarousel["data"] as $key => $value) {
				if($value['filmId'] !=  $this -> getVar("film_id"))
					$featuredFilms[] = $value;
			}
		}
		
   		$etoken = new TheaterAkamaiEtoken( $this -> context, null );
	    $etoken -> streamType = "film";
	    $etoken -> generateViewUrl( $filmObj, "", 84600 );

		$film = array(
			'posterUrl' => "http://".sfConfig::get("app_domain")."/uploads/screeningResources/".$filmObj["data"][0]["film_id"]."/logo/small_poster".$filmObj["data"][0]["film_logo"],
			'splashUrl' => "http://".sfConfig::get("app_domain")."/uploads/screeningResources/".$filmObj["data"][0]["film_id"]."/background/".$filmObj["data"][0]["film_splash_image"],
			'title' => $filmObj["data"][0]["film_name"],
			'id' => $filmObj["data"][0]["film_id"],
			'synopsis' => $filmObj["data"][0]["film_info"],
			'allowHostByRequest' => $filmObj["data"][0]["film_allow_hostbyrequest"] == '1' ? true: false,
			'streamUrl' => $etoken -> viewingUrl,
			'runtime' => $filmObj["data"][0]["film_running_time"],
			'directors' => $filmObj["data"][0]["film_directors"],
			'genre' => $filmObj["data"][0]["film_genre"]
		);

		$json = array(
			'meta' => array(
				'success' => true,
			),
			'response' =>array(
				'partner' => array(
					'partnerLogoUrl' => $programObj["data"][0]["program_logo"],
					'partnerUrl' => $programObj["data"][0]["program_url"],
					'partnerName' => $programObj["data"][0]["program_name"],
					'partnerShortName' => $programObj["data"][0]["program_short_name"],
				),
				'featuredShowtimes' => $FilmList -> widget_vars["carousel"],
				'dailyShowtimes' => $FilmList -> widget_vars["daily"],
				'featuredFilms' => $featuredFilms,
				'film' => $film,
			)
		);

		header('content-type: application/json; charset=utf-8');		
		print ($this -> getVar("callback") . "(" . json_encode($json). ");");
		die();
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
    return $this -> drawPage();
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

  function failure(){
		$json = array(
			'meta' => array(
				'success' => false,
			)
		);
		header('content-type: application/json; charset=utf-8');		
		print ($this -> getVar("callback") . "(" . json_encode($json). ");");
		die();
  }

}
