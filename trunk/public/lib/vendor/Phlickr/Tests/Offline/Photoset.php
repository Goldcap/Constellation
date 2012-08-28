<?php

/**
 * Photoset Offline Tests
 *
 * @version $Id: Photoset.php 494 2005-11-26 10:03:16Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Photoset.php';

class Phlickr_Tests_Offline_Photoset extends PHPUnit2_Framework_TestCase {
    var $api;
    var $psXml, $psInteger;

    function setUp() {
	$this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

	// inject the response xml into the cache...
        // ... first for the full description of the photoset
        $this->api->addResponseToCache(
            Phlickr_Photoset::getRequestMethodName(),
            Phlickr_Photoset::getRequestMethodParams(TESTING_XML_PHOTOSET_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSET . TESTING_RESP_SUFIX
        );

        $this->psInteger = new Phlickr_Photoset($this->api, TESTING_XML_PHOTOSET_ID);
        $this->psXml = new Phlickr_Photoset($this->api, simplexml_load_string(TESTING_XML_PHOTOSET));
    }
    function tearDown() {
        unset($this->psInteger);
        unset($this->psXml);
        unset($this->api);
    }

    function testConstructor_AssignsApi() {
        $this->assertSame($this->api, $this->psXml->getApi());
        $this->assertSame($this->api, $this->psInteger->getApi());
    }
    function testConstructor_AssignsId() {
        $this->assertEquals(TESTING_XML_PHOTOSET_ID, $this->psXml->getId(), 'xml\'s did not match');
        $this->assertEquals(TESTING_XML_PHOTOSET_ID, $this->psInteger->getId(), 'integer\'s did not match');
    }
    function testConstructor_AssignsPrimaryId() {
        $this->assertEquals('2483', $this->psXml->getPrimaryId(), 'xml\'s did not match');
        $this->assertEquals('2483', $this->psInteger->getPrimaryId(), 'integer\'s did not match');
    }
    function testConstructor_AssignsTitle() {
        $this->assertEquals('My Set', $this->psXml->getTitle(), 'xml\'s did not match');
        $this->assertEquals('My Set', $this->psInteger->getTitle(), 'integer\'s did not match');
    }
    function testConstructor_AssignsDescription() {
        $this->assertEquals('bar', $this->psXml->getDescription(), 'xml\'s did not match');
        $this->assertEquals('bar', $this->psInteger->getDescription(), 'integer\'s did not match');
    }

    function testGetPhotoCount() {
        $result = $this->psInteger->getPhotoCount();
	$this->assertEquals(4, $result);
    }

    function testGetPhotoList_ReturnsPhotoListObject() {
	// insert photos
        $this->api->addResponseToCache(
            'flickr.photosets.getPhotos',
            array('photoset_id' => TESTING_XML_PHOTOSET_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSET_PHOTOS . TESTING_RESP_SUFIX
        );

        $pl = $this->psInteger->getPhotoList();
	$this->assertType('Phlickr_PhotosetPhotoList', $pl);
    }

    function testGetPhotoList_HasCorrectIds() {
        // insert photos
        $this->api->addResponseToCache(
            'flickr.photosets.getPhotos',
            array('photoset_id' => TESTING_XML_PHOTOSET_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSET_PHOTOS . TESTING_RESP_SUFIX
        );

        $photos = $this->psInteger->getPhotoList();
        $this->assertEquals(array('2484', '2483', '2487', '2488', '2489'), $photos->getIds(), 'Photos were not set correctly.');
    }

    function testGetUserId() {
	$result = $this->psInteger->getUserId();
	$this->assertEquals('12037949754@N01', $result);
    }

    function testBuildUrl() {
	$userId = $this->psInteger->getUserId();
	$setId = $this->psInteger->getId();
	$result = $this->psInteger->buildUrl();
	$this->assertEquals("http://flickr.com/photos/{$userId}/sets/{$setId}/", $result);
    }
}

?>
