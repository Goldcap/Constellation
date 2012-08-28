<?php

/**
 * Group Offline Tests
 *
 * @version $Id: AuthedGroup.php 494 2005-11-26 10:03:16Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/AuthedGroup.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_AuthedGroup extends PHPUnit2_Framework_TestCase {
    var $api;
    var $group;

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
        // ... the full description of the group
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

        $this->group = new Phlickr_AuthedGroup($this->api, TESTING_XML_GROUP_ID);
    }

    function tearDown() {
        unset($this->api);
        unset($this->group);
    }

    function testConstructor_FromIdAssignsApi() {
        $this->assertEquals($this->api, $this->group->getApi());
    }

    function testConstructor_FromIdAssignsId() {
        $this->assertEquals(TESTING_XML_GROUP_ID, $this->group->getId());
    }

    function testGetName_FromId() {
        $this->assertEquals(TESTING_XML_GROUP_NAME, $this->group->getName());
    }

    function testGetPhotoList_ReturnsCorrectClass() {
        $result = $this->group->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
    }
    function testGetPhotoList_HasCorrectRequest() {
        $request = $this->group->getPhotoList()->getRequest();
        $this->assertEquals('flickr.groups.pools.getPhotos', $request->getMethod());
        $params = $request->getParams();
        $this->assertEquals($this->group->getId(), $params['group_id']);
    }
    function testGetPhotoList_ReturnsCorrectValues() {
        $result = $this->group->getPhotoList();
        $this->assertEquals(2, $result->getCount());
        $this->assertEquals(array('3928131','3681845'), $result->getIds());
    }
}
?>
