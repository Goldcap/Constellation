<?php

class CSessionStorage extends sfSessionStorage
{
  public function initialize($options = null)
  {
    // work-around for swfuploader
    if(sfContext::getInstance()->getRequest()->getParameter('constellation_frontend')) {
      session_id(sfContext::getInstance()->getRequest()->getParameter('constellation_frontend'));
    }

    parent::initialize($options);
  }
}

?>
