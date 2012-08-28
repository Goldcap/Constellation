<?php

/**
 * Group Online Tests
 *
 * @version $Id: Group.php 358 2005-07-19 00:38:59Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Group.php';
require_once 'Phlickr/Tests/constants.inc';


class Phlickr_Tests_Online_Group extends PHPUnit2_Framework_TestCase {
    var $api;
    var $group;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->group = new Phlickr_Group($this->api, TESTING_XML_GROUP_ID);
    }
    function tearDown() {
        unset($this->group);
        unset($this->api);
    }

    function testFindByUrl_IdUrl() {
        $result = Phlickr_Group::findByUrl($this->api, 'http://flickr.com/groups/84636767@N00/');
        $this->assertType('Phlickr_Group', $result);
        $this->assertEquals('84636767@N00', $result->getId());
    }
    function testFindByUrl_NamedUrl() {
        $result = Phlickr_Group::findByUrl($this->api, 'http://flickr.com/groups/infrastructure/');
        $this->assertType('Phlickr_Group', $result);
        $this->assertEquals('97544914@N00', $result->getId());
    }
    function testFindByUrl_NamedPoolUrl() {
        $result = Phlickr_Group::findByUrl($this->api, 'http://flickr.com/groups/infrastructure/pool/');
        $this->assertType('Phlickr_Group', $result);
        $this->assertEquals('97544914@N00', $result->getId());
    }
    function testFindByUrl_InvalidThrows() {
        try {
            $result = Phlickr_Group::findByUrl($this->api, 'http://flickr.com/groups/SOMETHING_THAT_IS_NOT_REAL/');
        } catch (Phlickr_MethodFailureException $e) {
            return;
        }
        $this->fail("An exception should have been thrown.");
    }


    function testGetPhotoList() {
        $result = $this->group->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
        $this->assertEquals('flickr.groups.pools.getPhotos', $result->getRequest()->getMethod());
    }
}

?>
