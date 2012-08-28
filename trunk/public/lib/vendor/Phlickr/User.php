<?php

/**
 * @version $Id: User.php 511 2006-02-05 03:45:21Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Phlickr_Api includes the core classes.
 */
require_once 'Phlickr/Api.php';
/**
 * This class extends Phlickr_ObjectBase.
 */
require_once 'Phlickr/Framework/ObjectBase.php';
/**
 * One or more methods returns Phlickr_GroupList objects.
 */
require_once 'Phlickr/GroupList.php';
/**
 * One or more methods returns Phlickr_PhotoList objects.
 */
require_once 'Phlickr/PhotoList.php';
/**
 * One or more methods returns Phlickr_PhotosetList objects.
 */
require_once 'Phlickr/PhotosetList.php';
/**
 * One or more methods returns Phlickr_UserList objects.
 */
require_once 'Phlickr/UserList.php';


/**
 * Phlickr_User is a Flickr user.
 *
 * This class allows access to the profile, favorites, photos, photosets, and
 * groups associated with a user. This class is readonly, to modify a user use
 * the Phlickr_AuthedUser class.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * include_once 'Phlickr/User.php';
 *
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 * $user = Phlickr_User::findByUrl($api, 'http://flickr.com/people/drewish/');
 *
 * // print out the user's name
 * print "User: {$user->getName()}\n";
 *
 * // print out their groups
 * foreach ($user->getGroupList()->getGroups() as $group) {
 *     print "Group: {$group->getName()} ({$group->buildUrl()})\n";
 * }
 *
 * // print out their photosets
 * foreach ($user->getPhotosetList()->getPhotosets() as $photoset) {
 *     print "Photoset: {$photoset->getTitle()} ({$photoset->buildUrl()})\n";
 * }
 *
 * // print out their 10 latest, favorite photos
 * $photolist = $user->getFavoritePhotoList(10);
 * foreach ($photolist->getPhotos() as $photo) {
 *     print "Favorite: {$photo->getTitle()} ({$photo->buildImgUrl()})\n";
 * }
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_AuthedUser
 * @since   0.1.0
 */
class Phlickr_User extends Phlickr_Framework_ObjectBase {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'person';
    /**
     * The name of the Flickr API method that provides the info on this object.
     *
     * @var string
     */
    const XML_METHOD_NAME = 'flickr.people.getInfo';

    /**
     * Constructor.
     *
     * You can construct a user by Id or XML.
     *
     * @param   object Phlickr_API $api
     * @param   mixed $source string Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source) {
        parent::__construct($api, $source, self::XML_RESPONSE_ELEMENT);
    }

    public function __toString() {
        return $this->getName() . ' (' . $this->getId() . ')';
    }

    static function getRequestMethodName() {
        return self::XML_METHOD_NAME;
    }

    static function getRequestMethodParams($id) {
        return array('user_id' => (string) $id);
    }

    /**
     * Find a user by their user name.
     *
     * This will thrown an Phlickr_MethodFailureException exception if no user
     * can be found.
     *
     * @param   object Phlickr_Api $api An API object.
     * @param   string $username The user's name.
     * @return  object Flickr_User
     * @since   0.1.7
     * @see     findByEmail(), findByUrl()
    */
    static function findByUsername(Phlickr_Api $api, $username) {
        $resp = $api->executeMethod(
            'flickr.people.findByUsername',
            array('username' => $username)
        );
        return new Phlickr_User($api, (string) $resp->xml->user['nsid']);
    }
    /**
     * Find a user by their email address.
     *
     * This will thrown an Phlickr_MethodFailureException exception if no user
     * can be found.
     *
     * @param   object Phlickr_Api $api An API object.
     * @param   string $email The user's email address.
     * @return  object Flickr_User
     * @since   0.1.7
     * @see     findByUsername(), findByUrl()
    */
    static function findByEmail(Phlickr_Api $api, $email) {
        $resp = $api->executeMethod(
            'flickr.people.findByEmail',
            array('find_email' => $email)
        );
        $id = (string) $resp->xml->user['nsid'];
        return new Phlickr_User($api, $id);
    }
    /**
     * Find a user based on their Flickr URL.
     *
     * The URL can be in the following forms:
     * - http://flickr.com/photos/39059360@N00/
     * - http://flickr.com/photos/justtesting/
     * - http://flickr.com/people/39059360@N00/
     * - http://flickr.com/people/justtesting/
     *
     * This will thrown an Phlickr_MethodFailureException exception if no user
     * can be found.
     *
     * @param   object Phlickr_Api $api An API object.
     * @param   string $user The user's URL.
     * @return  object Flickr_User
     * @since   0.1.7
     * @see     findByUsername(), findByEmail()
    */
    static function findByUrl(Phlickr_Api $api, $url) {
        $resp = $api->executeMethod(
            'flickr.urls.lookupUser',
            array('url' => $url)
        );
        $id = (string) $resp->xml->user['id'];
        return new Phlickr_User($api, $id);
    }

    public function getId() {
        return (string) $this->_cachedXml['nsid'];
    }

    /**
     * Return the User's username.
     *
     * @return  string
     */
    public function getName() {
        if (!isset($this->_cachedXml->username)) {
            $this->load();
        }
        return (string) $this->_cachedXml->username;
    }

    /**
     * Return the User's real name.
     *
     * @return  string
     * @since   0.2.1
     */
    public function getRealname() {
        if (!isset($this->_cachedXml->realname)) {
            $this->load();
        }
        return (string) $this->_cachedXml->realname;
    }

    /**
     * Return the User's location.
     *
     * @return  string
     */
    public function getLocation() {
        if (!isset($this->_cachedXml->location)) {
            $this->load();
        }
        return (string) $this->_cachedXml->location;
    }

    /**
     * Total number of photos
     *
     * @return  integer
     */
    public function getPhotoCount() {
        if (!isset($this->_cachedXml->photos)) {
            $this->load();
        }
        return (integer) $this->_cachedXml->photos->count;
    }


    /**
     * Return a UserList of this user's contacts.
     *
     * @return  object Phlickr_UserList
     */
    public function getContactUserList() {
        $request = $this->getApi()->createRequest(
            'flickr.contacts.getPublicList',
            array('user_id' => $this->getId())
        );
        return new Phlickr_UserList($request);
    }

    /**
     * Return a PhotoList of this user's favorite photos.
     *
     * @param   integer $perPage Number of photos per page
     * @return  object Phlickr_PhotoList
     * @since   0.1.3
     */
    public function getFavoritePhotoList($perPage = Phlickr_PhotoList::PER_PAGE_DEFAULT) {
        $request = $this->getApi()->createRequest(
            'flickr.favorites.getPublicList',
            array('user_id' => $this->getId())
        );
        return new Phlickr_PhotoList($request, $perPage);
    }

    /**
     * Return a GroupList of the groups that this user belongs to.
     *
     * @return  object Phlickr_GroupList
     */
    public function getGroupList() {
        $request = $this->getApi()->createRequest(
            'flickr.people.getPublicGroups',
            array('user_id' => $this->getId())
        );
        return new Phlickr_GroupList($request);
    }

    /**
     * Return a PhotoList of this user's photos.
     *
     * @param   integer $perPage Number of photos per page
     * @return  object Phlickr_PhotoList
     * @since   0.1.3
     */
    public function getPhotoList($perPage = Phlickr_PhotoList::PER_PAGE_DEFAULT) {
        $request = $this->getApi()->createRequest(
            'flickr.people.getPublicPhotos',
            array('user_id' => $this->getId())
        );
        return new Phlickr_PhotoList($request, $perPage);
    }

    /**
     * Return a PhotosetList for this user.
     *
     * @return  object Phlickr_PhotosetList
     */
    public function getPhotosetList() {
        return new Phlickr_PhotosetList($this->getApi(), $this->getId());
    }

    /**
     * Return all of tags attached to the user's photos.
     *
     * @return  array of string tags
     * @since   0.2.6
     * @see     getPopularTags(), Phlickr_Photo::getTags()
     */
    public function getTags() {
        $ret = array();
        $resp = $this->getApi()->executeMethod(
            'flickr.tags.getListUser',
            array('user_id' => $this->getId())
        );

        foreach ($resp->xml->who->tags->tag as $tag) {
            $ret[] = (string) $tag;
        }
        return $ret;
    }

    /**
     * Return <i>n</i> of the user's most popular tags. If desired the usage
     * count can be returned.
     *
     * @param   integer $count The number of tags to return.
     * @param   boolean $indexByTag Should the tags be used as indexes so that
     *          the usage counts can be returned? This is false by default
     *          for compatibility with all the other getTags functions that
     *          simply return arrays of strings.
     * @return  array if $indexByTag the index will be the string tag and the
     *          value will be the tag's usage count. If $indexByTag is false,
     *          the values will be the tag strings.
     * @since   0.2.6
     * @see     getTags(), Phlickr_Photo::getTags()
     */
    public function getPopularTags($count = 10, $indexByTag = false) {
        $ret = array();
        $resp = $this->getApi()->executeMethod(
            'flickr.tags.getListUserPopular',
            array('user_id' => $this->getId(), 'count' => $count)
        );

        // should tags be used as indexes?
        if ($indexByTag) {
            foreach ($resp->xml->who->tags->tag as $tag) {
                $ret[(string) $tag] = (integer) $tag['count'];
            }
        } else {
            foreach ($resp->xml->who->tags->tag as $tag) {
                $ret[] = (string) $tag;
            }
        }
        return $ret;
    }

    /**
     * Build a URL to access the user's photo page.
     *
     * @return  string
     */
    public function buildUrl() {
        return "http://flickr.com/photos/{$this->getId()}/";
    }

    /**
     * Build a URL to a feed of a user's recent comments.
     *
     * @param   $format string specifying the desired feed format. Acceptable
     *          values include 'rss', 'rss2', 'atom', and 'rdf' but you should
     *          check http://flickr.com/services/feeds/ for a complete list of
     *          formats.
     * @return  string URL
     * @since   0.2.6
     */
    public function buildCommentsByFeedUrl($format = 'atom') {
        return "http://flickr.com/photos_comments_feed.gne?user_id={$this->getId()}&format={$format}";
    }

    /**
     * Build a URL to a feed of a comments on a user's photos.
     *
     * @param   $format string specifying the desired feed format. Acceptable
     *          values include 'rss', 'rss2', 'atom', and 'rdf' but you should
     *          check http://flickr.com/services/feeds/ for a complete list of
     *          formats.
     * @return  string URL
     * @since   0.2.6
     */
    public function buildCommentsOnFeedUrl($format = 'atom') {
        return "http://flickr.com/recent_comments_feed.gne?id={$this->getId()}&format={$format}";
    }
}
