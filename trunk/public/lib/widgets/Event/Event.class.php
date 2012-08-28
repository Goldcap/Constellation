<?php
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php"); 

  class Event_PageWidget extends Widget_PageWidget {
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));

   

    parent::__construct( $context );
  }

	function parse() {
    $event = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Event/query/Event_datamap.xml");
    if(empty($event['data'])) {
      $this -> redirect('/');
    }
    sfConfig::set("filmId", $event['data'][0]['screening_film_id']);
    $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Event/query/Film_datamap.xml");
    $host = getUserById($event['data'][0]['screening_user_id']);

    $etoken = new TheaterAkamaiEtoken( $this -> context, null );
    $etoken -> streamType = "film";
    $etoken -> generateViewUrl( $film, "", 84600 );

    $security = new TheaterSecurity_PageWidget($this -> context);

    if($event['data'][0]['screening_qa'] != ''){
      $qa[] = array();
      $imp = explode(',', $event['data'][0]['screening_qa']);
      foreach ($imp as $qa) {
        $imp2 = explode('|', $qa);
        $qas[] = array('title' => $imp2[0], 'youtubeId' => $imp2[1]);
      }

      $event['data'][0]['screening_qa'] = $qas;
    } else {
      $event['data'][0]['screening_qa'] = array();
    }



    $this -> widget_vars["event"] = $event['data'][0];
    $this -> widget_vars["event"]["screening_film_trailer_url"] = $this ->getTrailer($event);
    $this -> widget_vars["event"]["audience"] = $this->getAttendees($event['data'][0]['screening_id']);
    $this -> widget_vars["film"] = $film['data'][0];
    $this -> widget_vars["host"] = $host;
    $this -> widget_vars["stream_url"] = $etoken -> viewingUrl;
    $this -> widget_vars["isAttending"] = $this -> isAttending($event['data'][0]['screening_id']);
    $this -> widget_vars["gbip"] = $security -> checkGeoBlock($event["data"][0]["film_geoblocking_type"],REMOTE_ADDR());
    return $this -> widget_vars;
  }

  function getTrailer($event){
    $etoken = new TheaterAkamaiEtoken( $this -> context, null );
    $etoken -> streamType = "film";
    $etoken -> generateViewUrl( $event, "", 84600 );
    return $etoken -> viewingUrl;
  }
  function getAttendees($screeningId){
    // if ($this -> sessionVar("user_id") > 0) {
     $sql = "select distinct fk_user_id,
              user.user_username,
              case when user.user_photo_url is not NULL
              then user.user_photo_url
              else 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
              end
              from payment
              inner join `user`
              on user.user_id = fk_user_id
              where fk_screening_id = ".$screeningId."
              and payment.payment_status = 2
              order by payment_created_at desc
              limit 16";
      // if ($this -> getVar("limit")) {
      //   $sql = $sql . " limit " . $this -> getVar("limit");
      // }
      
      $res = $this -> propelQuery($sql);
      $ii = 0;
      while( $row = $res-> fetch()) {
        $user["id"] = $row[0];
        $user["username"] = $row[1];
        if (preg_match("/twimg/",$row[2])) {
          $user["image"] = "https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png";
        } else {
          if (left($row[2],4) == 'http') {
            $user["image"] = str_replace("http:","https",$row[2]);
          } else {
            $user["image"] = '/uploads/hosts/'.$row[0].'/'.$row[2];
          }
        }
        $users[] = $user;
      }
     return  $users;
  }
  function isAttending($screeningId){
// $screeningId
    $userIsAttending = false;
    $userid = -1;
    if ($this -> getUser() -> getAttribute("user_id")) {
      $userid = $this -> getUser() -> getAttribute("user_id");
    }
    $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ". $screeningId ." and audience_paid_status = 2";
    $res = $this -> propelQuery($sql);
    $val = $res -> fetchall();
    $userIsAttending = false;
    foreach($val as $aval) {
      if ($aval["fk_user_id"] == $userid) {
        $userIsAttending = true;
      }
    }
    return $userIsAttending;
  }
}