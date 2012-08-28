<?php
  include_once(sfConfig::get('sf_lib_dir')."/helper/ImageHelper.php");

  class Home_PageWidget extends Widget_PageWidget {

	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
    if ($this -> getUser() -> getAttribute("user_id")) {
      $userid = $this -> getUser() -> getAttribute("user_id");
    }  else {
      $userid = -1;
    }

    $util = new HomeFilmList_format_utility($this -> context);

    sfConfig::set("end_date", "[". formatDate(time() + 30,"W3XMLIN")." TO * ]");
    $events = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Home/query/Events_datamap.xml", true, "array", $util);

    if ($events["meta"]["totalresults"] > 0) {
      $i = 0;
      foreach ($events["data"] as $event) {
        $events["data"][$i]["is_attending"] = false;

        $events["data"][$i]["is_inprogress"] = strtotime($event["screening_date"]) < strtotime(date("Y-m-d H:i:s")) && (strtotime($event["screening_end_time"]) + 120 )> strtotime(date("Y-m-d H:i:s"));
        if ((int) $event["screening_user_id"] == $userid) {
          $events["data"][$i]["is_attending"] = true;
        } else {
          $sql = "select audience_id, fk_user_id from audience where fk_screening_id = ".$event["screening_id"]." and audience_paid_status = 2";
          $res = $this -> propelQuery($sql);
          $val = $res -> fetchall();
          foreach($val as $aval) {
            if ($aval["fk_user_id"] == $userid) {
              $events["data"][$i]["is_attending"] = true;
            }
          }
        }
      $i++;
      }
    }

    $this -> widget_vars["events"] = $events["data"];
    $this -> widget_vars["eventsCount"] = (int) $events["meta"]["totalresults"]; 
    return $this -> widget_vars;
    
  }

  function isAttendingEvent(){

  }
}