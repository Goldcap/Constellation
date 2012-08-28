<?php

/**
 * Offline Import Makethumbs Tests
 *
 * @version $Id: Gallery.php 473 2005-09-29 03:08:19Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/Import/Gallery.php';


class Phlickr_Tests_Offline_Import_Gallery extends PHPUnit2_Framework_TestCase {
    /**
     * @var Phlickr_Import_Makethumbs
     */
    private $batch;
    /**
     * Gallery directory
     * @var string
     */
    private $dirGallery;
    /**
     * Albums directory
     * @var string
     */
    private $dirAlbums;
    /**
     * Album name
     * @var string
     */
    private $albumName;

    function setUp() {
        $this->albumName = 'gernert_homes_ceremony';
        $this->dirGallery = realpath('D:\sf\phlickr\Phlickr\Tests\Offline\Import\gallery');
        $this->dirAlbums = realpath('D:\sf\phlickr\Phlickr\Tests\Offline\Import\albums');
        $this->dirAlbum = $dir = $this->dirAlbums . DIRECTORY_SEPARATOR . $this->albumName . DIRECTORY_SEPARATOR;
        $this->batch = new Phlickr_Import_Gallery($this->dirGallery, $this->albumName);
    }

    function tearDown() {
        unset($this->albumName);
        unset($this->dirAlbums);
        unset($this->dirGallery);
        unset($this->albumName);
        unset($this->batch);
    }


    function testConstructor_BadGalleryDir() {
        try {
            $batch = new Phlickr_Import_Gallery('/tmp', $this->albumName);
        } catch (Exception $ex) {
            return;
        }
        $this->fail('An exception should have been thrown.');
    }
    function testConstructor_BadAlbumName() {
        try {
            $batch = new Phlickr_Import_Gallery($this->dirGallery, 'MISSING');
        } catch (Exception $ex) {
            return;
        }
        $this->fail('An exception should have been thrown.');
    }

    function testSplitKeywords_Empty() {
        $actual = $this->batch->splitKeywords('');
        $expected = array();
        $this->assertEquals($expected, $actual);
    }
    function testSplitKeywords_SpaceSeparators() {
        $actual = $this->batch->splitKeywords('something or another');
        $expected = array('something', 'or', 'another');
        $this->assertEquals($expected, $actual);
    }
    function testSplitKeywords_CommaSeparators() {
        $actual = $this->batch->splitKeywords('some thing,or,a nother');
        $expected = array('some thing', 'or', 'a nother');
        $this->assertEquals($expected, $actual);
    }
    function testSplitKeywords_QuotedSpaces1() {
        $actual = $this->batch->splitKeywords('"some thing" or "a" nother thing duha');
        $expected = array('some thing', 'or', 'a', 'nother', 'thing', 'duha');
        $this->assertEquals($expected, $actual);
    }
    function testSplitKeywords_QuotedSpaces2() {
        $actual = $this->batch->splitKeywords('different " multi word" with "single"');
        $expected = array('different', ' multi word', 'with', 'single');
        //var_dump($actual);
        $this->assertEquals($expected, $actual);
    }


    function testIsSetWanted() {
        $result = $this->batch->isSetWanted();
        $this->assertTrue($result);
    }

    function testGetSetName() {
        $result = $this->batch->getSetTitle();
        $this->assertEquals('Gernert Homes Ceremony', $result);
    }

    function testGetSetDescription() {
        $result = $this->batch->getSetDescription();
        $this->assertEquals('A reception at Gernert Homes, the elderly public housing complex, where DeFord lived in early 1974. David Morton worked with Housing Authority staff to organize this event to coincide with the publication of an article on DeFord in Nashville magazine.', $result);
    }

    function testGetSetPrimary() {
        $expected = $this->dirAlbum . 'party_1_deford_family.jpg';
        $result = $this->batch->getSetPrimary();
        $this->assertEquals($expected, $result);
    }

    function testGetFiles() {
        $dir = $this->dirAlbum;
        $expected = array(
            $dir . 'party_1_deford_family.jpg',
            $dir . 'party_2_deford_family.jpg',
            $dir . 'party_3_deford_family.jpg',
        );
        $result = $this->batch->getFiles();
        $this->assertEquals($expected, $result);
    }

    function testGetTitleForFile() {
        $dir = $this->dirAlbum;

        $result = $this->batch->getTitleForFile($dir . 'party_1_deford_family.jpg');
        $this->assertEquals('Reception honoring DeFord (DeFord Family)', $result);

        $result = $this->batch->getTitleForFile($dir . 'party_2_deford_family.jpg');
        $this->assertEquals('Dezoral Thomas, Chris Craig, DeFord, DeFord Jr, David Morton at reception honoring DeFord (DeFord Family)', $result);

        $result = $this->batch->getTitleForFile($dir . 'party_3_deford_family.jpg');
        $this->assertEquals('Reception honoring DeFord (DeFord Family)', $result);
    }

    function testGetDescriptionForFile() {
        $dir = $this->dirAlbum;

        $result = $this->batch->getDescriptionForFile($dir . 'party_1_deford_family.jpg');
        $this->assertEquals('', $result);

        $result = $this->batch->getDescriptionForFile($dir . 'party_2_deford_family.jpg');
        $this->assertEquals('', $result);

        $result = $this->batch->getDescriptionForFile($dir . 'party_3_deford_family.jpg');
        $this->assertEquals('', $result);
    }

    function testGetTagsForFile() {
        $dir = $this->dirAlbum;

        $expected = array('DeFord', 'black&white');
        $result = $this->batch->getTagsForFile($dir . 'party_1_deford_family.jpg');
        $this->assertEquals($expected, $result);

        $expected = array('DeFord', 'black&white', 'family');
        $result = $this->batch->getTagsForFile($dir . 'party_2_deford_family.jpg');
        $this->assertEquals($expected, $result);

        $expected = array('DeFord', 'black&white', 'harp', 'playing');
        $result = $this->batch->getTagsForFile($dir . 'party_3_deford_family.jpg');
        $this->assertEquals($expected, $result);
    }

    function testGetDatesForFile() {
        $dir = $this->dirAlbum;

        $expected = 1046930120;
        $result = $this->batch->getTakenDateForFile($dir . 'party_1_deford_family.jpg');
        $this->assertEquals($expected, $result);

        $expected = 1046930094;
        $result = $this->batch->getTakenDateForFile($dir . 'party_2_deford_family.jpg');
        $this->assertEquals($expected, $result);

        $expected = 1046930076;
        $result = $this->batch->getTakenDateForFile($dir . 'party_3_deford_family.jpg');
        $this->assertEquals($expected, $result);
    }
}

?>
