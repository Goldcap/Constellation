<?php
  
  //Some interesting comments from the Drupal community!
  //First time for everything...
  //http://drupal.org/node/292662
  
  
  //http://www.ibm.com/developerworks/opensource/library/os-php-apachesolr/
  
  //require_once( '/var/www/html/sites/dev.tattoojohnny.com/public/lib/vendor/SolrPhpClient/Apache/Solr/Service.php' );
  
  /*
  $parts = array(
    'spark_plug' => array(
      'partno' => 1,
      'name' => 'Spark plug',
      'model' => array( 'Boxster', '924' ),
      'year' => array( 1999, 2000 ),
      'price' => 25.00,
      'inStock' => true,
    ),
    'windshield' => array(
      'partno' => 2,
      'name' => 'Windshield',
      'model' => '911',
      'year' => array( 1999, 2000 ),
      'price' => 15.00,
      'inStock' => false,
    )
  );
  */
  function solrCommit() {
    $solr = new Apache_Solr_Service( sfConfig::get("app_solr_ip"), sfConfig::get("app_solr_port"), sfConfig::get("app_solr_uri") );
    
    if ( ! $solr->ping( 10 ) ) {
      echo 'Solr service not responding.';
      return false;
    }
    
    $res = $solr->commit();
  }
  
  function solrOptimize() {
    $solr = new Apache_Solr_Service( sfConfig::get("app_solr_ip"), sfConfig::get("app_solr_port"), sfConfig::get("app_solr_uri") );
    if ( ! $solr->ping( 10 ) ) {
      echo 'Solr service not responding.';
      return false;
    }
    
    $solr->optimize();
  }
  
  function postDocuments( $parts ) {
    
    $solr = new Apache_Solr_Service( sfConfig::get("app_solr_ip"), sfConfig::get("app_solr_port"), sfConfig::get("app_solr_uri") );
    if ( ! $solr->ping( 10 ) ) {
      die  ('Solr service not responding.');
      return false;
    }
    
    $documents = array();
          
    if (count($parts) < 1) {
      return false;
    }
    
    foreach ( $parts as $item => $fields ) {
      $part = new Apache_Solr_Document();
      $cost = 0;
      
      foreach ( $fields as $key => $value ) {
        if (($key == "product_price") && ($value >= 1)) $cost = 1;
        if (($key == "product_price_category_price") && ($value >= 1)) $cost = 1;
        if (( is_array( $value ) ) && count($value) > 0) {
          foreach ( $value as $datum ) {
            if (strlen($datum) > 0)
            $part->setMultiValue( $key, $datum );
          }
        }
        else {
          $part->$key = $value;
        }
      }
      
      if ((isset($item["object_type"])) && ($item["object_type"] == 'product') && ($cost != 0)) {
        $documents[] = $part;
      } else {
        $documents[] = $part;
      }
        
    }
    
    if (count($documents) == 0) return false;
    
    //
    //
    // Load the documents into the index
    // 
    try {
       
	$solr->addDocuments( $documents );
      $solr->commit();
      //$solr->optimize();
    }
    catch ( Exception $e ) {
      
      $email_body .= "An SOLR POST Error has been reported by the ". $_SERVER["SERVER_NAME"] . " site ( ".$_SERVER["SERVER_ADDR"] ." ):"."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Page Request: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Error:                    ".$e->getMessage()."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "IP Address:               ".REMOTE_ADDR()."<br />";
    	$email_body .= "User Agent:               ".$_SERVER["HTTP_USER_AGENT"]."<br />";
    	$email_body .= "Time/Date:                 ".date("F j, Y, g:i a")."<br />";
    	$email_body .= "======================================================================"."<br />";
    	
    	$subject = $_SERVER["SERVER_NAME"].'( '.$_SERVER["SERVER_ADDR"] .' ) SOLR POST Error: '.$type.':'.$code;
      
      QAMail ($email_body, $subject, false);
      echo $e->getMessage();
    }
    
  }
  
  //
  // 
  // Run some queries. Provide the raw path, a starting offset
  //   for result documents, and the maximum number of result
  //   documents to return. You can also use a fourth parameter
  //   to control how results are sorted and highlighted, 
  //   among other options.
  //
  function runQuery( $query, $offset=0, $limit=10 ) {
    $solr = new Apache_Solr_Service( sfConfig::get("app_solr_ip"), sfConfig::get("app_solr_port"), sfConfig::get("app_solr_uri") );
    
    $response = $solr->search( $query, $offset, $limit );
    
    if ( $response->getHttpStatus() == 200 ) { 
      // print_r( $response->getRawResponse() );
      
      if ( $response->response->numFound > 0 ) {
        //echo "$query <br />";
        
        return $response;
        //foreach ( $response->response->docs as $doc ) { 
        //  echo "$doc->partno $doc->name <br />";
        //}
        
      }
    }
    else {
      $email_body .= "An SOLR POST Error has been reported by the ". $_SERVER["SERVER_NAME"] . " site ( ".$_SERVER["SERVER_ADDR"] ." ):"."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Page Request: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Error:                    ".$response->getHttpStatusMessage()."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "IP Address:               ".REMOTE_ADDR()."<br />";
    	$email_body .= "User Agent:               ".$_SERVER["HTTP_USER_AGENT"]."<br />";
    	$email_body .= "Time/Date:                 ".date("F j, Y, g:i a")."<br />";
    	$email_body .= "======================================================================"."<br />";
    	
    	$subject = $_SERVER["SERVER_NAME"].'( '.$_SERVER["SERVER_ADDR"] .' ) SOLR POST Error: '.$type.':'.$code;
      
      QAMail ($email_body, $subject, false);
      
      echo $response->getHttpStatusMessage();
      return false;
    }
  }
  
  //
  // 
  // Run some queries. Provide the raw path, a starting offset
  //   for result documents, and the maximum number of result
  //   documents to return. You can also use a fourth parameter
  //   to control how results are sorted and highlighted, 
  //   among other options.
  //
  function deleteDocument( $id ) {
    $solr = new Apache_Solr_Service( sfConfig::get("app_solr_ip"), sfConfig::get("app_solr_port"), sfConfig::get("app_solr_uri") );
    
    //
    //
    // Load the documents into the index
    // 
    try {
      $solr->deleteById( $id );
      $solr->commit();
      //$solr->optimize();
    }
    catch ( Exception $e ) {
      $email_body .= "An SOLR DELETE Error has been reported by the ". $_SERVER["SERVER_NAME"] . " site ( ".$_SERVER["SERVER_ADDR"] ." ):"."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Page Request: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Error:                    ".$e->getMessage()."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "IP Address:               ".REMOTE_ADDR()."<br />";
    	$email_body .= "User Agent:               ".$_SERVER["HTTP_USER_AGENT"]."<br />";
    	$email_body .= "Time/Date:                 ".date("F j, Y, g:i a")."<br />";
    	$email_body .= "======================================================================"."<br />";
    	
    	$subject = $_SERVER["SERVER_NAME"].'( '.$_SERVER["SERVER_ADDR"] .' ) SOLR DELETE Error: '.$type.':'.$code;
      
      QAMail ($email_body, $subject, false);
      echo $e->getMessage();
    }
  }
  
  //
  // 
  // Run some queries. Provide the raw path, a starting offset
  //   for result documents, and the maximum number of result
  //   documents to return. You can also use a fourth parameter
  //   to control how results are sorted and highlighted, 
  //   among other options.
  //
  function deleteQuery( $query ) {
    $solr = new Apache_Solr_Service( sfConfig::get("app_solr_ip"), sfConfig::get("app_solr_port"), sfConfig::get("app_solr_uri") );
    
    //
    //
    // Load the documents into the index
    // 
    try {
      $solr->deleteByQuery( $query );
      $solr->commit();
      //$solr->optimize();
    }
    catch ( Exception $e ) {
      $email_body .= "An SOLR DELETE Error has been reported by the ". $_SERVER["SERVER_NAME"] . " site ( ".$_SERVER["SERVER_ADDR"] ." ):"."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Page Request: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] ."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "Error:                    ".$e->getMessage()."<br />";
    	$email_body .= "======================================================================"."<br />";
    	$email_body .= "IP Address:               ".REMOTE_ADDR()."<br />";
    	$email_body .= "User Agent:               ".$_SERVER["HTTP_USER_AGENT"]."<br />";
    	$email_body .= "Time/Date:                 ".date("F j, Y, g:i a")."<br />";
    	$email_body .= "======================================================================"."<br />";
    	
    	$subject = $_SERVER["SERVER_NAME"].'( '.$_SERVER["SERVER_ADDR"] .' ) SOLR DELETE Error: '.$type.':'.$code;
      
      QAMail ($email_body, $subject, false);
      
      echo $e->getMessage();
    }
  }
  
?>
