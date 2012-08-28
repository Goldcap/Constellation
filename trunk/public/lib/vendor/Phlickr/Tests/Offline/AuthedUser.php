<?php

/**
 * Auth User Offline Tests
 *
 * @version $Id: AuthedUser.php 494 2005-11-26 10:03:16Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/AuthedUser.php';
require_once 'Phlickr/Tests/constants.inc';



define('XML_FAVORITE_PHOTOLIST_PAGE1', <<<XML
<photos page="1" pages="41" perpage="5" total="203">
<photo id="11025559" owner="22221054@N00" secret="090a93e61f" server="7" title="wireframe" ispublic="1" isfriend="0" isfamily="0"/>
<photo id="4980204" owner="49298425@N00" secret="56f4124525" server="4" title="bear gazes" ispublic="1" isfriend="0" isfamily="0"/>
<photo id="9873731" owner="47527967@N00" secret="66eadd7a2a" server="7" title="Waterfront Fountain" ispublic="1" isfriend="0" isfamily="0"/>
<photo id="10385398" owner="88135495@N00" secret="d1de845ab8" server="8" title="vent a" ispublic="1" isfriend="0" isfamily="0"/>
<photo id="10449226" owner="22221054@N00" secret="5c21993de1" server="3" title="diagonal one" ispublic="1" isfriend="0" isfamily="0"/>
</photos>
XML
);

define('XML_FAVORITE_PHOTOLIST_PAGE2', <<<XML
<photos page="2" pages="41" perpage="5" total="203">
<photo id="5958530" owner="13173157@N00" secret="f36047528c" server="5" title="Hungry" ispublic="1" isfriend="0" isfamily="0"/>
<photo id="10041869" owner="33642498@N00" secret="f7bcbadee9" server="8" title="Composition" ispublic="1" isfriend="1" isfamily="1"/>
<photo id="10043049" owner="33642498@N00" secret="f1b4523b55" server="8" title="We Are The Robots" ispublic="1" isfriend="1" isfamily="1"/>
<photo id="9854274" owner="26084283@N00" secret="7cda20a1b2" server="6" title="wicked_world" ispublic="1" isfriend="0" isfamily="0"/>
<photo id="6324978" owner="52232708@N00" secret="0b80daa8e0" server="7" title="Fremont Bridge" ispublic="1" isfriend="1" isfamily="1"/>
</photos>
XML
);

class Phlickr_Tests_Offline_AuthedUser extends PHPUnit2_Framework_TestCase {
    var $api;
    var $user;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET, TESTING_API_TOKEN);
        $this->api->setEndpointUrl('http://example.com');

        // inject the response xml into the cache...
        // ... first the login details (so it can figure out the user id)
        $this->api->addResponseToCache(
            'flickr.auth.checkToken',
            $this->api->getParamsForRequest(),
            TESTING_RESP_OK_PREFIX . TESTING_XML_CHECKTOKEN . TESTING_RESP_SUFIX
        );
        // ... then the full description of the user
        $this->api->addResponseToCache(
            Phlickr_AuthedUser::getRequestMethodName(),
            Phlickr_AuthedUser::getRequestMethodParams(TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USER_LONG . TESTING_RESP_SUFIX
        );

        $this->user = new Phlickr_AuthedUser($this->api);
    }
    function tearDown() {
        unset($this->user);
        unset($this->api);
    }

    function testConstructor_ReturnsCorrectClass() {
        $this->assertNotNull($this->user);
        $this->assertType('Phlickr_AuthedUser', $this->user);
    }
    function testConstructor_ThrowsWithoutAuthedApi() {
        $api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
        try {
            $user = new Phlickr_AuthedUser($api);
        } catch (Phlickr_Exception $ex){
            return;
        } catch (Exception $ex){
            $this->fail('threw the wrong type ('.get_class($ex).') of exception.');
        }
        $this->Fail('An exception should have been thrown.');
    }
    function testConstructor_AssignsApi() {
        $this->assertSame($this->api, $this->user->getApi());
    }
    function testConstructor_AssignsId() {
        $this->assertEquals(TESTING_USER_ID, $this->user->getId());
    }


    function testGetContactUserList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.contacts.getList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_PRIVATE_CONTACTS . TESTING_RESP_SUFIX
        );

        $result = $this->user->getContactUserList();
        $this->assertType('Phlickr_UserList', $result);
    }
    function testGetContactUserList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.contacts.getList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_PRIVATE_CONTACTS . TESTING_RESP_SUFIX
        );

        $result = $this->user->getContactUserList();
        $this->assertEquals(3, $result->getCount());
        $this->assertEquals(
            array('12037949629@N01', '12037949631@N01', '41578656547@N01'),
            $result->getIds());
    }


    function testGetPhotoList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.photos.search',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => Phlickr_PhotoList::PER_PAGE_DEFAULT
            ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOS . TESTING_RESP_SUFIX
        );
        $result = $this->user->getPhotoList();
        $this->assertType('Phlickr_PhotoList', $result);
    }
    function testGetPhotoList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.photos.search',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => Phlickr_PhotoList::PER_PAGE_DEFAULT
            ),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOS . TESTING_RESP_SUFIX
        );

        $result = $this->user->getPhotoList();
        $this->assertEquals(881, $result->getCount());
        $this->assertEquals(array('2636', '2635', '2633', '2610'), $result->getIds());
    }


    function testGetFavoritePhotoList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.favorites.getList',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => 5
            ),
            TESTING_RESP_OK_PREFIX . XML_FAVORITE_PHOTOLIST_PAGE1 . TESTING_RESP_SUFIX
        );
        $this->api->addResponseToCache(
            'flickr.favorites.getList',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 2,
                'per_page' => 5
            ),
            TESTING_RESP_OK_PREFIX . XML_FAVORITE_PHOTOLIST_PAGE2 . TESTING_RESP_SUFIX
        );

        $result = $this->user->getFavoritePhotoList(5);
        $this->assertType('Phlickr_PhotoList', $result);
    }
    function testGetFavoritePhotoList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.favorites.getList',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 1,
                'per_page' => 5
            ),
            TESTING_RESP_OK_PREFIX . XML_FAVORITE_PHOTOLIST_PAGE1 . TESTING_RESP_SUFIX
        );
        $this->api->addResponseToCache(
            'flickr.favorites.getList',
            array(
                'user_id' => TESTING_XML_USER_ID,
                'page' => 2,
                'per_page' => 5
            ),
            TESTING_RESP_OK_PREFIX . XML_FAVORITE_PHOTOLIST_PAGE2 . TESTING_RESP_SUFIX
        );

        $result = $this->user->getFavoritePhotoList(5);
        $this->assertEquals(1, $result->getPage());
        $this->assertEquals(array('11025559', '4980204', '9873731', '10385398', '10449226'), $result->getIds());

        $result->setPage(2);
        $this->assertEquals(2, $result->getPage());
        $this->assertEquals(array('5958530', '10041869', '10043049', '9854274', '6324978'), $result->getIds());
    }


    function testGetPhotosetList_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.photosets.getList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );

        $result = $this->user->getPhotosetList();
        $this->assertType('Phlickr_AuthedPhotosetList', $result, 'returns correct class');
    }
    function testGetPhotosetList_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.photosets.getList',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_PHOTOSETS . TESTING_RESP_SUFIX
        );

        $result = $this->user->getPhotosetList();
        $this->assertEquals(2, $result->getCount());
        $this->assertEquals(array('5','4'), $result->getIds());
    }


    function testGetGroups_ReturnsCorrectClass() {
        $this->api->addResponseToCache(
            'flickr.groups.pools.getGroups',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_GROUPS_PRIVATE . TESTING_RESP_SUFIX
        );

        $result = $this->user->getGroupList();
        $this->assertType('Phlickr_GroupList', $result);
    }
    function testGetGroups_ReturnsCorrectValues() {
        $this->api->addResponseToCache(
            'flickr.groups.pools.getGroups',
            array('user_id' => TESTING_XML_USER_ID),
            TESTING_RESP_OK_PREFIX . TESTING_XML_GROUPS_PRIVATE . TESTING_RESP_SUFIX
        );

        $result = $this->user->getGroupList();
        $this->assertEquals(2, $result->getCount());
        $this->assertEquals(array('97544914@N00','84636767@N00'),
            $result->getIds());
    }


    function testBuildUrl() {
        $userId = $this->user->getId();
        $result = $this->user->buildUrl();
        $this->assertEquals("http://flickr.com/photos/{$userId}/", $result);
    }

}

?>
