<?php

/**
 * AuthedPhotsetList Offline Tests
 *
 * @version $Id: AuthedPhotosetList.php 519 2006-04-24 06:10:30Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/AuthedPhotosetList.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_AuthedPhotosetList extends PHPUnit2_Framework_TestCase {
    var $api;
    var $psl;

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
            'flickr.photosets.getList',
            array('user_id' => TESTING_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );
        // ... then the full photosetlist description, other user
        $this->api->addResponseToCache(
            'flickr.photosets.getList',
            array('user_id' => TESTING_OTHER_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );

        $this->psl = new Phlickr_AuthedPhotosetList($this->api);
    }
    function tearDown() {
        unset($this->psl);
        unset($this->api);
    }

    function testConstructor_AssignsApi() {
        $this->assertSame($this->api, $this->psl->getApi());
    }
    function testConstructor_ThrowsWithNoUserInfo() {
        $apiNoAuth = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        try {
            $psl = new Phlickr_AuthedPhotosetList($apiNoAuth);
        } catch (Phlickr_Exception $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->fail('Ths should have thrown an exception, no UserId was provided.');
    }
    function testConstructor_AssignsUserId() {
        $this->assertEquals($this->api->getUserId(), $this->psl->getUserId());
    }

    function testGetIds() {
        $result = $this->psl->getIds();
        $this->assertEquals(array('5', '4'), $result);
    }

    function testGetCount_ReturnsNumberOfMembers() {
        $result = $this->psl->getCount();
        $this->assertTrue(is_int($result), 'returned wrong type');
        $this->assertEquals(2, $result);
    }
    function testGetPhotosets_ReturnsValidData() {
        $result = $this->psl->getPhotosets();
        $this->assertTrue(is_array($result), 'Response should be an an array.');

        $this->assertEquals('5', $result[0]->getId());
        $this->assertEquals('4', $result[1]->getId());
    }
    function testGetPhotosets_ReturnsCorrectType() {
        $result = $this->psl->getPhotosets();
        $this->assertTrue(is_array($result), 'Response should be an an array.');
        foreach ($result as $photoset) {
            $this->assertType('Phlickr_AuthedPhotoset', $photoset);
        }
    }

/*
    function testCreate() {
        // add a response
        $this->api->addResponseToCache(
            'flickr.photosets.create',
            array('title' => 'foo',
                'description' => 'bar',
                'primary_photo_id' => TESTING_REAL_PHOTO_ID_JPG),
            TESTING_RESP_OK_PREFIX ."<photoset id=\"72057594115422046\" url=\"http://www.flickr.com/photos/drewish/sets/72057594115422046/\"/>". TESTING_RESP_SUFIX
        );

        // create it
        $result = $this->psl->create('foo', 'bar', TESTING_REAL_PHOTO_ID_JPG);
        $this->assertType('string', $result, 'Returned the wrong type.');
        $this->assertEquals('72057594115422046', $result);
    }
*/
}
