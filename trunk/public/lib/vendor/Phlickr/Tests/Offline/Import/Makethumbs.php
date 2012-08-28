<?php

/**
 * Offline Import Makethumbs Tests
 *
 * @version $Id: Makethumbs.php 473 2005-09-29 03:08:19Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/Import/Makethumbs.php';


define('DESCRIPTIONS_TXT', <<<SAMPLE
[short title]  (best to avoid HTML markup here, keep it short, <br>s are OK)
Bishop 2002.04.19-21

[longer page description]  (all the HTML you want, as many lines as you want)
Dan and I went down to Bishop to do a little climbing at the gorge and Happy boulders.

[captions]  (best to avoid HTML markup here, keep it short, <br>s are OK)

DSC01613.jpg Dan in front of Mona Lake
DSC01614.jpg Me this time
DSC01615.jpg Schats Bakery

[descriptions]  (all the HTML you want, but each file on just one line)

DSC01613.jpg
DSC01614.jpg
DSC01615.jpg Note the eccentric guy sitting near dan with his eccentric bike.

SAMPLE
);

class Phlickr_Tests_Offline_Import_Makethumbs extends PHPUnit2_Framework_TestCase {
    /**
     * @var string
     */
    private $dir;
    /**
     * @var Phlickr_Import_Makethumbs
     */
    private $batch;

    function setUp() {
        file_put_contents(Phlickr_Import_Makethumbs::FILE_DESCRIPTIONS, DESCRIPTIONS_TXT);
        $this->dir = realpath('.') . DIRECTORY_SEPARATOR;
        $this->batch = new Phlickr_Import_Makethumbs($this->dir);
    }

    function tearDown() {
        unlink(Phlickr_Import_Makethumbs::FILE_DESCRIPTIONS);
        unset($this->dir);
        unset($this->batch);
    }

    function testConstructor_BadDirectory() {
        try {
            $batch = new Phlickr_Import_Makethumbs('/tmp');
        } catch (Exception $ex) {
            return;
        }
        $this->fail('An exception should have been thrown.');
    }

    function testIsSetWanted() {
        $result = $this->batch->isSetWanted();
        $this->assertTrue($result);
    }

    function testGetSetName() {
        $result = $this->batch->getSetTitle();
        $this->assertEquals('Bishop 2002.04.19-21', $result);
    }

    function testGetSetDescription() {
        $result = $this->batch->getSetDescription();
        $this->assertEquals('Dan and I went down to Bishop to do a little climbing at the gorge and Happy boulders.', $result);
    }

    function testGetSetPrimary() {
        $result = $this->batch->getSetPrimary();
        $this->assertNull($result);
    }

    function testGetFiles() {
        $expected = array(
            $this->dir . 'DSC01613.jpg',
            $this->dir . 'DSC01614.jpg',
            $this->dir . 'DSC01615.jpg',
        );
        $result = $this->batch->getFiles();
        $this->assertEquals($expected, $result);
    }

    function testGetTitleForFile() {
        $result = $this->batch->getTitleForFile($this->dir . 'DSC01613.jpg');
        $this->assertEquals('Dan in front of Mona Lake', $result);

        $result = $this->batch->getTitleForFile($this->dir . 'DSC01614.jpg');
        $this->assertEquals('Me this time', $result);

        $result = $this->batch->getTitleForFile($this->dir . 'DSC01615.jpg');
        $this->assertEquals('Schats Bakery', $result);
    }

    function testGetDescriptionForFile() {
        $result = $this->batch->getDescriptionForFile($this->dir . 'DSC01613.jpg');
        $this->assertEquals('', $result);

        $result = $this->batch->getDescriptionForFile($this->dir . 'DSC01614.jpg');
        $this->assertEquals('', $result);

        $result = $this->batch->getDescriptionForFile($this->dir . 'DSC01615.jpg');
        $this->assertEquals('Note the eccentric guy sitting near dan with his eccentric bike.', $result);
    }

    function testGetTagsFromFile_NoneSpecified() {
        $expected = array();
        $result = $this->batch->getTagsForFile($this->dir . 'DSC01613.jpg');
        $this->assertEquals($expected, $result);

        $result = $this->batch->getTagsForFile($this->dir . 'DSC01614.jpg');
        $this->assertEquals($expected, $result);

        $result = $this->batch->getTagsForFile($this->dir . 'DSC01615.jpg');
        $this->assertEquals($expected, $result);

        $result = $this->batch->getTagsForFile($this->dir . 'MADE_UP_NAME.JPG');
        $this->assertEquals($expected, $result);
    }
}

?>
