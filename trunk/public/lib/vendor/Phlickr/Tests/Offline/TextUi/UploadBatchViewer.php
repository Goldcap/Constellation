<?php

/**
 * Offline TextUi UploadBatchViewer Tests
 *
 * @version $Id: UploadBatchViewer.php 520 2006-04-24 06:11:53Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Tests/constants.inc';

require_once 'Phlickr/TextUi/UploadBatchViewer.php';


class MockUploadBatch implements Phlickr_Framework_IUploadBatch {
    public $files, $titles, $descs, $tags, $dates, $setwanted, $settitle, $setdesc, $setprimary;
    function getFiles() { return (array) $this->files; }

    function isSetWanted() { return $this->setwanted; }

    function getSetTitle() { return $this->settitle; }

    function getSetDescription() { return $this->setdesc; }

    function getSetPrimary() { return $this->setprimary; }

    function getTitleForFile($filePath)
    { return (string) $this->titles[$filePath]; }

    function getDescriptionForFile($filePath)
    { return (string) $this->descs[$filePath]; }

    function getTagsForFile($filePath)
    { return (array) $this->tags[$filePath]; }

    function getTakenDateForFile($filePath)
    { return $this->dates[$filePath]; }
}

class Phlickr_Tests_Offline_TextUi_UploadBatchViewer extends PHPUnit2_Framework_TestCase {
    protected $batch, $viewer, $fileName, $file;

    function setUp() {
        $this->fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output.txt';
        $this->file = fopen($this->fileName, 'w+');
        $this->batch = new MockUploadBatch();
        $this->viewer = new Phlickr_TextUi_UploadBatchViewer($this->batch, $this->file);
    }

    function tearDown() {
        fclose($this->file);
        unlink($this->fileName);

        unset($this->file);
        unset($this->fileName);
        unset($this->viewer);
    }

    function testConstructor() {
        $this->assertNotNull($this->viewer);
    }

    function testGetBatch() {
        $this->assertSame($this->batch, $this->viewer->getBatch());
    }


    function testViewFiles_EmptyList() {
        $this->batch->files = array();
        $this->viewer->viewFiles();

        $expected = "There are no files in this batch.\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewFiles_OnlyNames() {
        $this->batch->files = array('foo', 'bar');
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\n\n";
        $expected .= "bar\n\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewFiles_Titles() {
        $this->batch->files = array('foo', 'bar');
        $this->batch->titles = array('foo'=>'title', 'bar'=>'pork');
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\nTitle:       title\n\n";
        $expected .= "bar\nTitle:       pork\n\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewFiles_Descriptions() {
        $this->batch->files = array('foo', 'bar');
        $this->batch->descs = array('foo'=>"a long\nrambler", 'bar'=>'Nothing to see ehre');
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\nDescription: a long\nrambler\n\n";
        $expected .= "bar\nDescription: Nothing to see ehre\n\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewFiles_DatesTimestamp() {
        $now = time();
        $this->batch->files = array('foo', 'bar');
        $this->batch->dates = array('foo'=>$now, 'bar'=>$now);
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\nTaken Date:  " . date('Y-m-d H:i:s', $now) . "\n\n";
        $expected .= "bar\nTaken Date:  " . date('Y-m-d H:i:s', $now) . "\n\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }
    function testViewFiles_DatesString() {
        $now = time();
        $this->batch->files = array('foo', 'bar');
        $this->batch->dates = array('foo'=>'2005-09-28 19:55:27', 'bar'=>'2005-09-28 19:56:23');
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\nTaken Date:  2005-09-28 19:55:27\n\n";
        $expected .= "bar\nTaken Date:  2005-09-28 19:56:23\n\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewFiles_Tags() {
        $this->batch->files = array('foo', 'bar');
        $this->batch->tags = array('foo'=>array('some','for','me'), 'bar'=>array('not','for','you'));
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\nTags:        some, for, me\n\n";
        $expected .= "bar\nTags:        not, for, you\n\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewFiles_AllTogetherNow() {
        $now = time();
        $this->batch->files = array('foo');
        $this->batch->titles = array('foo'=>'title');
        $this->batch->tags = array('foo'=>array('some','for','me'));
        $this->batch->dates = array('foo'=>'2005-09-28 19:55:27');
        $this->batch->descs = array('foo'=>"a long\nrambler");
        $this->viewer->viewFiles();

        $expected = "Files:\n";
        $expected .= "foo\n";
        $expected .= "Title:       title\n";
        $expected .= "Tags:        some, for, me\n";
        $expected .= "Taken Date:  2005-09-28 19:55:27\n";
        $expected .= "Description: a long\nrambler\n";
        $expected .= "\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    function testViewSet_NoSet() {
        $this->batch->setwanted = false;
        $this->viewer->viewSet();

        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals("No photoset will be created.\n", $result);
    }
    function testViewSet_SetWithTitle() {
        $this->batch->setwanted = true;
        $this->batch->settitle = $title = "A nice title";
        $this->viewer->viewSet();

        $expected = "A photoset titled '$title' will be created. \n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }
    function testViewSet_SetWithAndDesc() {
        $this->batch->setwanted = true;
        $this->batch->settitle = $title = "A nice title";
        $this->batch->setdesc = $desc = "such a long description...";
        $this->viewer->viewSet();

        $expected = "A photoset titled '$title' will be created. Its description will be:\n$desc\n";
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }
}
