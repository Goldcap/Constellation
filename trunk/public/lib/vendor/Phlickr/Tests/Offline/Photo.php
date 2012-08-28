<?php

/**
* Photo Offline Tests
*
* @version $Id: Photo.php 498 2006-01-03 10:37:53Z drewish $
* @copyright 2005
*/

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/Photo.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_Photo extends PHPUnit2_Framework_TestCase {
    var $api;
    var $fromId, $fromShortXml, $fromMedXml, $fromLongXml;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        // inject the response xml into the cache...
        // ... first for the full description of the photo
        $this->api->addResponseToCache(
            Phlickr_Photo::getRequestMethodName(),
            Phlickr_Photo::getRequestMethodParams(TESTING_PHOTO_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTO_LONG . TESTING_RESP_SUFIX
        );
        // ... then size info
        $this->api->addResponseToCache(
            'flickr.photos.getSizes',
            Phlickr_Photo::getRequestMethodParams(TESTING_PHOTO_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTO_SIZES . TESTING_RESP_SUFIX
        );

        $this->fromId = new Phlickr_Photo($this->api, TESTING_PHOTO_ID);
        $this->fromShortXml = new Phlickr_Photo($this->api, simplexml_load_string(TESTING_XML_PHOTO_FROM_PHOTOSET));
        $this->fromMedXml = new Phlickr_Photo($this->api, simplexml_load_string(TESTING_XML_PHOTO_FROM_NOTINSET));
        $this->fromLongXml = new Phlickr_Photo($this->api, simplexml_load_string(TESTING_XML_PHOTO_LONG));
    }
    function tearDown() {
        unset($this->fromShortXml);
        unset($this->fromMedXml);
        unset($this->fromLongXml);
        unset($this->fromId);
        unset($this->api);
    }


    function testConstructor_AssignsApi() {
        $this->assertEquals($this->api, $this->fromId->getApi(), 'api was not assigned from id.');
        $this->assertEquals($this->api, $this->fromShortXml->getApi(), 'api was not assigned from short xml.');
        $this->assertEquals($this->api, $this->fromMedXml->getApi(), 'api was not assigned from medium xml.');
        $this->assertEquals($this->api, $this->fromLongXml->getApi(), 'api was not assigned from long xml.');
    }

    function testConstructor_AssignsId() {
        $this->assertEquals(TESTING_PHOTO_ID, $this->fromId->getId(), 'id was not assigned from id.');
        $this->assertEquals(TESTING_PHOTO_ID, $this->fromShortXml->getId(), 'id was not assigned from short xml.');
        $this->assertEquals(TESTING_PHOTO_ID, $this->fromMedXml->getId(), 'id was not assigned from medium xml.');
        $this->assertEquals(TESTING_PHOTO_ID, $this->fromLongXml->getId(), 'id was not assigned from long xml.');
    }

    function testConstructor_AssignsSecret() {
        $this->assertEquals('123456', $this->fromId->getSecret(), 'from Id failed.');
        $this->assertEquals('123456', $this->fromShortXml->getSecret(), 'from short XML failed.');
        $this->assertEquals('123456', $this->fromMedXml->getSecret(), 'from medium XML failed.');
        $this->assertEquals('123456', $this->fromLongXml->getSecret(), 'from long XML failed.');
    }

    function testConstructor_AssignsServer() {
        $this->assertEquals(12, $this->fromId->getServer());
        $this->assertEquals(12, $this->fromShortXml->getServer());
        $this->assertEquals(12, $this->fromMedXml->getServer());
        $this->assertEquals(12, $this->fromLongXml->getServer());
    }

    function testGetTags_LongXml() {
        $result = $this->fromLongXml->getTags();
        $this->assertTrue(is_array($result), 'Did not return an array.');
        $this->assertEquals(array('wooyay', 'hoopla'), $result);
    }
    function testGetTags_ShortXml() {
        $result = $this->fromShortXml->getTags();
        $this->assertTrue(is_array($result), 'Did not return an array.');
        $this->assertEquals(array('wooyay', 'hoopla'), $result);
    }

    function testGetRawTags_LongXml() {
        $result = $this->fromLongXml->getRawTags();
        $this->assertTrue(is_array($result), 'Did not return an array.');
        $this->assertEquals(array('woo yay', 'hoopla'), $result);
    }
    function testGetRawTags_ShortXml() {
        $result = $this->fromShortXml->getRawTags();
        $this->assertTrue(is_array($result), 'Did not return an array.');
        $this->assertEquals(array('woo yay', 'hoopla'), $result);
    }




    function testGetTitle_ShortXml() {
        $result = $this->fromShortXml->getTitle();
        $this->assertEquals('orford_castle_taster', $result);
    }
    function testGetTitle_fromMedXml() {
        $result = $this->fromMedXml->getTitle();
        $this->assertEquals('orford_castle_taster', $result);
    }
    function testGetTitle_LongXml() {
        $result = $this->fromLongXml->getTitle();
        $this->assertEquals('orford_castle_taster', $result);
    }

    function testGetDescription_ShortXml() {
        $this->assertEquals('hello!', $this->fromShortXml->getDescription());
    }
    function testGetDescription_MedXml() {
        $this->assertEquals('hello!', $this->fromMedXml->getDescription());
    }
    function testGetDescription_LongXml() {
        $this->assertEquals('hello!', $this->fromLongXml->getDescription());
    }

    function testGetPostedDate_ShortXml() {
        $expected = date('Y-m-d H:i:s', $this->fromShortXml->getPostedTimestamp());
        $this->assertEquals($expected, $this->fromShortXml->getPostedDate());
    }
    function testGetPostedDate_LongXml() {
        $expected = date('Y-m-d H:i:s', $this->fromLongXml->getPostedTimestamp());
        $this->assertEquals($expected, $this->fromLongXml->getPostedDate());
    }

    function testGetPostedTimestamp_ShortXml() {
        $this->assertEquals(1100897479, $this->fromShortXml->getPostedTimestamp());
    }
    function testGetPostedTimestamp_LongXml() {
        $this->assertEquals(1100897479, $this->fromLongXml->getPostedTimestamp());
    }

    function testGetTakenDate_ShortXml() {
        $this->assertEquals('2004-11-19 12:51:19', $this->fromShortXml->getTakenDate());
    }
    function testGetTakenDate_LongXml() {
        $this->assertEquals('2004-11-19 12:51:19', $this->fromLongXml->getTakenDate());
    }

    function testGetTakenTimestamp_ShortXml() {
        $expected = mktime(12, 51, 19, 11, 19, 2004);
        $actual = $this->fromShortXml->getTakenTimestamp();
        $this->assertEquals($expected, $actual);
    }
    function testGetTakenTimestamp_LongXml() {
        $expected = mktime(12, 51, 19, 11, 19, 2004);
        $actual = $this->fromLongXml->getTakenTimestamp();
        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected, $this->fromLongXml->getTakenTimestamp());
    }

    function testGetTakenGranularity_ShortXml() {
        $this->assertEquals(0, $this->fromShortXml->getTakenGranularity());
    }
    function testGetTakenGranularity_LongXml() {
        $this->assertEquals(0, $this->fromLongXml->getTakenGranularity());
    }


    function testGetTakenGranularity_4() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12">
    <dates taken="2004-11-01 00:00:00" takengranularity="4" />
</photo>
XML
));
        $expected = mktime(0, 0, 0, 11, 01, 2004);
        $this->assertEquals(4, $photo->getTakenGranularity());
        $this->assertEquals($expected, $photo->getTakenTimestamp());
    }
    function testGetTaken_Granularity6() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12">
    <dates taken="2004-01-01 00:00:00" takengranularity="6" />
</photo>
XML
));
        $expected = mktime(0, 0, 0, 1, 1, 2004);
        $this->assertEquals(6, $photo->getTakenGranularity());
        $this->assertEquals($expected, $photo->getTakenTimestamp());
    }


    function testGetUser_Id() {
        $result = $this->fromId->getUserId();
        $this->assertEquals('12037949754@N01', $result);
    }
    function testGetUser_ShortXml() {
        $result = $this->fromShortXml->getUserId();
        $this->assertEquals('12037949754@N01', $result);
    }

    function testIsForPublic_True() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12"><visibility ispublic="1" isfriend="0" isfamily="0" /></photo>
XML
));
        $this->assertTrue($photo->isForPublic());
    }
    function testIsForPublic_False() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12"><visibility ispublic="0" isfriend="1" isfamily="1" /></photo>
XML
));
        $this->assertFalse($photo->isForPublic());
    }

    function testIsForFriends_True() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12"><visibility ispublic="0" isfriend="1" isfamily="0" /></photo>
XML
));
        $this->assertTrue($photo->isForFriends());
    }
    function testIsForFriends_False() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12"><visibility ispublic="1" isfriend="0" isfamily="1" /></photo>
XML
));
        $this->assertFalse($photo->isForFriends());
    }

    function testIsForFamily_True() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12"><visibility ispublic="0" isfriend="0" isfamily="1" /></photo>
XML
));
        $this->assertTrue($photo->isForFamily());
    }
    function testIsForFamily_False() {
        $photo = new Phlickr_Photo($this->api, simplexml_load_string(<<<XML
<photo id="2733" secret="123456" server="12"><visibility ispublic="1" isfriend="1" isfamily="0" /></photo>
XML
));
        $this->assertFalse($photo->isForFamily());
    }

    function testGetSizes_Specified_Original() {
        $result = $this->fromId->getSizes(Phlickr_Photo::SIZE_ORIGINAL);
        $this->assertType('array', $result);
        //$this->assertEquals(array(112, 69, 'type'=>'jpg'), $result);
    }

    function testGetSizes() {
        $result = $this->fromId->getSizes();

        $this->assertType('array', $result);

        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_75PX, $result),
            '75 pixel square size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_100PX, $result),
            '100 pixel thumbnail size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_240PX, $result),
            '240 pixel small size was not included');
        $this->assertFalse(array_key_exists(Phlickr_Photo::SIZE_500PX, $result),
            '500 pixel medium size was included');
        $this->assertFalse(array_key_exists(Phlickr_Photo::SIZE_1024PX, $result),
            '1024 pixel large size was included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_ORIGINAL, $result),
            'original size was not included');


        $this->assertEquals(array(75, 75, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_75PX]);
        $this->assertEquals(array(100, 62, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_100PX]);
        $this->assertEquals(array(112, 69, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_240PX]);
        $this->assertFalse(isset($result[Phlickr_Photo::SIZE_500PX]));
        $this->assertFalse(isset($result[Phlickr_Photo::SIZE_1024PX]));
        $this->assertEquals(array(112, 69, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_ORIGINAL]);
    }

    function testGetSizes_SmallPng() {
        // inject the responses into the cache...
        // ...full info
        $this->api->addResponseToCache(
            Phlickr_Photo::getRequestMethodName(),
            Phlickr_Photo::getRequestMethodParams(TESTING_REAL_PHOTO_ID_PNG),
            TESTING_RESP_OK_PREFIX . <<<XML
<photo id="25002122" secret="7eb7961d3e" server="21" dateuploaded="1121032740" />
XML
            . TESTING_RESP_SUFIX
        );
        // ...then size info
        $this->api->addResponseToCache(
            'flickr.photos.getSizes',
            Phlickr_Photo::getRequestMethodParams(TESTING_REAL_PHOTO_ID_PNG),
            TESTING_RESP_OK_PREFIX . <<<XML
<sizes>
    <size label="Square" width="75" height="75"
        source="http://photos21.flickr.com/25002122_7eb7961d3e_s.jpg" />
    <size label="Thumbnail" width="100" height="62"
        source="http://photos21.flickr.com/25002122_7eb7961d3e_t.jpg"/>
    <size label="Small" width="112" height="69"
        source="http://photos21.flickr.com/25002122_7eb7961d3e_m.jpg" />
    <size label="Large" width="112" height="69"
        source="http://photos21.flickr.com/25002122_7eb7961d3e_o.png" />
</sizes>
XML
            . TESTING_RESP_SUFIX
        );

        $photo = new Phlickr_Photo($this->api, TESTING_REAL_PHOTO_ID_PNG);
        $result = $photo->getSizes();

        $this->assertType('array', $result);
        // just check the original sizing that's all that should be different
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_ORIGINAL, $result),
            'original size was not included');
        $this->assertEquals(array(112, 69, 'type'=>'png'),
            $result[Phlickr_Photo::SIZE_ORIGINAL]);
    }


    function testGetSizes_LargeJpg() {
        // inject the responses into the cache...
        // ...full info
        $this->api->addResponseToCache(
            Phlickr_Photo::getRequestMethodName(),
            Phlickr_Photo::getRequestMethodParams(24778503),
            TESTING_RESP_OK_PREFIX . <<<XML
	<photo id="24778503" secret="7263862414" server="22"/>
XML
            . TESTING_RESP_SUFIX
        );
        // ...then size info
        $this->api->addResponseToCache(
            'flickr.photos.getSizes',
            Phlickr_Photo::getRequestMethodParams(24778503),
            TESTING_RESP_OK_PREFIX . <<<XML
<sizes>
    <size label="Square" width="75" height="75"
        source="http://photos22.flickr.com/24778503_7263862414_s.jpg" />
    <size label="Thumbnail" width="100" height="66"
        source="http://photos22.flickr.com/24778503_7263862414_t.jpg" />
    <size label="Small" width="240" height="159"
        source="http://photos22.flickr.com/24778503_7263862414_m.jpg" />
    <size label="Medium" width="500" height="331"
        source="http://photos22.flickr.com/24778503_7263862414.jpg" />
    <size label="Large" width="1024" height="677"
        source="http://photos22.flickr.com/24778503_7263862414_b.jpg" />
    <size label="Original" width="1733" height="1146"
        source="http://photos22.flickr.com/24778503_7263862414_o.jpg" />
</sizes>
XML
            . TESTING_RESP_SUFIX
        );

        $photo = new Phlickr_Photo($this->api, 24778503);
        $result = $photo->getSizes();

        $this->assertType('array', $result);

        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_75PX, $result),
            '75 pixel square size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_100PX, $result),
            '100 pixel thumbnail size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_240PX, $result),
            '240 pixel small size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_500PX, $result),
            '500 pixel medium size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_1024PX, $result),
            '1024 pixel large size was not included');
        $this->assertTrue(array_key_exists(Phlickr_Photo::SIZE_ORIGINAL, $result),
            'original size was not included');


        $this->assertEquals(array(75, 75, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_75PX]);
        $this->assertEquals(array(100, 66, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_100PX]);
        $this->assertEquals(array(240, 159, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_240PX]);
        $this->assertEquals(array(500, 331, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_500PX]);
        $this->assertEquals(array(1024, 677, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_1024PX]);
        $this->assertEquals(array(1733, 1146, 'type'=>'jpg'),
            $result[Phlickr_Photo::SIZE_ORIGINAL]);
    }

    function testBuildUrl() {
        $photoId = $this->fromShortXml->getId();
        $userId = $this->fromShortXml->getUserId();
        $result = $this->fromShortXml->buildUrl();
        $this->assertEquals("http://flickr.com/photos/{$userId}/{$photoId}/", $result);
    }

    // These tests verify that the URL is created as expected. The online
    // tests verify that the URL can be retreived.
    function testBuildImgUrl_SquareSize() {
        $result = $this->fromShortXml->buildImgUrl(Phlickr_Photo::SIZE_75PX);
        $this->assertEquals('http://static.flickr.com/12/2733_123456_s.jpg', $result);
    }
    function testBuildImgUrl_ThumbSize() {
        $result = $this->fromShortXml->buildImgUrl(Phlickr_Photo::SIZE_100PX);
        $this->assertEquals('http://static.flickr.com/12/2733_123456_t.jpg', $result);
    }
    function testBuildImgUrl_SmallSize() {
        $result = $this->fromShortXml->buildImgUrl(Phlickr_Photo::SIZE_240PX);
        $this->assertEquals('http://static.flickr.com/12/2733_123456_m.jpg', $result);
    }
    function testBuildImgUrl_MediumSize() {
        $result = $this->fromShortXml->buildImgUrl(Phlickr_Photo::SIZE_500PX);
        $this->assertEquals('http://static.flickr.com/12/2733_123456.jpg', $result);
    }
    function testBuildImgUrl_LargeSize() {
        $result = $this->fromShortXml->buildImgUrl(Phlickr_Photo::SIZE_500PX);
        $this->assertEquals('http://static.flickr.com/12/2733_123456.jpg', $result);
    }
    function testBuildImgUrl_OriginalSize() {
        // add the bare minimum response specifying the original size file type
        $this->api->addResponseToCache(
            'flickr.photos.getSizes',
            Phlickr_Photo::getRequestMethodParams(TESTING_PHOTO_ID),
            TESTING_RESP_OK_PREFIX . <<<XML
<sizes>
    <size label="Original" width="1733" height="1146"
        source="http://static.flickr.com/22/24778503_7263862414_o.jpg"/>
    </sizes>
XML
            . TESTING_RESP_SUFIX
        );

        $result = $this->fromShortXml->buildImgUrl(Phlickr_Photo::SIZE_ORIGINAL);
        $this->assertEquals('http://static.flickr.com/12/2733_123456_o.jpg', $result);
    }
    function testBuildImgUrl_NoSizeSpecified() {
        $result = $this->fromShortXml->buildImgUrl();
        $this->assertEquals('http://static.flickr.com/12/2733_123456_m.jpg', $result);
    }
}

?>
