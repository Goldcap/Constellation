<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ProgramAdmin_crud.php';
  
   class ProgramAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new ProgramCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 	if ($this -> as_service) {
	    if ($this -> getVar("id") == "add") {
				$ffilm = new ProgramFilmCrud( $this -> context );
				$vars = array("fk_film_id"=>$this -> getVar("key"),"fk_film_child_id"=>str_replace("film_","",$this -> getVar("kid")),"program_film_level"=>1);
				$ffilm -> checkUnique($vars);
				$ffilm -> setFkFilmId($this -> getVar("key"));
				$ffilm -> setFkFilmChildId(str_replace("film_","",$this -> getVar("kid")));
				$ffilm -> setProgramFilmLevel(1);
				$ffilm -> save();
				die();
			} elseif ($this -> getVar("id") == "remove") {
        $ffilm = new ProgramFilmCrud( $this -> context );
				$vars = array("fk_film_id"=>$this -> getVar("key"),"fk_film_child_id"=>str_replace("film_","",$this -> getVar("kid")),"program_film_level"=>1);
				$ffilm -> checkUnique($vars);
				$ffilm -> remove();
				die();
			}
    }
    
	 //return $this -> widget_vars;
    $c = new Criteria();
    $films = FilmPeer::doSelect( $c );
    foreach ($films as $film) {
    	$filma["sel_key"] = $film -> getFilmId();
    	$filma["sel_value"] = $film -> getFilmName();
    	$filmarray[] = $filma;
    }
    $this -> XMLForm -> registerArray("films",$filmarray);
    
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
          
          if (! $this -> postVar("program_featured")) {
            $this -> crud -> setProgramFeatured(0);
          }
          
          if (! $this -> postVar("program_use_sponsor_codes")) {
            $this -> crud -> setProgramUseSponsorCodes(0);
          }
          
          if (! $this -> postVar("program_allow_hostbyrequest")) {
            $this -> crud -> setProgramAllowHostbyrequest(0);
          }
          
          if (! $this -> postVar("program_allow_user_hosting")) {
            $this -> crud -> setProgramAllowUserHosting(0);
          }
          
          if ($this -> postVar("program_alternate_template") == 0) {
            $this -> crud -> setProgramAlternateTemplate(0);
          }
          
          if (! $this -> postVar("program_preuser")) {
            $this -> crud -> setProgramPreuser(0);
          }
          
          if (! $this -> postVar("program_share")) {
            $this -> crud -> setProgramShare(0);
          }
          
          if ($this -> postVar("program_show_title") == 0) {
            $vars["program_show_title"] = 0;
          } else {
            $vars["program_show_title"] = 1;
          }
          
          if ($this -> postVar("program_text_color")) {
            $vars["program_text_color"] = $this -> postVar("program_text_color");
          } else {
            $vars["program_text_color"] = '';
          }
          $this -> crud -> setProgramAlternateOpts(serialize($vars));
          
          $this -> crud -> write();
          
          $sql = "delete from program_genre where fk_program_id = " . $this->crud->Program->getProgramId();
          $this -> propelQuery($sql);
          if ($this -> postVar("program_genre")) {
            foreach($this -> postVar("program_genre") as $genre) {
              //$vars = array("fk_film_id"=>$this->crud->Film->getFilmId(),"fk_genre_id"=>$genre);
              //$filmgenre -> checkUnique($vars);
              $filmgenre = new ProgramGenreCrud($this -> context);
              $filmgenre -> setFkProgramId($this->crud->Program->getProgramId());
              $filmgenre -> setFkGenreId($genre);
              $filmgenre -> save();
            }
          }
          
          if ($this -> postVar("program_id") == 0) {
            
            $file = new WTVRFile();
            $id = $this -> crud -> Program -> getProgramId();
            if ($this -> postVar("FILE_program_logo_guid")) {
              $sourcr = explode("/",$this -> postVar("FILE_program_logo_guid"));
              //dump($sourcr);
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/programResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/programResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/logo",$dest."/logo");
              $this -> crud -> setProgramLogo( str_replace("screening_poster","",$filename) . ".jpg" );
            }
            if ($this -> postVar("FILE_program_still_image_guid")) {
            
              $sourcr = explode("/",$this -> postVar("FILE_program_still_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/programResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/programResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/still",$dest."/still");
              $this -> crud -> setProgramStillImage( $filename . ".jpg" );
              
            }
            if ($this -> postVar("FILE_program_background_image_guid")) {
            
              $sourcr = explode("/",$this -> postVar("FILE_program_background_image_guid"));
              $filename = array_pop($sourcr);
              array_pop($sourcr);
              $sourcer = implode("/",$sourcr);
              $source = sfConfig::get("sf_web_dir")."/uploads/programResources/".$sourcer;
              $dest = sfConfig::get("sf_web_dir")."/uploads/programResources/".$id;
              createDirectory($dest);
              $dest = $dest;
              $file -> moveFile($source."/background",$dest."/background");
              $this -> crud -> setProgramBackgroundImage( $filename . ".jpg" );
              
            }
            $this -> crud -> setProgramCreatedAt(now());
            $this -> crud -> save();
            //Move Images Where they need to go
          } else {
            if ($this -> postVar("FILE_program_logo_guid")) {
              $lar = explode("/",$this -> postVar("FILE_program_logo_guid"));
              $this -> crud -> setProgramLogo( str_replace("screening_poster","",end($lar)) . ".jpg" );
            }
            if ($this -> postVar("FILE_program_still_image_guid")) {
               $sar = explode("/",$this -> postVar("FILE_program_still_image_guid"));
              $this -> crud -> setProgramStillImage( end($sar) . ".jpg" );
            }
            if ($this -> postVar("FILE_program_background_image_guid")) {
               $sar = explode("/",$this -> postVar("FILE_program_background_image_guid"));
              $this -> crud -> setProgramBackgroundImage( end($sar) . ".jpg" );
            }
            $this -> crud -> setProgramUpdatedAt(now());
            $this -> crud -> save();
            
          }
          
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("add","program",$this -> crud -> Program -> getProgramId());
          
          break;
          case "delete":
          
          //Update the SOLR Search Engine
          $solr = new SolrManager_PageWidget(null, null, $this -> context);
          $solr -> execute("delete","program",$this -> crud -> Program -> getProgramId());
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
   
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
      
      $vars = unserialize($this -> crud -> getProgramAlternateOpts());
      
      $this -> XMLForm -> item["program_show_title"] = $vars["program_show_title"];
      $this -> XMLForm -> item["program_text_color"] = $vars["program_text_color"];
      
      $this -> XMLForm -> item["related_film_screenings"] = $this -> returnList("film_related_list_datamap.xml");
      $this -> XMLForm -> item["unrelated_film_screenings"] = $this -> returnList("film_unrelated_list_datamap.xml");
			
      $genres = $this -> dataMap(sfConfig::get("sf_lib_dir")."/widgets/ProgramAdmin/query/program_genre_list_datamap.xml");
      if (count($genres["data"]) > 0) {
        foreach ($genres["data"] as $key=>$value) {
          $g[] = $value["genre_name"];
        }
        $genres = $g;
        $this -> XMLForm -> item["program_genre[]"] = $genres;
      }
      
      $junk = $this -> XMLForm -> item["program_geoblocking_type"];
      foreach(explode(",",$junk) as $filter){
        if ($filter == ''){
          continue;
        }
        $this -> XMLForm -> item["program_geoblocking_filters"] .= '<div id="'.str_replace(".","",$filter).'"><span class="gbfilter">'.$filter.'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''.str_replace(".","",$filter).'\')" /></div>';
      
      }
      
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>
