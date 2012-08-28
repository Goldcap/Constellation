<?php

/**
 * Group Offline Tests
 *
 * @version $Id: Group.php 510 2006-02-05 03:44:39Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Group.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_Group extends PHPUnit2_Framework_TestCase {
    var $api;
    var $fromId, $fromShortXml, $fromLongXml;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        // inject the response xml into the cache...
        // ... first for the full description of the group
        $this->api->addResponseToCache(
            Phlickr_Group::getRequestMethodName(),
            Phlickr_Group::getRequestMethodParams(TESTING_XML_GROUP_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_GROUP_LONG . TESTING_RESP_SUFIX
        );
        // ... then for group pool
        $this->api->addResponseToCache(
            'flickr.groups.pools.getPhotos',
            array(
                'group_id' => TESTING_XML_GROUP_ID,
                'page' => 1,
                'per_page' => 10
            ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_GROUP_PHOTOLIST . TESTING_RESP_SUFIX
        );

        $this->fromId = new Phlickr_Group($this->api, TESTING_XML_GROUP_ID);
        $this->fromShortXml = new Phlickr_Group($this->api, simplexml_load_string(TESTING_XML_GROUP_SHORT));
        $this->fromLongXml = new Phlickr_Group($this->api, simplexml_load_string(TESTING_XML_GROUP_LONG));
    }

    function tearDown() {
        unset($this->api);
        unset($this->fromId);
        unset($this->fromShortXml);
        unset($this->fromLongXml);
    }

    function testConstructor_FromIdAssignsApi() {
        $this->assertEquals($this->api, $this->fromId->getApi());
    }
    function testConstructor_FromShortXmlAssignsApi() {
        $this->assertEquals($this->api, $this->fromShortXml->getApi());
    }
    function testConstructor_FromLastIdAssignsApi() {
        $this->assertEquals($this->api, $this->fromLongXml->getApi());
    }

    function testConstructor_FromIdAssignsId() {
        $this->assertEquals(TESTING_XML_GROUP_ID, $this->fromId->getId());
    }
    function testConstructor_FromShortXmlAssignsId() {
        $this->assertEquals(TESTING_XML_GROUP_ID, $this->fromShortXml->getId());
    }
    function testConstructor_FromLastIdAssignsId() {
        $this->assertEquals(TESTING_XML_GROUP_ID, $this->fromLongXml->getId());
    }

    function testGetName_FromId() {
        $this->assertEquals(TESTING_XML_GROUP_NAME, $this->fromId->getName());
    }
    function testGetName_FromShort() {
        $this->assertEquals(TESTING_XML_GROUP_NAME, $this->fromShortXml->getName());
    }
    function testGetName_FromLong() {
        $this->assertEquals(TESTING_XML_GROUP_NAME, $this->fromLongXml->getName());
    }

    function testGetPhotoList_ReturnsCorrectClass() {
        $result = $this->fromLongXml->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
    }
    function testGetPhotoList_HasCorrectRequest() {
        $request = $this->fromLongXml->getPhotoList()->getRequest();
        $this->assertEquals('flickr.groups.pools.getPhotos', $request->getMethod());
        $params = $request->getParams();
        $this->assertEquals($this->fromLongXml->getId(), $params['group_id']);
    }
    function testGetPhotoList_ReturnsCorrectValues() {
        $result = $this->fromLongXml->getPhotoList();
        $this->assertEquals(2, $result->getCount());
        $this->assertEquals(array('3928131','3681845'), $result->getIds());
    }

    function testBuildUrl() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildUrl();
        $this->assertEquals("http://flickr.com/groups/{$groupId}/", $result);
    }

    function testBuildPhotoFeedUrl() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildPhotoFeedUrl();
        $this->assertEquals("http://flickr.com/groups/{$groupId}/pool/feed?format=atom", $result);
    }
    function testBuildPhotoFeedUrl_Atom() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildPhotoFeedUrl('atom');
        $this->assertEquals("http://flickr.com/groups/{$groupId}/pool/feed?format=atom", $result);
    }
    function testBuildPhotoFeedUrl_Rss() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildPhotoFeedUrl('rss');
        $this->assertEquals("http://flickr.com/groups/{$groupId}/pool/feed?format=rss", $result);
    }

    function testBuildCommentFeedUrl() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildDiscussFeedUrl();
        $this->assertEquals("http://flickr.com/groups_feed.gne?id={$groupId}&format=atom", $result);
    }
    function testBuildCommentFeedUrl_Atom() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildDiscussFeedUrl('atom');
        $this->assertEquals("http://flickr.com/groups_feed.gne?id={$groupId}&format=atom", $result);
    }
    function testBuildCommentFeedUrl_Rss() {
        $groupId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildDiscussFeedUrl('rss');
        $this->assertEquals("http://flickr.com/groups_feed.gne?id={$groupId}&format=rss", $result);
    }
}
