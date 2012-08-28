<?php

//Utility Classes
require_once (dirname(__FILE__).'/../../lib/vendor/utils.php');
require_once (dirname(__FILE__).'/../../lib/vendor/wtvr/1.3/WTVRUtils.php');
//For the Form Generator
set_include_path(".:".dirname(__FILE__)."/../../public/lib/vendor/styroform/1.2/xsl:".dirname(__FILE__)."/../../web");
require_once dirname(__FILE__).'/../bootstrap/unit.php';

new TestWidget( $arguments, $options, $this );

?>
