<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-9414.phpt,v 1.1 2006/11/22 00:19:58 firman Exp $
?>
--TEST--
Bug #9414: Date::addSeconds() fails to work properly with negative numbers
--FILE--
<?php
/**
 * Test for: Date
 * Parts tested: Date::addSeconds()
 */

require_once 'Date.php';

$date = new Date('2006-11-21');

print "Date is now: " . $date->format("%Y-%m-%d %H:%M") . "\n";

$date->addSeconds(-1 * 86400 * 7); # subtract 1 week (negative value)
print 'After subtracting a week\'s worth of seconds, date is: ' . $date->format("%Y-%m-%d %H:%M") . "\n";

$date->subtractSeconds(-1 * 86400 * 7); # add 1 week (negative value)
print 'After subtracting a week\'s worth of seconds, date is: ' . $date->format("%Y-%m-%d %H:%M") . "\n";

?>
--EXPECT--
Date is now: 2006-11-21 00:00
After subtracting a week's worth of seconds, date is: 2006-11-14 00:00
After subtracting a week's worth of seconds, date is: 2006-11-21 00:00
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