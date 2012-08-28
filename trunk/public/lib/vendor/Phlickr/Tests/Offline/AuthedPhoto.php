<?php

/**
* AuthedPhoto Offline Tests
*
* @version $Id: AuthedPhoto.php 508 2006-02-02 09:00:31Z drewish $
* @copyright 2005
*/

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/AuthedPhoto.php';

class Phlickr_Tests_Offline_AuthedPhoto extends PHPUnit2_Framework_TestCase {
    var $api;
    var $photo;

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
        // ... then the full description of the photo
        $this->api->addResponseToCache(
            Phlickr_Photo::getRequestMethodName(),
            Phlickr_Photo::getRequestMethodParams(TESTING_PHOTO_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTO_LONG . TESTING_RESP_SUFIX
        );
        $this->photo = new Phlickr_AuthedPhoto($this->api, TESTING_PHOTO_ID);
    }
    function tearDown() {
        unset($this->photo);
        unset($this->api);
    }

    function testConstructor_AssignsApi() {
        $this->assertEquals($this->api, $this->photo->getApi(), 'api was not assigned.');
    }

    function testConstructor_AssignsId() {
        $this->assertEquals(TESTING_PHOTO_ID, $this->photo->getId(), 'id was not assigned.');
    }

    function testConstructor_AssignsSecret() {
        $this->assertEquals('123456', $this->photo->getSecret(), 'secret was not assigned.');
    }

    function testConstructor_AssignsServer() {
        $this->assertEquals(12, $this->photo->getServer(), 'server was not assigned.');
    }


    function testDelete() {
        $this->api->addResponseToCache(
            'flickr.photos.delete',
            array('photo_id' => TESTING_PHOTO_ID),
            TESTING_RESP_OK_PREFIX . TESTING_RESP_SUFIX
        );
        $ret = $this->photo->delete();
    }


    function testGetPostedTimestamp() {
        $this->assertEquals(1100897479, $this->photo->getPostedTimestamp());
    }
    function testGetTakenTimestamp() {
        $expected = mktime(12, 51, 19, 11, 19, 2004);
        $actual = $this->photo->getTakenTimestamp();
        $this->assertEquals($expected, $actual);
    }
    function testGetTakenGranularity() {
        $this->assertEquals(0, $this->photo->getTakenGranularity());
    }


    function testGetTaken_Granularity4() {
        $photo = new Phlickr_AuthedPhoto($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12">
    <dates taken="2004-11-01 00:00:00" takengranularity="4" />
</photo>
XML
));
        $this->assertEquals(4, $photo->getTakenGranularity());
        $expected = mktime(0, 0, 0, 11, 01, 2004);
        $this->assertEquals(4, $photo->getTakenGranularity());
        $this->assertEquals($expected, $photo->getTakenTimestamp());
    }
    function testGetTaken_Granularity6() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12">
    <dates taken="2004-01-01 00:00:00" takengranularity="6" />
</photo>
XML
));
        $expected = mktime(0, 0, 0, 1, 1, 2004);
        $this->assertEquals(6, $photo->getTakenGranularity());
        $this->assertEquals($expected, $photo->getTakenTimestamp());
    }


    function testGetTags() {
        $result = $this->photo->getTags();
        $this->assertTrue(is_array($result), 'Did not return an array.');
        $this->assertEquals(array('wooyay', 'hoopla'), $result);
    }


    function testGetTitle() {
        $result = $this->photo->getTitle();
        $this->assertEquals('orford_castle_taster', $result);
    }

    function testGetDescription() {
        $result = $this->photo->getDescription();
        $this->assertEquals('hello!', $result);
    }

    function testGetUserId() {
        $result = $this->photo->getUserId();
        $this->assertEquals('12037949754@N01', $result);
    }

    function testBuildUrl() {
        $photoId = $this->photo->getId();
        $userId = $this->photo->getUserId();
        $result = $this->photo->buildUrl();
        $this->assertEquals("http://flickr.com/photos/{$userId}/{$photoId}/", $result);
    }
}
