<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-8912.phpt,v 1.1 2006/11/20 09:07:42 firman Exp $
?>
--TEST--
Bug #8912: putenv() causes crashes in DateTimeZone::inDaylightTime() under windows
--FILE--
<?php
/**
 * Test for: Date_TimeZone
 * Parts tested: Date_TimeZone::inDaylightTime()
 */

require_once 'Date.php';

$states = array(
    'Australia/Adelaide',
    'Australia/Canberra',
    'Australia/Darwin',
    'Australia/Brisbane',
    'Australia/Hobart',
    'Australia/Melbourne',
    'Australia/Perth',
    'Australia/Sydney'
);

$originalTimezone = new Date_TimeZone('Australia/Adelaide'); 

foreach ($states as $state) {
    $new_date = new Date(time());
    print 'Original Time (Australia/Adelaide): ' . $new_date->getTime() . "\n";
    $timezone = new Date_TimeZone($state); 
    $new_date->setTZ($originalTimezone);
    $new_date->convertTZ($timezone);
    print $state . ': ' . $new_date->getTime() . "\n";
    print "\n";
}
?>
--EXPECT--
Original Time (Australia/Adelaide): (timestamp)
Australia/Adelaide: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Canberra: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Darwin: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Brisbane: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Hobart: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Melbourne: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Perth: (timestamp)

Original Time (Australia/Adelaide): (timestamp)
Australia/Sydney: (timestamp)
<?php
/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>