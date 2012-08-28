<?php

/**
 * User Offline Tests
 *
 * @version $Id: User.php 511 2006-02-05 03:45:21Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/User.php';
require_once 'Phlickr/Tests/constants.inc';


class Phlickr_Tests_Offline_User extends PHPUnit2_Framework_TestCase {
    var $api;
    var $fromShortXml, $fromLongXml;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        // inject the response xml into the cache...
        // ... first for the full description of the user
        $this->api->addResponseToCache(
            Phlickr_User::getRequestMethodName(),
            Phlickr_User::getRequestMethodParams(TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USER_LONG . TESTING_RESP_SUFIX
        );
        // ... then for public photos
        $this->api->addResponseToCache(
            'flickr.people.getPublicPhotos',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => Phlickr_PhotoList::PER_PAGE_DEFAULT
            ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOS . TESTING_RESP_SUFIX
        );


        $this->fromShortXml = new Phlickr_User($this->api, simplexml_load_string(TESTING_XML_USER_SHORT));
        $this->fromLongXml = new Phlickr_User($this->api, simplexml_load_string(TESTING_XML_USER_LONG));
    }
    function tearDown() {
        unset($this->api);
        unset($this->fromShortXml);
        unset($this->fromLongXml);
    }

    function testConstructor_FromIdConnectsAssignsId() {
        $fromId = new Phlickr_User($this->api, TESTING_USER_ID);
        $this->assertEquals($this->api, $fromId->getApi());
        $this->assertEquals(TESTING_XML_USER_ID, $fromId->getId());
    }
    function testConstructor_FromShortXmlAssignsApi() {
        $this->assertEquals($this->api, $this->fromShortXml->getApi());
    }
    function testConstructor_FromLongXmlAssignsApi() {
        $this->assertEquals($this->api, $this->fromLongXml->getApi());
    }
    function testConstructor_FromShortXmlAssignsId() {
        $this->assertEquals(TESTING_XML_USER_ID, $this->fromShortXml->getId());
    }
    function testConstructor_FromLongXmlAssignsId() {
        $this->assertEquals(TESTING_XML_USER_ID, $this->fromLongXml->getId());
    }

    function testConstructor_FromShortXmlAssignsName() {
        $this->assertEquals('just testing', $this->fromShortXml->getName(),
            'Constructor did not set the name.');
    }
    function testConstructor_FromLongXmlAssignsName() {
        $this->assertEquals('just testing', $this->fromLongXml->getName(),
            'Constructor did not set the name.');
    }

    function testConstructor_FromShortXmlAssignsPhotoCount() {
        $this->assertEquals(36, $this->fromLongXml->getPhotoCount());
    }
    function testConstructor_FromLongXmlAssignsPhotoCount() {
        $this->assertEquals(36, $this->fromLongXml->getPhotoCount());
    }

    function testConstructor_FromShortXmlAssignsLocation() {
        $this->assertEquals('some place, some country', $this->fromShortXml->getLocation(),
            'Constructor did not set the location.');
    }
    function testConstructor_FromLongXmlAssignsLocation() {
        $this->assertEquals('some place, some country', $this->fromLongXml->getLocation(),
            'Constructor did not set the location.');
    }

    function testConstructor_FromShortXmlAssignsRealname() {
        $this->assertEquals('phlickr test account', $this->fromShortXml->getRealname(),
            'Constructor did not set the realname.');
    }
    function testConstructor_FromLongXmlAssignsRealname() {
        $this->assertEquals('phlickr test account', $this->fromLongXml->getRealname(),
            'Constructor did not set the realname.');
    }


    function testGetContactUserList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.contacts.getPublicList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_PUBLIC_CONTACTS . TESTING_RESP_SUFIX
        );

        $result = $this->fromLongXml->getContactUserList();
        $this->assertType('Phlickr_UserList', $result);
    }
    function testGetContactUserList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.contacts.getPublicList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_PUBLIC_CONTACTS . TESTING_RESP_SUFIX
        );

        $result = $this->fromLongXml->getContactUserList();
        $this->assertEquals(3, $result->getCount());
        $this->assertEquals(
            array('12037949629@N01', '12037949631@N01', '41578656547@N01'),
            $result->getIds());
    }


    function testGetFavoritePhotoList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.favorites.getPublicList',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => Phlickr_PhotoList::PER_PAGE_DEFAULT
            ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_FAVORITE_PHOTOLIST . TESTING_RESP_SUFIX
        );

        $result = $this->fromLongXml->getFavoritePhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
    }
    function testGetFavoritePhotoList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.favorites.getPublicList',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => Phlickr_PhotoList::PER_PAGE_DEFAULT
            ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_FAVORITE_PHOTOLIST . TESTING_RESP_SUFIX
        );

        $result = $this->fromLongXml->getFavoritePhotoList();
        $this->assertEquals(1, $result->getCount());
        $this->assertEquals(array('6508272'), $result->getIds());
    }


    function testGetPhotoList_ReturnsCorrectClass() {
        $result = $this->fromLongXml->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
    }
    function testGetPhotoList_ReturnsCorrectValues() {
        $result = $this->fromLongXml->getPhotoList();
        $this->assertEquals(881, $result->getCount());
        $this->assertEquals(array('2636', '2635', '2633', '2610'), $result->getIds());
    }


    function testGetPhotosetList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.photosets.getList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );
        $result = $this->fromLongXml->getPhotosetList();

        $this->assertType('Phlickr_PhotosetList', $result, 'returns correct class');
    }
    function testGetPhotosetList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.photosets.getList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );
        $result = $this->fromLongXml->getPhotosetList();

        $this->assertEquals(2, $result->getCount());
        $this->assertEquals(array('5','4'), $result->getIds());
    }


    function testGetGroupList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.people.getPublicGroups',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_GROUPS_PUBLIC . TESTING_RESP_SUFIX
        );

        $result = $this->fromLongXml->getGroupList();
        $this->assertType('Phlickr_GroupList', $result);
    }
    function testGetGroupList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.people.getPublicGroups',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_GROUPS_PUBLIC . TESTING_RESP_SUFIX
        );

        $result = $this->fromLongXml->getGroupList();
        $this->assertEquals(1, $result->getCount());
        $this->assertEquals(array('97544914@N00'), $result->getIds());
    }


    function testBuildUrl() {
        $userId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildUrl();
        $this->assertEquals("http://flickr.com/photos/{$userId}/", $result);
    }


    function testBuildCommentsFeedUrl() {
        $userId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildCommentsByFeedUrl();
        $this->assertEquals("http://flickr.com/photos_comments_feed.gne?user_id={$userId}&format=atom", $result);
    }
    function testBuildCommentsFeedUrl_Rss() {
        $userId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildCommentsByFeedUrl('rss');
        $this->assertEquals("http://flickr.com/photos_comments_feed.gne?user_id={$userId}&format=rss", $result);
    }

    function testBuildCommentsOnFeedUrl() {
        $userId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildCommentsOnFeedUrl();
        $this->assertEquals("http://flickr.com/recent_comments_feed.gne?id={$userId}&format=atom", $result);
    }
    function testBuildCommentsOnFeedUrl_Rss() {
        $userId = $this->fromLongXml->getId();
        $result = $this->fromLongXml->buildCommentsOnFeedUrl('rss');
        $this->assertEquals("http://flickr.com/recent_comments_feed.gne?id={$userId}&format=rss", $result);
    }


    function testGetTags() {
        $this->api->addResponseToCache(
            'flickr.tags.getListUser',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USER_TAGS . TESTING_RESP_SUFIX
        );
        $result = $this->fromLongXml->getTags();
        $this->assertEquals(13, count($result));
        $this->assertEquals("2004", $result[0], "first tag didn't match");
        $this->assertEquals("classtag", $result[6], "a random tag in the middle wasn't as expected");
        $this->assertEquals("something", $result[12], "last tag didn't match");
    }

    function testGetPopularTags() {
        $this->api->addResponseToCache(
            'flickr.tags.getListUserPopular',
            array('user_id' => TESTING_XML_USER_ID,
                'count' => 9 ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USER_POPULAR_TAGS . TESTING_RESP_SUFIX
        );
        $count = 9;
        $result = $this->fromLongXml->getPopularTags($count);

        $this->assertEquals($count, count($result));
        $this->assertEquals("2004", $result[0], "first tag didn't match");
        $this->assertEquals("classtag", $result[6], "a random tag in the middle wasn't as expected");
        $this->assertEquals("searchtag", $result[8], "last tag didn't match");
    }
    function testGetPopularTags_IndexedByTag() {
        $this->api->addResponseToCache(
            'flickr.tags.getListUserPopular',
            array('user_id' => TESTING_XML_USER_ID,
                'count' => 9 ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USER_POPULAR_TAGS . TESTING_RESP_SUFIX
        );
        $count = 9;
        $result = $this->fromLongXml->getPopularTags($count, true);
        $this->assertEquals($count, count($result));
        $this->assertEquals(2, $result['2004'], "first tag didn't match");
        $this->assertEquals(13, $result['classtag'], "a random tag in the middle wasn't as expected");
        $this->assertEquals(7, $result['searchtag'], "last tag didn't match");
    }
}
