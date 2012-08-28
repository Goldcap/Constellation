<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FilmAdmin_crud.php';
  
   class FilmAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new FilmCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
  
   if ($this -> as_cli) {
      //http://developers.facebook.com/tools/debug/og/object?q=http%3A%2F%2Fwww.constellation.tv%2Ffilm%2Fquality
      $base = "http://developers.facebook.com/tools/debug/og/object?q=http%3A%2F%2Fwww.constellation.tv%2Ffilm%2F";
      $c = new Criteria();
      $films = FilmPeer::doSelect( $c );
      foreach ($films as $film) {
        cli_text("Clearing Film ".$film->getFilmName(),"green");
      	$curl = new Curl();
      	cli_text("Clearing ".$base.$film->getFilmId(),"cyan");
        $curl->get($base.$film->getFilmId());
        $curl->get($base.urlencode($film->getFilmShortName()));
      }
      $programs = ProgramPeer::doSelect( $c );
      foreach ($programs as $program) {
        cli_text("Clearing ".$program->getProgramName(),"green");
      	$curl = new Curl();
      	cli_text("Clearing ".$base.$program->getProgramShortName(),"cyan");
        $curl->get($base.urlencode($program->getProgramShortName()));
      }
      die();
   }
	 if ($this -> as_service) {
	    if ($this -> getVar("id") == "promo") {
	      $this -> crud = new FilmPromotionCrud( $this -> context );
        $vals = array("fk_film_id"=>$this->postVar("film_id"),"fk_promotion_id"=>$this->postVar("promo_id"));
        $this -> crud -> checkUnique($vals);
        $this -> crud -> setFkFilmId($this->postVar("film_id"));
        $this -> crud -> setFkPromotionId($this->postVar("promo_id"));
        $this -> crud -> setFilmPromotionPriority($this->postVar("priority"));
        $this -> crud -> save();
        $this -> setGetVar("id",$this->postVar("film_id"));
        $result = $this -> returnList("film_promotion_list_datamap.xml");
        print($result["form"]);
        die();
      } elseif ($this -> getVar("id") == "add") {
				$ffilm = new FilmFilmCrud( $this -> context );
				$vars = array("fk_film_id"=>$this -> getVar("key"),"fk_film_child_id"=>str_replace("film_","",$this -> getVar("kid")),"film_film_level"=>1);
				$ffilm -> checkUnique($vars);
				$ffilm -> setFkFilmId($this -> getVar("key"));
				$ffilm -> setFkFilmChildId(str_replace("film_","",$this -> getVar("kid")));
				$ffilm -> setFilmFilmLevel(1);
				$ffilm -> save();
				die();
			} elseif ($this -> getVar("id") == "remove") {
        $ffilm = new FilmFilmCrud( $this -> context );
				$vars = array("fk_film_id"=>$this -> getVar("key"),"fk_film_child_id"=>str_replace("keyword_","",$this -> getVar("kid")),"film_film_level"=>1);
				$ffilm -> checkUnique($vars);
				$ffilm -> remove();
				die();
			}else { 
        $sql = "select film_id, film_name from film where film_name like ? or film_name like ?";
        $term = "%".$this -> getVar("term")."%";
        $rs = $this -> propelArgs($sql,array($term,$term));
        while ($row = $rs -> fetch()) {
          $ar["id"] = $row[0];
          $ar["value"] = $row[1];
          $arr[] = $ar;
        }
        print json_encode($arr);
        die();
      }
    }
    
	 //return $this -> widget_vars;
   
	 if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
   }
   $this -> doGet();
   
   $form = $this -> drawPage();
      
   $this -> widget_vars["form"] = $form["form"];
   
   $this -> SearchForm = new XMLForm($this -> widget_name, "searchconf.php");
   $this -> SearchForm -> validated= false;
   $searchform = $this -> SearchForm -> drawForm();
   $this -> widget_vars["search_form"] = $searchform["form"];
   
   return $this -> widget_vars;
    
  }

  function doPost(){
     //$this -> XMLForm -> validate_debug = 2;
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          
          $sql = "delete from film_info where fk_film_id=".$this -> getVar("id");
          $this -> propelQuery($sql);
          
          $this -> addInfo("director",1);
          $this -> addInfo("producer",2);
          $this -> addInfo("actor",3);
          $this -> addInfo("writer",4);
          $this -> addInfo("executive_producers",5);
          $this -> addInfo("director_of_photography",6);
          $this -> addInfo("music",7);
          $this -> addInfo("co-producers",8);
          $this -> addInfo("co-executive_producers",9);
          $this -> addInfo("associate_producers",10);
          $this -> addInfo("supported",11);
          $this -> addInfo("link",12);
          
          if ($this -> postVar("film_review") == '') {
            $this -> crud -> setFilmReview('');
          }

          if (! $this -> postVar("film_featured")) {
            $this -> crud -> setFilmFeatured(0);
          }
          
          if (! $this -> postVar("film_free_screening")) {
            $this -> crud -> setFilmFreeScreening(0);
          }
          
          if ($this -> postVar("film_twitter_tags") == "") {
            $this -> crud -> setFilmTwitterTags("");
          }
          
          if (! $this -> postVar("film_use_sponsor_codes")) {
            $this -> crud -> setFilmUseSponsorCodes(0);
          }
          
          if (! $this -> postVar("film_allow_hostbyrequest")) {
            $this -> crud -> setFilmAllowHostbyrequest(0);
          }
          
          if (! $this -> postVar("film_allow_user_hosting")) {
            $this -> crud -> setFilmAllowUserHosting(0);
          }
          
          if ($this -> postVar("film_alternate_template") == 0) {
            $this -> crud -> setFilmAlternateTemplate(0);
          }
          
          if (! $this -> postVar("film_preuser")) {
            $this -> crud -> setFilmPreuser(0);
          }
          
          if (! $this -> postVar("film_share")) {
            $this -> crud -> setFilmShare(0);
          }
          
          if (! $this -> postVar("film_freewithinvite")) {
            $this -> crud -> setFilmFreewithinvite(0);
          }
          
          if ($this -> postVar("film_show_title") == 0) {
            $vars["film_show_title"] = 0;
          } else {
            $vars["film_show_title"] = 1;
          }
          
          if ($this -> postVar("film_trailer_file") == '') {
            $this -> crud -> setFilmTrailerFile(null);
          }
          if ($this -> postVar("film_youtube_trailer") == '') {
            $this -> crud -> setFilmYoutubeTrailer(null);
          }          
          
          if ($this -> postVar("film_text_color")) {
            $vars["film_text_color"] = $this -> postVar("film_text_color");
          } else {
            $vars["film_text_color"] = '';
          }
          $this -> crud -> setFilmAlternateOpts(serialize($vars));
          
          if ((! $this -> postVar("film_bitrate_minimum")) || ($this -> postVar("film_bitrate_minimum") == "")) {
            $this -> crud -> setFilmBitrateMinimum("");
          }
          if ((! $this -> postVar("film_bitrate_low")) || ($this -> postVar("film_bitrate_low") == "")) {
            $this -> crud -> setFilmBitrateLow("");
          }
          if ((! $this -> postVar("film_bitrate_small")) || ($this -> postVar("film_bitrate_small") == "")) {
            $this -> crud -> setFilmBitrateSmall("");
          }
          if ((! $this -> postVar("film_bitrate_medium")) || ($this -> postVar("film_bitrate_medium") == "")) {
            $this -> crud -> setFilmBitrateMedium("");
          }
          if ((! $this -> postVar("film_bitrate_large")) || ($this -> postVar("film_bitrate_large") == "")) {
            $this -> crud -> setFilmBitrateLarge("");
          }
          if ((! $this -> postVar("film_bitrate_largest")) || ($this -> postVar("film_bitrate_largest") == "")) {
            $this -> crud -> setFilmBitrateLargest("");
          }
          $this -> crud -> write();
      
      	  $this -> crud -> Film -> setFilmGeoblockingType( $this -> postVar("film_geoblocking_type") );
      	  $this -> crud -> Film -> save();
          
          $sql = "delete from film_genre where fk_film_id = " . $this->crud->Film->getFilmId();
          $this -> propelQuery($sql);
          if ($this -> postVar("film_genre")) {
            foreach($this -> postVar("film_genre") as $genre) {
              //$vars = array("fk_film_id"=>$this->crud->Film->getFilmId(),"fk_genre_id"=>$genre);
              //$filmgenre -> checkUnique($vars);
              $filmgenre = new FilmGenreCrud($this -> context);
              $filmgenre -> setFkFilmId($this->crud->Film->getFilmId());
              $filmgenre -> setFkGenreId($genre);
              $filmgenre -> save();
            }
          }
          
          if ($this -> postVar("film_id") == 0) {
            
            $file = new WTVRFile();
            $id = $this -> crud -> Film -> getFilmId();
            if ($this -> postVar("FILE_film_logo_guid")) {
              $sourcr = explode("/",$this -> postVar("FILE_film_logo_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/logo",$dest."/logo");
              $this -> crud -> setFilmLogo( str_replace("screening_poster","",$filename) . ".jpg" );
            } 
						if ($this -> postVar("FILE_film_homelogo_guid")) {
              $sourcr = explode("/",$this -> postVar("FILE_film_homelogo_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/logo",$dest."/logo");
              $this -> crud -> setFilmHomelogo( str_replace("wide_poster","",$filename) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_still_image_guid")) {
            
              $sourcr = explode("/",$this -> postVar("FILE_film_still_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/still",$dest."/still");
              $this -> crud -> setFilmStillImage( $filename . ".jpg" );
              
            }
            if ($this -> postVar("FILE_film_background_image_guid")) {
            
              $sourcr = explode("/",$this -> postVar("FILE_film_background_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/background",$dest."/background");
              $this -> crud -> setFilmBackgroundImage( $filename . ".jpg" );
              
            }
            if ($this -> postVar("FILE_film_splash_image_guid")) {
            
              $sourcr = explode("/",$this -> postVar("FILE_film_splash_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/background",$dest."/background");
              $this -> crud -> setFilmSplashImage( $filename . ".jpg" );
              
            }
            if ($this -> postVar("FILE_film_trailer_file_guid")) {
              
              $sourcr = explode("/",$this -> postVar("FILE_film_trailer_file_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest."/trailerFile/");
              if (is_dir($source)) {
                  if ($dh = opendir($dir)) {
                      while (($file = readdir($dh)) !== false) {
                          if ( $file == '.' || $file == '..' )
                            {  continue; }
                          $file -> moveFile($source."/trailerFile/".$file,$dest."/trailerFile/".$file);
                      }
                      closedir($dh);
                  }
              }
              
              $tar = explode("/",$this -> postVar("FILE_film_trailer_file_guid"));
              $ttar = explode(".",$this -> postVar("FILE_film_trailer_file_filename"));
              $this -> crud -> setFilmTrailerFile( end($tar) . "." . end($ttar) );
              
            }
            if ($this -> postVar("FILE_film_movie_file_guid")) {
              
              $sourcr = explode("/",$this -> postVar("FILE_film_movie_file_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/screeningResources/".$id;
              createDirectory($dest."/trailerFile/");
              if (is_dir($source)) {
                  if ($dh = opendir($dir)) {
                      while (($file = readdir($dh)) !== false) {
                          if ( $file == '.' || $file == '..' )
                            {  continue; }
                          $file -> moveFile($source."/trailerFile/".$file,$dest."/trailerFile/".$file);
                      }
                      closedir($dh);
                  }
              }

              $mar = explode("/",$this -> postVar("FILE_film_movie_file_guid"));
              $mtar = explode(".",$this -> postVar("FILE_film_movie_file_filename"));
              $this -> crud -> setFilmMovieFile( end($mar) . "." . end($mtar) );
            
            }
            
            $this -> crud -> setFilmCreatedAt(now());
            $this -> crud -> save();
            //Move Images Where they need to go
          } else {
            if ($this -> postVar("FILE_film_logo_guid")) {
              $lar = explode("/",$this -> postVar("FILE_film_logo_guid"));
              $this -> crud -> setFilmLogo( str_replace("screening_poster","",end($lar)) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_homelogo_guid")) {
              $lar = explode("/",$this -> postVar("FILE_film_homelogo_guid"));
              $this -> crud -> setFilmHomelogo( str_replace("wide_poster","",end($lar)) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_still_image_guid")) {
               $sar = explode("/",$this -> postVar("FILE_film_still_image_guid"));
              $this -> crud -> setFilmStillImage( end($sar) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_background_image_guid")) {
               $sar = explode("/",$this -> postVar("FILE_film_background_image_guid"));
              $this -> crud -> setFilmBackgroundImage( end($sar) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_splash_image_guid")) {
               $sar = explode("/",$this -> postVar("FILE_film_splash_image_guid"));
              $this -> crud -> setFilmSplashImage( end($sar) . ".jpg" );
            }
            if ($this -> postVar("FILE_film_trailer_file_guid")) {
              $tar = explode("/",$this -> postVar("FILE_film_trailer_file_guid"));
              $ttar = explode(".",$this -> postVar("FILE_film_trailer_file_filename"));
              $this -> crud -> setFilmTrailerFile( end($tar) . "." . end($ttar) );
            }
            if ($this -> postVar("FILE_film_movie_file_guid")) {
              $mar = explode("/",$this -> postVar("FILE_film_movie_file_guid"));
              $mtar = explode(".",$this -> postVar("FILE_film_movie_file_filename"));
              $this -> crud -> setFilmMovieFile( end($mar) . "." . end($mtar) );
            }
            $this -> crud -> setFilmUpdatedAt(now());
            $this -> crud -> save();
            
          }

          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","film",$this -> crud -> Film -> getFilmId());
          
          //Update all screenings for this film, too
          //And all Audience Entries as well
          $sql = "select screening_id, screening_date, screening_time from screening where fk_film_id = ".$this -> crud -> Film -> getFilmId();
          $res = $this -> propelQuery($sql);
          while ($row = $res -> fetch()) {
          	$starttime = formatDate($row[1],"MDY-") . " " . formatDate($row[2],"time");
	          $times = explode(":",$this -> crud -> Film -> getFilmRunningTime());
	          $totaltime = ($times[0] * 3600) + ($times[1] * 60) + ($times[2]);
	          $screen = ScreeningPeer::retrieveByPk( $row[0] );
	          $screen ->setScreeningEndTime(strtotime($starttime) + $totaltime);
	          $screen -> save();
	          
            $solr = new SolrManager_PageWidget(null, null, $this -> context);
            $solr -> execute("add","screening",$row[0]);
            
		        //Update all screenings for this film, too
		        $sql = "select audience_id from audience where fk_screening_id = ".$row[0];
		        $ares = $this -> propelQuery($sql);
		        while ($arow = $ares -> fetch()) {
		          $solr = new SolrManager_PageWidget(null, null, $this -> context);
		          $solr -> execute("add","audience",$arow[0]);
		        }
		        
          }
          break;
          case "delete":
          
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("delete","film",$this -> crud -> Film -> getFilmId());
          $this -> crud -> remove();
          
          break;
        }
      }
  }

  function doGet(){
  
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
     	
     	$this -> widget_vars["master_film_id"] = $this -> crud -> getFilmId();
     	
			$vars = unserialize($this -> crud -> getFilmAlternateOpts());
      
      $this -> XMLForm -> item["film_show_title"] = $vars["film_show_title"];
      $this -> XMLForm -> item["film_text_color"] = $vars["film_text_color"];
      
      $genres = $this -> dataMap(sfConfig::get("sf_lib_dir")."/widgets/FilmAdmin/query/film_genre_list_datamap.xml");
      if (count($genres["data"]) > 0) {
        foreach ($genres["data"] as $key=>$value) {
          $g[] = $value["genre_name"];
        }
        $genres = $g;
        $this -> XMLForm -> item["film_genre[]"] = $genres;
      }
      
      $junk = $this -> XMLForm -> item["film_geoblocking_type"];
      foreach(explode(",",$junk) as $filter){
        if ($filter == ''){
          continue;
        }
        $this -> XMLForm -> item["film_geoblocking_filters"] .= '<div id="'.str_replace(array(" ","."),array("",""),$filter).'"><span class="gbfilter">'.$filter.'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''.str_replace(array(" ","."),array("",""),$filter).'\')" /></div>';
      
      }
      $this -> XMLForm -> item["film_screenings"] = $this -> returnList("Screening_list_datamap.xml");
      $this -> XMLForm -> item["film_promotions"] = $this -> returnList("film_promotion_list_datamap.xml");
      
			$this -> XMLForm -> item["related_film_screenings"] = $this -> returnList("film_related_list_datamap.xml");
      $this -> XMLForm -> item["unrelated_film_screenings"] = $this -> returnList("film_unrelated_list_datamap.xml");
			$sql = "select film_info_type, film_info, film_info_url from film_info where fk_film_id=".$this -> getVar("id");
      $res = $this -> propelQuery($sql);
      if ($res) {
        while ($row = $res -> fetch()) {
          switch ($row[0]) {
            case 1:
              $cast['director'][] = array( $row[1], $row[2]);
              break;
            case 2:
              $cast['producer'][] = array( $row[1], $row[2]);
              break;
            case 3:
              $cast['actor'][] = array( $row[1], $row[2]);
              break;
            case 4:
              $cast['writer'][] = array( $row[1], $row[2]);
              break;
            case 5:
              $cast['executive_producer'][] = array( $row[1], $row[2]);
              break;
            case 6:
              $cast['director_of_photography'][] = array( $row[1], $row[2]);
              break;
            case 7:
              $cast['music'][] = array( $row[1], $row[2]);
              break;
            case 8:
              $cast['co_producer'][] = array( $row[1], $row[2]);
              break;
            case 9:
              $cast['co_executive_producer'][] = array( $row[1], $row[2]);
              break;
            case 10:
              $cast['associate_producer'][] = array( $row[1], $row[2]);
              break;
            case 11:
              $cast['supported'][] = array( $row[1], $row[2]);
              break;
            case 12:
              $cast['link'][] = array( $row[1], $row[2]);
              break;
              
          }
        }
      }
      $this -> XMLForm -> item["film_candc"] = json_encode($cast);
      
      $html = '<div class="row"><span class="col1">Directors</span></div>';
      if (count($cast['director']) > 0) {
      foreach($cast['director'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Producers</span></div>';
      if (count($cast['producer']) > 0) {
      foreach($cast['producer'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Actors</span></div>';
      if (count($cast['actor']) > 0) {
      foreach($cast['actor'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Writers</span></div>';
      if (count($cast['writer']) > 0) {
      foreach($cast['writer'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Executive Producers</span></div>';
      if (count($cast['executive_producer']) > 0) {
      foreach($cast['executive_producer'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Director of Photography</span></div>';
      if (count($cast['director_of_photography']) > 0) {
      foreach($cast['director_of_photography'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Music</span></div>';
      if (count($cast['music']) > 0) {
      foreach($cast['music'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Co-Producer</span></div>';
      if (count($cast['co_producer']) > 0) {
      foreach($cast['co_producer'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Co-Executive Producer</span></div>';
      if (count($cast['co_executive_producer']) > 0) {
      foreach($cast['co_executive_producer'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Associate Producer</span></div>';
      if (count($cast['associate_producer']) > 0) {
      foreach($cast['associate_producer'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      $html .= '<div class="row"><span class="col1">Supported</span></div>';
      if (count($cast['supported']) > 0) {
      foreach($cast['supported'] as $acast) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$acast[0].' (<a href="'.$acast[1].'">'.$acast[1].'</a>)</span></div>';
      }}
      if (count($cast['lnk']) > 0) {
      foreach($cast['link'] as $alink) {
      $html .= '<div class="row"><span class="col1 white">.</span><span class="col2">'.$alink[0].' (<a href="'.$alink[1].'">'.$alink[1].'</a>)</span></div>';
      }}
      $this -> XMLForm -> item["film_cast_list"] = $html;
       
    }
    //dump($this -> XMLForm -> item);
  }

  function drawPage(){
    if ($this -> getVar("op") == "search") {
      return $this -> filmSearch();
    } elseif (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      //$this -> showXML();
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }
  
  function filmSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
  
    $util = new FilmAdmin_format_utility( $this -> context );
    
    if (! $query) {
      
      if (($this -> greedyVar("film_start_date")) && (! $this -> greedyVar("film_end_date"))) {
        $start = formatDate($this -> greedyVar("film_start_date"),"W3XMLIN");
        //$end = formatDate($this -> postVar("cms_object_end_date"),"W3XML");
        $util -> date = "[ ". $start ." TO * ]"; 
      }
      if ((! $this -> greedyVar("film_start_date")) && ($this -> greedyVar("film_end_date"))) {
        //$start = formatDate($this -> postVar("cms_object_start_date"),"W3XML");
        $end = formatDate($this -> greedyVar("film_end_date"),"W3XMLIN");
        $util -> date = "[ * TO ". $end." ]"; 
      }
      if (($this -> greedyVar("film_start_date")) && ($this -> greedyVar("film_end_date"))) {
        $start = formatDate($this -> greedyVar("film_start_date"),"W3XMLIN");
        $end = formatDate($this -> greedytVar("film_end_date"),"W3XMLIN");
        $util -> date = "[ ". $start ." TO ". $end." ]"; 
      }
      if (($this -> greedyVar("film_term"))) {
        $term = $this -> greedyVar("film_term");
        $util -> term = $this -> greedyVar("film_term");
      }
      
      $search = ($term != "") ? $term : "[ * TO * ]";
      
      if (is_numeric($term) && ($term < 1000000)) {
        $num = $term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$term);
      sfConfig::set("num",$num);
      
    }
    
    
    return $this -> returnList( "FilmSearch_list_datamap.xml", true, true, "standard", $util );
    
  }
  
  function addInfo($type,$tid) {
    if ($this -> postVar("film_".$type."_name")) {
      $i=0;
      $urls = $this -> postVar("film_".$type."_url");
      $items = $this->postVar("film_".$type."_name");
      foreach($items as $item) {
        if ($item == "")
          continue;
        $crud = new FilmInfoCrud( $this -> context );
        $crud -> setFkFilmId( $this -> getVar("id") );
        $crud -> setFilmInfoType( $tid );
        $crud -> setFilmInfo( $item );
        $crud -> setFilmInfoUrl( $urls[$i] );
        $crud -> setFilmInfoUpdatedAt( now() );
        $crud -> setFilmInfoCreatedAt( now() );
        $crud -> save();
        $i++;
      }
    }
  }
  
}
?>
