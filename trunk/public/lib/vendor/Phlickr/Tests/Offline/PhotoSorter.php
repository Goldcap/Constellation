<?php

/**
 * PhotoSorter Offline Tests
 *
 * @version $Id: PhotoSorter.php 516 2006-03-29 03:56:51Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/PhotoSorter.php';
require_once 'Phlickr/PhotosetPhotoList.php';
require_once 'Phlickr/PhotoSortStrategy/ById.php';
require_once 'Phlickr/PhotoSortStrategy/ByTitle.php';

define('PHOTOSET_ID', 534039);
define('PHOTOSET_PHOTOS', <<<XML
<photoset id="534039" primary="23180625">
    <photo id="23155946" secret="7f6672db61" server="16"
        title="spaceman and the family arrive" isprimary="0"/>
    <photo id="23155947" secret="7f6672db62" server="16"
        title="spaceman and the family arrive" isprimary="0"/>
    <photo id="23155990" secret="31f2c7d526" server="19"
        title="scotty and dee" isprimary="0"/>
    <photo id="23156036" secret="d16bc0d8a5" server="19"
        title="chad and emiko" isprimary="0"/>
    <photo id="23156085" secret="6644a877e6" server="18"
        title="running out of bike parking" isprimary="0"/>
    <photo id="23156143" secret="95ff511ab9" server="17"
        title="billy, chris and billy" isprimary="0"/>
</photoset>
XML
);


class Phlickr_Tests_Offline_PhotoSorter extends PHPUnit2_Framework_TestCase {
    var $api, $psl;
    var $stratId, $stratTitle;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        $this->stratId = new Phlickr_PhotoSortStrategy_ById();
        $this->stratTitle = new Phlickr_PhotoSortStrategy_ByTitle();

        // create a request
        $this->request = $this->api->createRequest(
            Phlickr_PhotosetPhotoList::getRequestMethodName(),
            Phlickr_PhotosetPhotoList::getRequestMethodParams(PHOTOSET_ID)
        );
        // then make sure that there's data in the cache to fufill it
        $this->api->addResponseToCache(
            Phlickr_PhotosetPhotoList::getRequestMethodName(),
            Phlickr_PhotosetPhotoList::getRequestMethodParams(PHOTOSET_ID),
            TESTING_RESP_OK_PREFIX . PHOTOSET_PHOTOS . TESTING_RESP_SUFIX
        );
        // an IPhotoList
        $this->psl = new Phlickr_PhotosetPhotoList($this->request);
    }
    function tearDown() {
        unset($this->stratTitle);
        unset($this->stratId);
        unset($this->psl);
        unset($this->api);
    }

    function testConstructor_Default() {
        $sorter = new Phlickr_PhotoSorter($this->stratId);

        $this->assertNotNull($sorter);
        $this->assertType('Phlickr_PhotoSorter', $sorter);
        $this->assertFalse($sorter->isInReverse(),
            'isInReverse() was not set correctly');
    }
    function testConstructor_InReverse() {
        $sorter = new Phlickr_PhotoSorter($this->stratId, true);

        $this->assertNotNull($sorter);
        $this->assertType('Phlickr_PhotoSorter', $sorter);
        $this->assertTrue($sorter->isInReverse(),
            'isInReverse() was not set correctly');
    }

    function testIdsFromPhotos() {
        $photos = $this->psl->getPhotos();
        $ids = Phlickr_PhotoSorter::idsFromPhotos($photos);

        $this->assertEquals(array('23155946','23155947','23155990','23156036','23156085','23156143'),
            $ids);
    }

    function testSortById() {
        $count = $this->psl->getCount();
        $sorter = new Phlickr_PhotoSorter($this->stratId);

        $photos = $sorter->sort($this->psl);
        $this->assertType('array', $photos);
        $this->assertEquals($count, count($photos));

        $ids = Phlickr_PhotoSorter::idsFromPhotos($photos);
        $this->assertEquals(array('23155946','23155947','23155990','23156036','23156085','23156143'),
           $ids);
    }

    function testSortById_InReverse() {
        $count = $this->psl->getCount();
        $sorter = new Phlickr_PhotoSorter($this->stratId, true);

        $photos = $sorter->sort($this->psl);
        $this->assertType('array', $photos);
        $this->assertEquals($count, count($photos));

        $ids = Phlickr_PhotoSorter::idsFromPhotos($photos);
        $this->assertEquals(array('23156143','23156085','23156036','23155990','23155947','23155946'),
            $ids);
    }

    function testSortByTitle() {
        $count = $this->psl->getCount();
        $sorter = new Phlickr_PhotoSorter($this->stratTitle);

        $photos = $sorter->sort($this->psl);
        $this->assertType('array', $photos);
        $this->assertEquals($count, count($photos));

        $ids = Phlickr_PhotoSorter::idsFromPhotos($photos);
        $this->assertEquals(array('23156143','23156036','23156085','23155990','23155947','23155946'),
           $ids);
    }

    function testSortByTitle_InReverse() {
        $count = $this->psl->getCount();
        $sorter = new Phlickr_PhotoSorter($this->stratTitle, true);

        $photos = $sorter->sort($this->psl);
        $this->assertType('array', $photos);
        $this->assertEquals($count, count($photos));

        $ids = Phlickr_PhotoSorter::idsFromPhotos($photos);
        $this->assertEquals(array('23155947','23155946','23155990','23156085','23156036','23156143'),
            $ids);
    }
}

