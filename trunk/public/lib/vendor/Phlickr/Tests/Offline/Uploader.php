<?php

/**
 * Uploader Offline tests
 *
 * @version $Id: Uploader.php 462 2005-09-27 02:48:28Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';
require_once 'Phlickr/Uploader.php';

class Phlickr_Tests_Offline_Uploader extends PHPUnit2_Framework_TestCase {
    var $api;
    var $uploader;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->uploader = new Phlickr_Uploader($this->api);
    }
    function tearDown() {
        unset($this->uploader);
        unset($this->api);
    }

    function testConstructor_AssignsPerms() {
        $this->assertTrue($this->uploader->isForPublic());
        $this->assertTrue($this->uploader->isForFriends());
        $this->assertTrue($this->uploader->isForFamily());
    }

    function testConstructor_AssignsTags() {
        $this->assertEquals(array(), $this->uploader->getTags());
    }


    function testSetPerms_ForPublic() {
        $this->uploader->setPerms(true, false, false);
        $this->assertTrue($this->uploader->isForPublic());

        $this->uploader->setPerms(false, true, true);
        $this->assertFalse($this->uploader->isForPublic());
    }
    function testSetPerms_ForFriends() {
        $this->uploader->setPerms(false, true, false);
        $this->assertTrue($this->uploader->isForFriends());

        $this->uploader->setPerms(true, false, true);
        $this->assertFalse($this->uploader->isForFriends());
    }
    function testSetPerms_ForFamily() {
        $this->uploader->setPerms(false, false, true);
        $this->assertTrue($this->uploader->isForFamily());

        $this->uploader->setPerms(true, true, false);
        $this->assertFalse($this->uploader->isForFamily());
    }
    function testSetPerms_NotBooleans() {
        $this->uploader->setPerms(2, 22, 'true');
        $this->assertTrue($this->uploader->isForPublic());
        $this->assertTrue($this->uploader->isForFriends());
        $this->assertTrue($this->uploader->isForFamily());

        $this->uploader->setPerms(null, '', 0);
        $this->assertFalse($this->uploader->isForPublic());
        $this->assertFalse($this->uploader->isForFriends());
        $this->assertFalse($this->uploader->isForFamily());
    }


    function testSetTags_EmptyArray() {
        $this->uploader->setTags(array());
        $this->assertEquals(array(), $this->uploader->getTags());
    }
    function testSetTags_SingleTag() {
        $tags = array('tag');
        $this->uploader->setTags($tags);
        $this->assertEquals($tags, $this->uploader->getTags());
    }
    function testSetTags_MultipleTag() {
        $tags = array('tag', 'some other tag');
        $this->uploader->setTags($tags);
        $this->assertEquals($tags, $this->uploader->getTags());
    }


    function testUpload_MissingFileThrows() {
        try {
            $this->uploader->Upload(TESTING_FILE_NAME_MISSING);
        } catch (Phlickr_Exception $ex) {
            return;
        } catch (Exception $ex) {
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->fail('an exception should have been thrown');
    }

    function testBuildEditUrl() {
        $result = Phlickr_Uploader::buildEditUrl(array(1,2,3));
        $this->assertEquals('http://www.flickr.com/tools/uploader_edit.gne?ids=1,2,3', $result);
    }
}
?>
