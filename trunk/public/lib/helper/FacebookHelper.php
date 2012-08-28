<?php

function sendFacebookWalls( $ids, $session=false, $name=false, $link=false, $description=false, $message=false, $picture=false, $beacon=false ) {
  $facebook = new FacebookAPI(array(
  'appId'  => sfConfig::get("app_facebook_app_id"),
  'secret' => sfConfig::get("app_facebook_secret"),
  'cookie' => true,
  ));
  
  if (! $session) {
    $session = $facebook -> getAccessToken();
  }
  
  if (! $name) {
    $name = "";
  }
  
  if (! $link) {
    $link = "http://www.constellation.tv";
  }
  
  if (! $description) {
    $description = "Constellation.tv, 'Your Online Movie Theater'; join Constellation to watch movies with others. Invite five friends and get your ticket for free.";
  }
  
  if (! $message) {
    $message = "I signed up to Constellation, 'Your Online Movie Theater'";
  }
  
  if (! $picture) {
    $picture = 'http://s3.amazonaws.com/cdn.constellation.tv/prod/images/alt1/logo-fb.png';
  }
  
  if (! $beacon) {
    $beacon = "";
  }
      
  $successcount = 0;
  foreach($ids as $id) {
    $url = "https://graph.facebook.com/".$id."/feed";
		$attachment =  array(   'access_token'  => $session,                        
				                    //'name'          => $name,
				                    'link'          => $link.$beacon,
				                    'description'   => $description,
				                    'message' => $message,
														'picture' => $picture,
				                );
    try {
			$res = $facebook -> api('/'.$id.'/feed','post',$attachment);
      if(isset($res["id"])) {
			  $successcount++;
      }
		} catch ( Exception $e ) {
		}	
	}
  return $successcount;
}