<?php

/**
 * Photo Online Tests
 *
 * @version $Id: Photo.php 523 2006-08-28 18:30:20Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Photo.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Online_Photo extends PHPUnit2_Framework_TestCase {
    var $api;
    var $photo;
    var $filepath;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->photo = new Phlickr_Photo($this->api, TESTING_REAL_PHOTO_ID_JPG);
        $this->filepath = tempnam('/tmp', 'image');
    }
    function tearDown() {
        unset($this->photo);
        unset($this->api);
        if (file_exists($this->filepath))
            unlink($this->filepath);
        unset($this->filepath);
    }


    function testAddTags_UsingCleanTags() {
        $this->photo->addTags(array('newtag'));
        sleep(1);
        $result = $this->photo->getTags();
        $this->assertContains('newtag', $result);
    }
    function testAddTags_UsingMessyTags() {
        $this->photo->addTags(array('a New-ish Tag!'));
        sleep(1);
        $result = $this->photo->getTags();
        $this->assertContains('anewishtag', $result);
    }


    function testRemoveTag_UsingCleanTags() {
        // this isn't an authed oubject so work around the lack of setTags()
        foreach ($this->photo->getTags() as $tag) {
            $this->photo->removeTag($tag);
        }
        $this->photo->addTags(array('something', 'another', '2004'));
        sleep(1);

        $this->photo->removeTag('another');

        $result = $this->photo->getTags();
        $this->assertEquals(array('something', '2004'), $result);
    }
    function testRemoveTag_UsingMessyTags() {
        // this isn't an authed oubject so work around the lack of setTags()
        foreach ($this->photo->getTags() as $tag) {
            $this->photo->removeTag($tag);
        }
        $this->photo->addTags(array('some thing!', 'a-nother.', '2004'));
        sleep(1);

        $this->photo->removeTag('another');

        $result = $this->photo->getTags();
        $this->assertEquals(array('something', '2004'), $result);
    }


    function testBuildImgUrl_SquareSize() {
        $url = $this->photo->buildImgUrl(Phlickr_Photo::SIZE_75PX);
        $file = file($url);
        $this->assertTrue($file != FALSE, 'Could not retrieve the image.');
    }
    function testBuildImgUrl_ThumbSize() {
        $url = $this->photo->buildImgUrl(Phlickr_Photo::SIZE_100PX);
        $file = file($url);
        $this->assertTrue($file != FALSE, 'Could not retrieve the image.');
    }
    function testBuildImgUrl_SmallSize() {
        $url = $this->photo->buildImgUrl(Phlickr_Photo::SIZE_240PX);
        $file = file($url);
        $this->assertTrue($file != FALSE, 'Could not retrieve the image.');
    }
    function testBuildImgUrl_MediumSize() {
        $url = $this->photo->buildImgUrl(Phlickr_Photo::SIZE_500PX);
        $file = file($url);
        $this->assertTrue($file != FALSE, 'Could not retrieve the image.');
    }
    function testBuildImgUrl_LargeSize() {
        $url = $this->photo->buildImgUrl(Phlickr_Photo::SIZE_1024PX);
        $file = file($url);
        $this->assertTrue($file != FALSE, 'Could not retrieve the image.');
    }
    function testBuildImgUrl_OriginalSize() {
        $url = $this->photo->buildImgUrl(Phlickr_Photo::SIZE_ORIGINAL);
        $file = file($url);
        $this->assertTrue($file != FALSE, 'Could not retrieve the image.');
    }


    function testSaveAs_CompareMyUploadSample() {
        $this->photo->saveAs($this->filepath, Phlickr_Photo::SIZE_ORIGINAL);

        $this->assertTrue(file_exists($this->filepath));
        $this->assertEquals(filesize(TESTING_FILE_NAME_JPG), filesize($this->filepath));

        $sizes = getimagesize($this->filepath);
        $this->assertEquals(112, $sizes[0], 'incorrect width');
        $this->assertEquals(69, $sizes[1], 'incorrect height');
    }
    function testSaveAs_DefaultSize() {
        $photo = new Phlickr_Photo($this->api, '7836107');
        $photo->saveAs($this->filepath);
        $this->assertTrue(file_exists($this->filepath));

        $sizes = getimagesize($this->filepath);
        $this->assertEquals(180, $sizes[0], 'incorrect width');
        $this->assertEquals(240, $sizes[1], 'incorrect height');
    }
    function testSaveAs_OriginalSize() {
        $photo = new Phlickr_Photo($this->api, '7836107');
        $photo->saveAs($this->filepath, Phlickr_Photo::SIZE_ORIGINAL);
        $this->assertTrue(file_exists($this->filepath));

        $sizes = getimagesize($this->filepath);
        $this->assertEquals(1536, $sizes[0], 'incorrect width');
        $this->assertEquals(2048, $sizes[1], 'incorrect height');
    }
}

