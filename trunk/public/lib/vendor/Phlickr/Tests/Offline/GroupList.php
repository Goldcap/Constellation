<?php

/**
 * GroupList Offline Tests
 *
 * @version $Id: GroupList.php 357 2005-07-19 00:33:56Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/GroupList.php';
require_once 'Phlickr/Tests/Mocks/Request.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_GroupList extends PHPUnit2_Framework_TestCase {
    var $api;
    var $requestPublic, $groupsPublic, $idsPublic;
    var $requestPrivate, $groupsPrivate, $idsPrivate;

    function setUp() {
	$this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        $this->api->setEndpointUrl('http://example.com');

        $this->requestPublic = new Phlickr_Tests_Mocks_Request($this->api, 'GROUP SEARCH',
	    TESTING_RESP_OK_PREFIX . TESTING_XML_GROUPS_PUBLIC . TESTING_RESP_SUFIX
	);
	$this->groupsPublic = new Phlickr_GroupList($this->requestPublic);
	$this->idsPublic = array('97544914@N00');

        $this->requestPrivate = new Phlickr_Tests_Mocks_Request($this->api, 'GROUP SEARCH',
	    TESTING_RESP_OK_PREFIX . TESTING_XML_GROUPS_PRIVATE . TESTING_RESP_SUFIX
	);
	$this->groupsPrivate = new Phlickr_GroupList($this->requestPrivate);
	$this->idsPrivate = array('97544914@N00','84636767@N00');
    }
    function tearDown() {
	unset($this->idsPublic);
	unset($this->groupsPublic);
	unset($this->requestPublic);
	unset($this->idsPrivate);
        unset($this->groupsPrivate);
        unset($this->requestPrivate);
        unset($this->api);
    }


    function testConstructor_AssignsRequest() {
        $this->assertSame($this->requestPublic, $this->groupsPublic->getRequest());
	$this->assertSame($this->requestPrivate, $this->groupsPrivate->getRequest());
    }


    function testGetCount() {
        $this->assertEquals(count($this->idsPrivate), $this->groupsPrivate->getCount(), 'private did not return correct count.');
	$this->assertEquals(count($this->idsPublic), $this->groupsPublic->getCount(), 'public did not return correct count.');
    }

    function testGetIds_ReturnsArray() {
        $this->assertTrue(is_array($this->groupsPublic->getIds()), 'public did not return an array. ');
	$this->assertTrue(is_array($this->groupsPrivate->getIds()), 'private did not return an array. ');
    }
    function testGetIds_ReturnsCorrectIds() {
        $this->assertEquals($this->idsPublic, $this->groupsPublic->getIds(), 'public did not return correct ids.');
	$this->assertEquals($this->idsPrivate, $this->groupsPrivate->getIds(), 'private did not return correct ids.');
    }


    function testGetGroups_ReturnsArrayOfGroups() {
	$result = $this->groupsPublic->getGroups();
	$this->assertTrue(is_array($result), 'did not return an array. ');
	foreach ($result as $r) {
	    $this->assertType('Phlickr_Group', $r);
	}
    }
    function testGetGroups_ReturnsCorrectGroups() {
	$result = $this->groupsPublic->getGroups();
	for ($i = 0; $i < $this->groupsPublic->getCount(); $i++) {
	    $this->assertEquals($this->idsPublic[$i], $result[$i]->getId());
	}
    }
}

?>
