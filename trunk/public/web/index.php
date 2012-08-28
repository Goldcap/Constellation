<?php

require_once (dirname(__FILE__).'/../lib/vendor/utils.php');
require_once (dirname(__FILE__).'/../lib/vendor/wtvr/1.3/WTVRUtils.php');
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
sfContext::createInstance($configuration)->dispatch();
