<?php

/**
 * PhotsetList Offline Tests
 *
 * @version $Id: PhotosetList.php 520 2006-04-24 06:11:53Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/PhotosetList.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_PhotosetList extends PHPUnit2_Framework_TestCase {
    var $api;
    var $pslUserFromApi, $pslSpecifiedUser;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        // inject the response xml into the cache...
        // ... first the login details (so it can figure out the user id)
        $this->api->addResponseToCache(
            'flickr.auth.checkToken',
            $this->api->getParamsForRequest(),
            TESTING_RESP_OK_PREFIX . TESTING_XML_CHECKTOKEN . TESTING_RESP_SUFIX
        );
        // ... then the full photosetlist description, this user
        $this->api->addResponseToCache(
            Phlickr_PhotosetList::getRequestMethodName(),
            Phlickr_PhotosetList::getRequestMethodParams(TESTING_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );
        // ... then the full photosetlist description, other user
        $this->api->addResponseToCache(
            Phlickr_PhotosetList::getRequestMethodName(),
            Phlickr_PhotosetList::getRequestMethodParams(TESTING_OTHER_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );

        $this->pslUserFromApi = new Phlickr_PhotosetList($this->api);
        $this->pslSpecifiedUser = new Phlickr_PhotosetList($this->api, TESTING_OTHER_USER_ID);
    }
    function tearDown() {
        unset($this->pslDefaulUser);
        unset($this->pslSpecifiedUser);
        unset($this->api);
    }

    function testConstructor_AssignsApi() {
        $this->assertSame($this->api, $this->pslUserFromApi->getApi());
        $this->assertSame($this->api, $this->pslSpecifiedUser->getApi());
    }
    function testConstructor_ThrowsWithNoUserInfo() {
        $apiNoAuth = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        try {
var_dump("trying...");
            $psl = new Phlickr_PhotosetList($apiNoAuth);
        }
        catch (Phlickr_Exception $ex) {
var_dump("caught in...");
            return;
        }
        catch (Exception $ex) {
var_dump("caught");
            #$this->fail('threw the wrong type of exception.');
        }
        #$this->fail('Ths should have thrown an exception, no UserId was provided.');
    }
    function testConstructor_AssignsUserId() {
        $this->assertEquals($this->api->getUserId(), $this->pslUserFromApi->getUserId());
        $this->assertEquals(TESTING_OTHER_USER_ID, $this->pslSpecifiedUser->getUserId());
    }

    function testGetIds() {
        $result = $this->pslUserFromApi->getIds();
        $this->assertEquals(array('5', '4'), $result);
    }

    function testGetPhotosets_ReturnsValidData() {
        $result = $this->pslUserFromApi->getPhotosets();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
        $this->assertEquals(2, count($result));
        $this->assertEquals('5', $result[0]->getId());
        $this->assertEquals('4', $result[1]->getId());
    }

    function testGetCount_ReturnsNumberOfMembers() {
        $result = $this->pslUserFromApi->getCount();
        $this->assertTrue(is_int($result), 'returned wrong type');
        $this->assertEquals(2, $result);
    }


    function testGetPhotosetByName_Missing() {
        $name = 'Something that does not exist...';
        $result = $this->pslDefaulUser->getPhotosetByName($name);
        $this->assertNull($result);
    }
}
