<?php

  include_once(sfConfig::get('sf_lib_dir')."/helper/SolrHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  //Data Abstraction classes as needed from propel
  //require_once 'crud/SolrManager_crud.php'
  
  /*
  Widget vars as follows:
  [0] = action [add|delete|update]
  [1] = type [artist|cms_object_keyword|cmsobject|product|user]
  [2]= $other_opts
  To add a single item
  php symfony widget exec SolrManager admin dev add,product,5
  To add a range (currently unimplemented)
  php symfony widget exec SolrManager admin dev add,product,5-6
  */
  
  class SolrManager_PageWidget extends Widget_PageWidget {
	
  var $documents;
  var $doc_id;
  var $doc_query;
  
  var $action;
  var $object_type;
  var $object_id;
  
  //Use this for ranking, to generate a list of ID's
  //From the rank query
  var $ext_ids;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> util = new SolrManager_format_utility( $context );
    parent::__construct( $context );
    
  }
  
  //Convenience function for external calls
  function execute( $action, $type, $id ) {
    $this -> action = $action;
    $this -> object_type = $type;
    $this -> object_id = $id ;
    $this -> parse();
  }
  
	function parse() {
	
    if ($this -> as_cli) {
      
      if ($this ->widget_vars["args"][0] == "help") {
       
       cli_text( "promotion (add,delete) \$id| (int)", "green" ) ;
       cli_text( " -- Add user by id", "cyan") ;
       
       cli_text( "user (add,delete) \$id| (int)", "green" ) ;
       cli_text( " -- Add user by id", "cyan") ;
       
       cli_text( "users (add,delete) \"\$id|\$id\" (int|int)", "green" ) ;
       cli_text( " -- Add users by id", "cyan") ;
       
       cli_text( "useraddress (add,delete) \$id (int)", "green" ) ;
       cli_text( " -- Add useraddress by id", "cyan") ;
       
       cli_text( "action (add,delete) \$id (int)", "green" ) ;
       cli_text( " -- Add action by id", "cyan") ;
       
       cli_text( "film (add,delete) \$id (int)", "green" ) ;
       cli_text( " -- Add film by id", "cyan") ;
       
       cli_text( "program (add,delete) \$id (int)", "green" ) ;
       cli_text( " -- Add program by id", "cyan") ;
       
       cli_text( "utility", "green" ) ;
       cli_text( " -- Runs the utility function", "cyan") ;
       
       cli_text( "query (add,delete) \$query (string)", "green" ) ;
       cli_text( " -- Runs a specific query", "cyan") ;
       
       cli_text( "wipe \$query (string)", "green" ) ;
       cli_text( " -- Clears Films, Users, and Screenings", "cyan") ;
       
       die();
      }
      if ($this ->widget_vars["args"][0] == "utility") {
        $this -> utilityRun();
      }
      if ($this ->widget_vars["args"][0] == "query") {
        $this -> queryRun();
      }
      if ($this ->widget_vars["args"][0] == "wipe") {
        $this -> wipe();
      }
      
      $this -> action = $this ->widget_vars["args"][0];
      $this -> object_type = $this->widget_vars["args"][1];
      $this -> object_id = $this ->widget_vars["args"][2];
    }
    
    if (! $this -> action) {
      return null;
    }
    
    switch ($this -> action) {
      case "commit":
        solrCommit();
        break;
      case "optimize":
        solrOptimize();
        break;
      case "add":
        $this -> addDocument();
        break;
      case "delete":
        $this -> removeDocument();
        solrCommit();
        break;
      case "update":
        $this -> addDocument();
        break;
      case "clear":
        $this -> clearDocuments();
        solrCommit();
        break;
    }
    
  }
  
  function addDocument() {
    $this -> createDocuments();
    postDocuments( $this -> documents );
  }
  
  function removeDocument() {
    $this -> identifyDocuments();
    deleteDocument( $this -> doc_id );
  }
  
  function clearDocuments() {
    switch($this -> object_type) {
      
      case "user":
        
        $this -> doc_query = "object_type: user";
        break;
      case "film":
        
        $this -> doc_query = "object_type: film";
        break;
      case "program":
        
        $this -> doc_query = "object_type: program";
        break;
      case "screening":
        
        $this -> doc_query = "object_type: screening";
        break;
      case "upcoming":
        
        $this -> doc_query = "object_type: upcoming";
        break;
      case "action":
        
        $this -> doc_query = "object_type: action";
        break;
      case "promotion":
        
        $this -> doc_query = "object_type: promotion";
        break;
      case "audience":
        
        $this -> doc_query = "object_type: audience";
        break;

      case "payment":
        
        $this -> doc_query = "object_type: payment";
        break;
      default:
        break;
    }
    deleteQuery( $this -> doc_query );
  }
  
  function identifyDocuments() {
    
    switch($this -> object_type) {
      
      case "user":
        
        $this -> doc_id = "user".$this -> object_id;
        break;
      
      case "film":
        
        $this -> doc_id = "film".$this -> object_id;
        break;
      
     case "program":
        
        $this -> doc_id = "program".$this -> object_id;
        break;
      
      case "screening":
        
        $this -> doc_id = "screening".$this -> object_id;
        break;
      
      case "upcoming":
        
        $this -> doc_id = "upcoming".$this -> object_id;
        break;
      
      case "action":
        
        $this -> doc_id = "action".$this -> object_id;
        break;
        
      case "promotion":
        
        $this -> doc_id = "promotion".$this -> object_id;
        break;
        
      case "audience":
        
        $this -> doc_id = "audience".$this -> object_id;
        break;
        
      case "payment":
        
        $this -> doc_id = "payment".$this -> object_id;
        break;
      
      default:
        break;
    }
  }
  
  function createDocuments() {
    //$this -> showData();
    switch($this -> object_type) {
      
      case "user":
        
        sfConfig::set("user_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/User_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            if ($part["user_ual"] != "") {
              $ual = unserialize($part["user_ual"]);
              try {
                if (count($ual) > 0) {
                  foreach($ual as $auth) {
                    $part["user_access_level"][] = getUAL($auth);
                  }
                }
              } catch ( Exception $e ) {
              }
            }
            unset($part["user_ual"]);
            $this -> documents["item"] = $part;
            
           }
        }
        //dump($this->documents);
        break;
      
      case "users":
        
        sfConfig::set("user_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Users_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            if ($part["user_ual"] != "") {
              $ual = unserialize($part["user_ual"]);
              if (count($ual) > 0) {
                foreach($ual as $auth) {
                  $part["user_access_level"][] = getUAL($auth);
                }
              }
            }
            unset($part["user_ual"]);
            $this -> documents[]=$part;
            
           }
           
        }
        
        break;
      
      case "action":
        
        sfConfig::set("action_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/User_Action_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            unset($part["user_default_timezone"]);
            $this -> documents["item"] = $part;
          }
        }
        
        break;
      
      case "film":
        
        sfConfig::set("film_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Film_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            $this -> documents["item"] = $part;
            
            sfConfig::set("film_id",$part["film_id"]);
            $reviewinfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Review_list_datamap.xml");
            if ($reviewinfo["meta"]["totalresults"] > 0) {
              foreach ($reviewinfo["data"] as $review) {
                $this -> documents["item"]["film_user_reviews"][] = $review["audience_review"]."|".$review["audience_review_user_full_name"]."|".$review["audience_review_user_username"];
              }
            }
            
            $filmgenre = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmGenre_list_datamap.xml",true,"array",$this -> util);
            if ($filmgenre["meta"]["totalresults"] > 0) {
              foreach ($filmgenre["data"] as $genre) {
                $this -> documents["item"]["film_genre"][]=$genre["genre_name"];
              }
            }
            
            $filminfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmInfo_list_datamap.xml",true,"array",$this -> util);
            if ($filminfo["meta"]["totalresults"] > 0) {
              foreach ($filminfo["data"] as $info) {
                switch ($info["film_info_type"]) {
                  case 1:
                    $this -> documents["item"]["film_directors"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 2:
                    $this -> documents["item"]["film_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 3:
                    $this -> documents["item"]["film_actors"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 4:
                    $this -> documents["item"]["film_writers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 5:
                    $this -> documents["item"]["film_executive_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 6:
                    $this -> documents["item"]["film_director_of_photography"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 7:
                    $this -> documents["item"]["film_music"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 8:
                    $this -> documents["item"]["film_co-producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 9:
                    $this -> documents["item"]["film_co-executive_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 10:
                    $this -> documents["item"]["film_associate_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 11:
                    $this -> documents["item"]["film_supported"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 12:
                    $this -> documents["item"]["film_link"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                }
              }
            }
          }
        }
        
        break;
      
      case "films":
        
        sfConfig::set("film_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Films_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            sfConfig::set("film_id",$part["film_id"]);
            
            $filmgenre = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmGenre_list_datamap.xml",true,"array",$this -> util);
            if ($filmgenre["meta"]["totalresults"] > 0) {
              foreach ($filmgenre["data"] as $genre) {
                $part["film_genre"][]=$genre["genre_name"];
              }
            }
            
            
            sfConfig::set("film_id",$part["film_id"]);
            $reviewinfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Review_list_datamap.xml");
            if ($reviewinfo["meta"]["totalresults"] > 0) {
              foreach ($reviewinfo["data"] as $review) {
                $part["film_user_reviews"][] = $review["audience_review"]."|".$review["audience_review_user_full_name"]."|".$review["audience_review_user_username"];
              }
            }
            
            $filminfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmInfo_list_datamap.xml",true,"array",$this -> util);
            
            if ($filminfo["meta"]["totalresults"] > 0) {
              foreach ($filminfo["data"] as $info) {
                switch ($info["film_info_type"]) {
                  case 1:
                    $part["film_directors"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 2:
                    $part["film_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 3:
                    $part["film_actors"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 4:
                    $part["film_writers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 5:
                    $part["film_executive_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 6:
                    $part["film_director_of_photography"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 7:
                    $part["film_music"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 8:
                    $part["film_co-producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 9:
                    $part["film_co-executive_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 10:
                    $part["film_associate_producers"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 11:
                    $part["film_supported"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                  case 12:
                    $part["film_link"][]=$info["film_info_url"]."|".$info["film_info"];
                    break;
                }
              }
            }
            $filminfo = null;
            $this -> documents[]=$part;
            
           }
        }
        break;
      
      case "program":
        
        sfConfig::set("program_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Program_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            $this -> documents["item"] = $part;
            
            $programgenre = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/ProgramGenre_list_datamap.xml",true,"array",$this -> util);
            if ($programgenre["meta"]["totalresults"] > 0) {
              foreach ($programgenre["data"] as $genre) {
                $this -> documents["item"]["program_genre"][]=$genre["genre_name"];
              }
            }
          }
        }
        break;
      
      case "programs":
        
        sfConfig::set("program_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Programs_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            sfConfig::set("program_id",$part["program_id"]);
            
            $programgenre = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/ProgramGenre_list_datamap.xml",true,"array",$this -> util);
            if ($programgenre["meta"]["totalresults"] > 0) {
              foreach ($programgenre["data"] as $genre) {
                $part["program_genre"][]=$genre["genre_name"];
              }
            }
            
            $this -> documents[]=$part;
            
           }
        }
        break;
        
      case "screening":
      case "upcoming":
        //$this -> showData();
        sfConfig::set("screening_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Screening_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            sfConfig::set("film_id",$part["screening_film_id"]);
            $reviewinfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Review_list_datamap.xml",true,"array",$this -> util);
            if ($reviewinfo["meta"]["totalresults"] > 0) {
              foreach ($reviewinfo["data"] as $review) {
                $part["screening_film_user_reviews"][] = $review["audience_review"]."|".$review["audience_review_user_full_name"]."|".$review["audience_review_user_username"];
              }
            }
            
            $part["screening_search"] = strtolower($part["screening_film_name"]) . " ";
            $part["screening_search"] .= strtolower($part["screening_name"]) . " ";     
            $part["screening_search"] .= strtolower($part["screening_user_fname"]) . " "; 
            $part["screening_search"] .= strtolower($part["screening_user_lname"]) . " "; 
            $part["screening_search"] .= strtolower($part["screening_film_makers"]) . " ";
						
						sfConfig::set("film_id",$part["screening_film_id"]);
            $filmgenre = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmGenre_list_datamap.xml",true,"array",$this -> util);
            if ($filmgenre["meta"]["totalresults"] > 0) {
              foreach ($filmgenre["data"] as $genre) {
                $part["screening_film_genre"][]=$genre["genre_name"];
              	$part["screening_search"] .= strtolower(implode(" ",$part["screening_film_genre"])) . " ";
							}
            }

            $screeninginfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmInfo_list_datamap.xml",true,"array",$this -> util);
            //Note the film id is set above...
						//$screeninginfo=$this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmInfo_list_datamap.xml",true,"array",$this -> util);
            
            if ($screeninginfo["meta"]["totalresults"] > 0) {
            	foreach ($screeninginfo["data"] as $user) {
                $part["screening_search"] .= strtolower($user["film_info"]) . " ";
              }
            }
            
            if ($part["screening_user_ual"] != "") {
              $ual = unserialize($part["screening_user_ual"]);
              if (count($ual) > 0) {
                foreach($ual as $auth) {
                  $part["screening_user_access_level"][] = getUAL($auth);
                }
              }
            }
            $tz = $part["screening_default_timezone_id"];
            $date = $part["screening_date"];
            $time = $part["screening_time"];
            $newdate = (date("Y-m-d",strtotime($part["screening_date"]))." ".date("H:i:s",strtotime($part["screening_time"])));
	          $part["screening_date"] = $part["screening_time"] = formatDate($newdate,"W3XMLIN",$tz);
            $part["screening_end_time"] = formatDate($part["screening_end_time"],"W3XMLIN",$tz);
            unset($part["screening_user_ual"]);
            $this -> documents["item"] = $part;
          }
        }
        if ($this -> object_type == "upcoming") {
          foreach($this -> documents["item"] as $key => $value) {
            $newitem[str_replace("screening","upcoming",$key)]=str_replace("screening","upcoming",$value);
          }
          $sql = "select count(audience_id) from audience where fk_screening_id = ".$this -> documents["item"]["screening_id"]." and audience_paid_status = 2";
          $res = $this -> propelQuery($sql);
          $val = $res -> fetchall();
          $newitem["upcoming_audience_count"] = $val[0][0];
          if ((strtotime($part["screening_date"])) < (strtotime( now()))) {
        	 	$newitem["upcoming_in_progress"] = true;
        	} else {
        		$newitem["upcoming_in_progress"] = false;
        	}
          $this -> documents["item"] = $newitem;
          //dump($this -> documents["item"]);
        }
				//dump($this -> documents["item"]);
        break;
      
      case "screenings":
      case "upcomings":  
        sfConfig::set("screening_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Screenings_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            sfConfig::set("film_id",$part["screening_film_id"]);
            $reviewinfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Review_list_datamap.xml",true,"array",$this -> util);
            if ($reviewinfo["meta"]["totalresults"] > 0) {
              foreach ($reviewinfo["data"] as $review) {
                $part["screening_film_user_reviews"][] = $review["audience_review"]."|".$review["audience_review_user_full_name"]."|".$review["audience_review_user_username"];
              }
            }
            
						$part["screening_search"] = strtolower($part["screening_film_name"]) . " ";
            $part["screening_search"] .= strtolower($part["screening_name"]) . " ";     
            $part["screening_search"] .= strtolower($part["screening_user_fname"]) . " "; 
            $part["screening_search"] .= strtolower($part["screening_user_lname"]) . " "; 
            $part["screening_search"] .= strtolower($part["screening_film_makers"]) . " ";
						
            sfConfig::set("film_id",$part["screening_film_id"]);
            $filmgenre = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmGenre_list_datamap.xml",true,"array",$this -> util);
            if ($filmgenre["meta"]["totalresults"] > 0) {
              foreach ($filmgenre["data"] as $genre) {
                $part["screening_film_genre"][]=$genre["genre_name"];
              	$part["screening_search"] .= strtolower(implode(" ",$part["screening_film_genre"])) . " ";
							}
            }
            
            //Note the film id is set above...
						$screeninginfo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/FilmInfo_list_datamap.xml",true,"array",$this -> util);
            
            if ($screeninginfo["meta"]["totalresults"] > 0) {
            	foreach ($screeninginfo["data"] as $user) {
                $part["screening_search"] .= strtolower($user["film_info"]) . " ";
              }
            }
            
            if ($part["screening_user_ual"] != "") {
              $ual = unserialize($part["screening_user_ual"]);
              if (count($ual) > 0) {
                foreach($ual as $auth) {
                  $part["screening_user_access_level"][] = getUAL($auth);
                }
              }
            }
            $tz = $part["screening_default_timezone_id"];
            $newdate = (date("Y-m-d",strtotime($part["screening_date"]))." ".date("H:i:s",strtotime($part["screening_time"])));
            $part["screening_date"] = $part["screening_time"] = formatDate($newdate,"W3XMLIN",$tz);
            $part["screening_end_time"] = formatDate($part["screening_end_time"],"W3XMLIN",$tz);
            unset($part["screening_user_ual"]);
            if ($this -> object_type == "upcomings") {
              foreach($part as $key => $value) {
                $newitem[str_replace("screening","upcoming",$key)]=str_replace("screening","upcoming",$value);
              }
              $sql = "select count(audience_id) from audience where fk_screening_id = ".$part["screening_id"]." and audience_paid_status = 2";
              $res = $this -> propelQuery($sql);
              $val = $res -> fetchall();
              $newitem["upcoming_audience_count"] = $val[0][0];
              if ((strtotime($part["screening_date"])) < (strtotime( now()))) {
            	 	$newitem["upcoming_in_progress"] = true;
            	} else {
            		$newitem["upcoming_in_progress"] = false;
            	}
              $part = $newitem;
              //dump($this -> documents["item"]);
            }
            $this -> documents[]=$part;
            
           }
          //dump($this -> documents);
        }
        break;
        
        case "promotion":
        
        sfConfig::set("promotion_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Promo_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            $this -> documents["item"] = $part;
            
           }
        }
        //dump($this->documents);
        break;
      
      case "promotions":
        
        sfConfig::set("user_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Promos_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
          
            $this -> documents[]=$part;
            
           }
           
        }
        
        break;
      
      case "audience":
        
        //$this -> showData();
				sfConfig::set("audience_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Audience_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            unset($part["user_default_timezone"]);
            
            $c = new Criteria();
            $c->add(UserPeer::USER_ID,$part["audience_screening_user_id"]);
            $user = UserPeer::doSelect($c);
            
            if ($user) {
              $part["audience_screening_user_full_name"] = $user[0] -> getUserFullName();
              $part["audience_screening_user_username"] = $user[0] -> getUserUsername();
              $part["audience_screening_user_photo_url"] = $user[0] -> getUserPhotoUrl();
              $part["audience_screening_user_image"] = $user[0] -> getUserImage();
            }
            
            $newdate = (date("Y-m-d",strtotime($part["audience_screening_date"]))." ".date("H:i:s",strtotime($part["audience_screening_time"])));
            $tz = $part["screening_default_timezone_id"];
            unset($part["screening_default_timezone_id"]);
            unset($part["audience_screening_time"]);
            
            $part["audience_screening_date"] = formatDate($newdate,"W3XMLIN",$tz);
            $part["audience_screening_end_time"] = formatDate($part["audience_screening_end_time"],"W3XMLIN",$tz);
            
            $this -> documents["item"] = $part;
           }
        }
        //dump($this -> documents);
        break;
      
      case "audiences":
        
        sfConfig::set("audience_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Audiences_list_datamap.xml",true,"array",$this -> util);

        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            unset($part["user_default_timezone"]);
            
            $c = new Criteria();
            $c->add(UserPeer::USER_ID,$part["audience_screening_user_id"]);
            $user = UserPeer::doSelect($c);
            
            if ($user){
              $part["audience_screening_user_full_name"] = $user[0] -> getUserFullName();
              $part["audience_screening_user_username"] = $user[0] -> getUserUsername();
              $part["audience_screening_user_photo_url"] = $user[0] -> getUserPhotoUrl();
              $part["audience_screening_user_image"] = $user[0] -> getUserImage();
            }
            $newdate = (date("Y-m-d",strtotime($part["audience_screening_date"]))." ".date("H:i:s",strtotime($part["audience_screening_time"])));
            $tz = $part["audience_user_timezone_id"];
            unset($part["audience_user_timezone_id"]);
            unset($part["audience_screening_time"]);
            
            $part["audience_screening_date"] = formatDate($newdate,"W3XMLIN",$tz);
            $part["audience_screening_end_time"] = formatDate($part["audience_screening_end_time"],"W3XMLIN",$tz);
            
            $this -> documents[] = $part;
            
           }
        }
        
        //dump($this->documents);
        break;
        
      case "payment":
        
        sfConfig::set("payment_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Payment_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            unset($part["user_default_timezone"]);
            $this -> documents["item"] = $part;
            
           }
        }
        //dump($this->documents);
        break;
      
      case "payments":
        
        sfConfig::set("payment_id",$this -> object_id);
        $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Payments_list_datamap.xml",true,"array",$this -> util);
        
        if ($list["meta"]["totalresults"] > 0) {
          foreach ($list["data"] as $part) {
            
            unset($part["user_default_timezone"]);
            $this -> documents[] = $part;
            
           }
        }
        //dump($this->documents);
        break;
        
      default:
        break;
    }
  }
  
  function wipe() {
    //dump($query);
    cli_text("Removing Screenings ".formatDate(null,"pretty"),"blue","white");
    deleteQuery("screening_id: [ * TO * ]");
    //cli_text("Removing Films ".formatDate(null,"pretty"),"blue","white");
    //deleteQuery("film_id: [ * TO * ]");
    //cli_text("Removing Users ".formatDate(null,"pretty"),"blue","white");
    //deleteQuery("user_id: [ * TO * ]");
    //cli_text("Removing Audience ".formatDate(null,"pretty"),"blue","white");
    //deleteQuery("audience_id: [ * TO * ]");
    
    cli_text("Ending parse at ".formatDate(null,"pretty"),"blue","white");
  }
  
  function utilityRun() {
    
    $sql = "select payment_id,
            user_id,
            user_email,
            payment_email,
            payment.payment_order_processor
            from  payment
            inner join `user`
            on user.user_id = payment.fk_user_id
            where user_email is null
            and payment_status = 2;";
    $rs = $this -> propelQuery($sql);
    cli_text("Starting parse at ".formatDate(null,"pretty"),"blue","white");
    
    while ($row = $rs->fetch()) {
      $user = UserPeer::retrieveByPk($row[1]);
      $user -> setUserEmail($row[3]);
      $user -> save();
      cli_text("Adding '".$row[1]."' to SOLR as a User","red","white");
      $this -> action = "add";
      $this -> object_type = "user";
      $this -> object_id = $row[2];
      $this -> addDocument();
      //$sql = "update user_order set user_order_syslog = 2 where user_order_id = ".$rs -> get(1).";";
      //$this -> propelQuery($sql);
    }
    cli_text("Ending parse at ".formatDate(null,"pretty"),"blue","white");
    
  }
  
  //query,delete,"screening_id: [ * TO * ]"
  function queryRun() {
    $action = $this ->widget_vars["args"][1];
    $query = $this ->widget_vars["args"][2];
    //dump($query);
    switch ($action) {
      case "delete":
        deleteQuery( $query );
        break;
      case "run":
        runQuery( $query );
        break;
    }
    cli_text("Ending parse at ".formatDate(null,"pretty"),"blue","white");
    
  }
  
  function scagImages() {
    
    //https://s3.amazonaws.com/static.constellation.tv/uploads/screeningResources/1/logo/4bc462ba3d241.jpg
    $baseURL = "https://s3.amazonaws.com/static.constellation.tv/uploads/hosts";  
    $dir = "/var/www/html/sites/dev.constellation.tv/public/web/uploads/hosts";
    cli_text("Grabbing All Film Images".formatDate(null,"pretty"),"blue","white");
    sfConfig::set("user_id","'1|1700'");
    $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/SolrManager/query/Users_datamap.xml");
    $curl = new Curl;
    
    foreach ($list["data"] as $user) {
        $folder="";
        $specdir = $dir."/".$user["user_id"]."/";
        $file="".$user["user_image"];
        $specfile = $specdir.$file;
        if ($user["user_image"] == '') {
          continue;
        }
        cli_text("Grabbing film ".$user["user_id"]." assets at ".formatDate(null,"pretty"),"blue","white");
        $res = $curl->get( $baseURL."/".$user["user_id"]."/".$file )->body;
        //File is a thumbnail error, so continue
        if (preg_match("/<Error>/",$res)) {
          cli_text("ERROR:: No File Available","red","white");
          continue;
        }
        //File is a hires error, so continue
        if (preg_match("/http/",$res)) {
          cli_text("ERROR:: No File Available","red","white");
          continue;
        }
        cli_text("Creating directory ".$specdir,"cyan","white");
        createDirectory( $specdir );
        cli_text("Creating file ".$specfile,"cyan","white");
        createFile( $specfile, $res, true );
     }
    
    return;
  }
  
}


?>
