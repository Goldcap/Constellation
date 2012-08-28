<?php
// this check prevents access to debug front conrollers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
//if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
//{
//  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
//}

require_once (dirname(__FILE__).'/../lib/vendor/utils.php');
require_once (dirname(__FILE__).'/../lib/vendor/wtvr/1.3/WTVRUtils.php');
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', false);
sfContext::createInstance($configuration)->dispatch();
