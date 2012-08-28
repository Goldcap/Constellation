<?php

/**
 * Runner for all tests.
 *
 * To run the online test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Online_AllTests
 *
 * @version $Id: AllTests.php 379 2005-08-14 01:27:32Z drewish $
 * @copyright 2005
 */

if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Phlickr_Tests_Online_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PHPUnit2/Util/Filter.php';

require_once 'Phlickr/Tests/Online/Api.php';
require_once 'Phlickr/Tests/Online/Request.php';
//require_once 'Phlickr/Tests/Online/Uploader.php';

require_once 'Phlickr/Tests/Online/AuthedGroup.php';
require_once 'Phlickr/Tests/Online/AuthedPhoto.php';
require_once 'Phlickr/Tests/Online/AuthedPhotoset.php';
require_once 'Phlickr/Tests/Online/AuthedPhotosetList.php';
require_once 'Phlickr/Tests/Online/AuthedUser.php';
require_once 'Phlickr/Tests/Online/Group.php';
require_once 'Phlickr/Tests/Online/Photo.php';
require_once 'Phlickr/Tests/Online/PhotoList.php';
require_once 'Phlickr/Tests/Online/Photoset.php';
require_once 'Phlickr/Tests/Online/PhotosetList.php';
require_once 'Phlickr/Tests/Online/User.php';


class Phlickr_Tests_Online_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('Phlickr Online Tests');

        // core
        $suite->addTestSuite('Phlickr_Tests_Online_Api');
        $suite->addTestSuite('Phlickr_Tests_Online_Request');
        $suite->addTestSuite('Phlickr_Tests_Online_Uploader');

        // wrappers
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedGroup');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedPhoto');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedPhotoset');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedPhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Online_AuthedUser');
        $suite->addTestSuite('Phlickr_Tests_Online_Group');
        $suite->addTestSuite('Phlickr_Tests_Online_Photo');
        $suite->addTestSuite('Phlickr_Tests_Online_PhotoList');
        $suite->addTestSuite('Phlickr_Tests_Online_Photoset');
        $suite->addTestSuite('Phlickr_Tests_Online_PhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Online_User');

        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Phlickr_Tests_Online_AllTests::main') {
    Phlickr_Tests_Online_AllTests::main();
}


?>
