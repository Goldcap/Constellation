<?php

function checkPartner( $context, $redirect=false, $film=0 ) {
	
  $thishost = explode(":",$_SERVER["HTTP_HOST"]);
  if ($thishost[0] == sfConfig::get("app_domain")) {
	 	return false;
	}
	if (($context ->getRequest()->getCookie("partner") != "") || ($context -> getRequest() -> getAttribute("partner") != "")) {
		
		if ($context -> getRequest() -> getAttribute("partner") != "") {
		  $partner["partner"] =  $context ->getRequest()-> getAttribute("partner");
			$partner["partner_logo"] =  $context ->getRequest()->getAttribute("partner_logo");
			$partner["partner_url"] =  $context ->getRequest()->getAttribute("partner_url");
		} else {
		  $partner["partner"] =  $context ->getRequest()->getCookie("partner");
			$partner["partner_logo"] =  $context ->getRequest()->getCookie("partner_logo");
			$partner["partner_url"] =  $context ->getRequest()->getCookie("partner_url");
		}
		
		if ($partner["partner"] == "constellation") {
			return;
		}
		
		if ($redirect) {
			header("Location: ".$partner["partner_url"]);
	  	die();
		}
		if ($film > 0) {
			$c = new Criteria();
		  $c->add(ProgramPeer::PROGRAM_SHORT_NAME,$partner["partner"]);
		  $c->add(ProgramFilmPeer::FK_FILM_CHILD_ID,$film);
		  $c->addJoin(ProgramFilmPeer::FK_FILM_ID,ProgramPeer::PROGRAM_ID);
		  $theprogram = ProgramFilmPeer::doSelect($c);
		  if (count($theprogram) == 0) {
				header("Location: ".$partner["partner_url"]);
		  	die();
			}
		}
		sfConfig::set("app_domain",$partner["partner"].".constellation.tv");
		return $partner;
	}
}
