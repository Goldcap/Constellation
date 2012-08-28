<?php

/**
 * Runner for all offline import tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_Import_AllTests
 *
 * @version $Id: AllTests.php 498 2006-01-03 10:37:53Z drewish $
 * @copyright 2005
 */


if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Phlickr_Tests_Offline_Import_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PHPUnit2/Util/Filter.php';

# LEAVE THIS COMMENTED OUT UNLESS YOU SETUP THE GALLERY INSTALLATION
#require_once 'Phlickr/Tests/Offline/Import/Gallery.php';
require_once 'Phlickr/Tests/Offline/Import/Makethumbs.php';

class Phlickr_Tests_Offline_Import_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('Phlickr Offline Import Tests');

//        $suite->addTestSuite('Phlickr_Tests_Offline_Import_Gallery');
//        $suite->addTestSuite('Phlickr_Tests_Offline_Import_Makethumbs');

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Phlickr_Tests_Offline_Import_AllTests::main') {
    Phlickr_Tests_Offline_Import_AllTests::main();
}

?>
