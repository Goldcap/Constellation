<?php

/**
 * UserList Offline Tests
 *
 * @version $Id: UserList.php 520 2006-04-24 06:11:53Z drewish $
 * @copyright 2005
 */

require_once 'PHPUnit2/Framework/TestCase.php';
require_once 'Phlickr/UserList.php';
require_once 'Phlickr/Tests/Mocks/Request.php';
require_once 'Phlickr/Tests/constants.inc';

class Phlickr_Tests_Offline_UserList extends PHPUnit2_Framework_TestCase {
    var $api;
    var $requestPublicContacts, $usersPublicContacts, $idsPublicContacts;
    var $requestPrivateContacts, $usersPrivateContacts, $idsPrivateContacts;
    var $requestOnline, $usersOnline, $idsOnline;

    function setUp() {
        $this->api = new Phlickr_Api(TESTING_API_KEY, TESTING_API_SECRET);
            $this->api->setEndpointUrl('http://example.com');

        $this->requestOnline = new Phlickr_Tests_Mocks_Request($this->api, 'ONLINE USERS',
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_ONLINE . TESTING_RESP_SUFIX
        );
        $this->usersOnline = new Phlickr_UserList($this->requestOnline, 'user', 'online');
        $this->idsOnline = array('72037949754@N01', '12037949754@N01');

        $this->requestPublicContacts = new Phlickr_Tests_Mocks_Request($this->api, 'PUBLIC USERS',
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_PUBLIC_CONTACTS . TESTING_RESP_SUFIX
        );
        $this->usersPublicContacts = new Phlickr_UserList($this->requestPublicContacts);
        $this->idsPublicContacts = array('12037949629@N01','12037949631@N01','41578656547@N01');

        $this->requestPrivateContacts = new Phlickr_Tests_Mocks_Request($this->api, 'PrivateContacts USERS',
            TESTING_RESP_OK_PREFIX . TESTING_XML_USERS_PRIVATE_CONTACTS . TESTING_RESP_SUFIX
        );
        $this->usersPrivateContacts = new Phlickr_UserList($this->requestPrivateContacts);
        $this->idsPrivateContacts = array('12037949629@N01','12037949631@N01','41578656547@N01');
    }
    function tearDown() {
        unset($this->idsOnline);
        unset($this->usersOnline);
        unset($this->requestOnline);

        unset($this->idsPublicContacts);
        unset($this->usersPublicContacts);
        unset($this->requestPublicContacts);

        unset($this->idsPrivateContacts);
        unset($this->usersPrivateContacts);
        unset($this->requestPrivateContacts);

        unset($this->api);
    }


    function testConstructor_AssignsRequest() {
        $this->assertSame($this->requestOnline, $this->usersOnline->getRequest());
        $this->assertSame($this->requestPublicContacts,
        $this->usersPublicContacts->getRequest());
        $this->assertSame($this->requestPrivateContacts,
        $this->usersPrivateContacts->getRequest());
    }


    function testGetCount() {
        $this->assertEquals(count($this->idsOnline), $this->usersOnline->getCount(),
            'online did not return correct count.');
        $this->assertEquals(count($this->idsPublicContacts),
        $this->usersPublicContacts->getCount(),
            'PublicContacts did not return correct count.');
        $this->assertEquals(count($this->idsPrivateContacts),
        $this->usersPrivateContacts->getCount(),
            'PrivateContacts did not return correct count.');
    }


    function testGetIds_ReturnsArray() {
        $this->assertTrue(is_array($this->usersOnline->getIds()),
            'online did not return an array.');
        $this->assertTrue(is_array($this->usersPublicContacts->getIds()),
            'PublicContacts did not return an array.');
        $this->assertTrue(is_array($this->usersPrivateContacts->getIds()),
            'PrivateContacts did not return an array. ');
    }
    function testGetIds_ReturnsCorrectIds() {
        $this->assertEquals($this->idsOnline, $this->usersOnline->getIds(),
            'online did not return correct ids.');
        $this->assertEquals($this->idsPublicContacts,
            $this->usersPublicContacts->getIds(),
            'PublicContacts did not return correct ids.');
        $this->assertEquals($this->idsPrivateContacts,
            $this->usersPrivateContacts->getIds(),
            'PrivateContacts did not return correct ids.');
    }


    function testGetUsers_Online_ReturnsArrayOfUsers() {
        $result = $this->usersOnline->getUsers();
        $this->assertTrue(is_array($result), 'did not return an array. ');
        foreach ($result as $r) {
            $this->assertType('Phlickr_User', $r);
        }
    }
    function testGetUsers_PublicContacts_ReturnsArrayOfUsers() {
        $result = $this->usersPublicContacts->getUsers();
        $this->assertTrue(is_array($result), 'did not return an array. ');
        foreach ($result as $r) {
            $this->assertType('Phlickr_User', $r);
        }
    }
    function testGetUsers_PrivateContacts_ReturnsArrayOfUsers() {
        $result = $this->usersPrivateContacts->getUsers();
        $this->assertTrue(is_array($result), 'did not return an array. ');
        foreach ($result as $r) {
            $this->assertType('Phlickr_User', $r);
        }
    }


    function testGetUsers_Online_ReturnsCorrectUsers() {
        $result = $this->usersOnline->getUsers();
        for ($i = 0; $i < $this->usersOnline->getCount(); $i++) {
            $this->assertEquals($this->idsOnline[$i], $result[$i]->getId());
        }
    }
    function testGetUsers_PublicContacts_ReturnsCorrectUsers() {
        $result = $this->usersPublicContacts->getUsers();
        for ($i = 0; $i < $this->usersPublicContacts->getCount(); $i++) {
            $this->assertEquals($this->idsPublicContacts[$i], $result[$i]->getId());
        }
    }
    function testGetUsers_PrivateContacts_ReturnsCorrectUsers() {
        $result = $this->usersPrivateContacts->getUsers();
        for ($i = 0; $i < $this->usersPrivateContacts->getCount(); $i++) {
            $this->assertEquals($this->idsPrivateContacts[$i], $result[$i]->getId());
        }
    }
}
