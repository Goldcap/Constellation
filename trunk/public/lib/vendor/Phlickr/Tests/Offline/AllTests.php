<?php

/**
 * Runner for all offline tests.
 *
 * To run the offline test suites (assuming the Phlickr installation is in the
 * include path) run:
 *      phpunit Phlickr_Tests_Offline_AllTests
 *
 * @version $Id: AllTests.php 500 2006-01-03 23:29:08Z drewish $
 * @copyright 2005
 */

if (!defined('PHPUnit2_MAIN_METHOD')) {
    define('PHPUnit2_MAIN_METHOD', 'Phlickr_Tests_Offline_AllTests::main');
}

require_once 'PHPUnit2/Framework/TestSuite.php';
require_once 'PHPUnit2/TextUI/TestRunner.php';
require_once 'PHPUnit2/Util/Filter.php';

// sub-directories
require_once 'Phlickr/Tests/Offline/Import/AllTests.php';
require_once 'Phlickr/Tests/Offline/PhotoSortStrategy/AllTests.php';
require_once 'Phlickr/Tests/Offline/TextUi/AllTests.php';

// core
require_once 'Phlickr/Tests/Offline/Api.php';
require_once 'Phlickr/Tests/Offline/Cache.php';
require_once 'Phlickr/Tests/Offline/Request.php';
require_once 'Phlickr/Tests/Offline/Response.php';
require_once 'Phlickr/Tests/Offline/Uploader.php';

// wrappers
require_once 'Phlickr/Tests/Offline/AuthedGroup.php';
require_once 'Phlickr/Tests/Offline/AuthedPhoto.php';
require_once 'Phlickr/Tests/Offline/AuthedPhotoset.php';
require_once 'Phlickr/Tests/Offline/AuthedPhotosetList.php';
require_once 'Phlickr/Tests/Offline/AuthedUser.php';
require_once 'Phlickr/Tests/Offline/Group.php';
require_once 'Phlickr/Tests/Offline/GroupList.php';
//require_once 'Phlickr/Tests/Offline/Note.php';
require_once 'Phlickr/Tests/Offline/Photo.php';
require_once 'Phlickr/Tests/Offline/PhotoList.php';
require_once 'Phlickr/Tests/Offline/PhotoListIterator.php';
require_once 'Phlickr/Tests/Offline/Photoset.php';
require_once 'Phlickr/Tests/Offline/PhotosetPhotoList.php';
require_once 'Phlickr/Tests/Offline/PhotosetList.php';
require_once 'Phlickr/Tests/Offline/PhotoSorter.php';
require_once 'Phlickr/Tests/Offline/User.php';
require_once 'Phlickr/Tests/Offline/UserList.php';

class Phlickr_Tests_Offline_AllTests {
    public static function main() {
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite('Phlickr Offline Tests');

        // sub-directories
        $suite->addTest(Phlickr_Tests_Offline_Import_AllTests::suite());
        $suite->addTest(Phlickr_Tests_Offline_PhotoSortStrategy_AllTests::suite());
        $suite->addTest(Phlickr_Tests_Offline_TextUi_AllTests::suite());

        // core
        $suite->addTestSuite('Phlickr_Tests_Offline_Api');
        $suite->addTestSuite('Phlickr_Tests_Offline_Cache');
        $suite->addTestSuite('Phlickr_Tests_Offline_Request');
        $suite->addTestSuite('Phlickr_Tests_Offline_Response');
        $suite->addTestSuite('Phlickr_Tests_Offline_Uploader');

        // wrappers
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedGroup');
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedPhoto');
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedPhotoset');
#        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedPhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Offline_AuthedUser');
        $suite->addTestSuite('Phlickr_Tests_Offline_Group');
        $suite->addTestSuite('Phlickr_Tests_Offline_GroupList');
        $suite->addTestSuite('Phlickr_Tests_Offline_Note');
        $suite->addTestSuite('Phlickr_Tests_Offline_Photo');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoList');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoListIterator');
        $suite->addTestSuite('Phlickr_Tests_Offline_Photoset');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotosetPhotoList');
#        $suite->addTestSuite('Phlickr_Tests_Offline_PhotosetList');
        $suite->addTestSuite('Phlickr_Tests_Offline_PhotoSorter');
        $suite->addTestSuite('Phlickr_Tests_Offline_User');
        $suite->addTestSuite('Phlickr_Tests_Offline_UserList');
        return $suite;
    }
}

if (PHPUnit2_MAIN_METHOD == 'Phlickr_Tests_Offline_AllTests::main') {
    Phlickr_Tests_Offline_AllTests::main();
}
