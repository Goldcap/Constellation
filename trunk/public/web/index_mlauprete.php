<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.

require_once (dirname(__FILE__).'/../lib/vendor/utils.php');
require_once (dirname(__FILE__).'/../lib/vendor/wtvr/1.3/WTVRUtils.php');
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'mlauprete', true);
sfContext::createInstance($configuration)->dispatch();
