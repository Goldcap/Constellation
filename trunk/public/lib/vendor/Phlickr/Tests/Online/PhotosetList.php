<?php

/**
 * PhotsetList Online Tests
 *
 * @version $Id: PhotosetList.php 523 2006-08-28 18:30:20Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/PhotosetList.php';

class Phlickr_Tests_Online_PhotosetList extends PHPUnit2_Framework_TestCase {
    var $api;
    var $pslDefaulUser, $pslSpecifiedUser;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->pslDefaulUser = new Phlickr_PhotosetList($this->api);
        $this->pslSpecifiedUser = new Phlickr_PhotosetList($this->api, TESTING_OTHER_USER_ID);
    }
    function tearDown() {
        unset($this->pslDefaulUser);
        unset($this->pslSpecifiedUser);
        unset($this->api);
    }

    function testGetIds_WorksWithDefaultUser() {
        $result = $this->pslDefaulUser->getIds();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
    }
    function testGetIds_WorksWithSpecifiedUser() {
        $result = $this->pslSpecifiedUser->getIds();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
    }

    function testGetPhotosets_WorksWithDefaultUser() {
        $result = $this->pslDefaulUser->getPhotosets();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
        foreach ($result as $o) {
            $this->assertType('Phlickr_Photoset', $o, 'Should have returned an array of Photosets. ');
        }
    }
    function testGetPhotosets_WorksWithSpecifiedUser() {
        $result = $this->pslSpecifiedUser->getPhotosets();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
        foreach ($result as $o) {
            $this->assertType('Phlickr_Photoset', $o, 'Should have returned an array of Photosets. ');
        }
    }
}
