<?php

date_default_timezone_set("America/New_York");

require_once (dirname(__FILE__).'/../lib/vendor/utils.php');
require_once (dirname(__FILE__).'/../lib/vendor/wtvr/1.3/WTVRUtils.php');
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('admin', 'test', false);
sfContext::createInstance($configuration)->dispatch();
