<?php

/**
 * @version $Id: Group.php 510 2006-02-05 03:44:39Z drewish $
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
 * One or more methods returns Phlickr_PhotosetList objects.
 */
require_once 'Phlickr/PhotoList.php';

/**
 * Phlickr_Group access to the photos in a group.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * include_once 'Phlickr/GroupList.php';
 * include_once 'Phlickr/PhotoListIterator.php';
 *
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 *
 * $group = new Phlickr_Group($api, '98274710@N00');
 * $photolist = $group->getPhotoList();
 *
 * foreach(new Phlickr_PhotoListIterator($photolist) as $page => $photos) {
 *     print "Page number: $page\n";
 *     foreach ($photos as $photo) {
 *         print "Photo Id: {$photo->getId()} Title: '{$photo->getTitle()}'\n";
 *     }
 * }
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_AuthedGroup
 * @since   0.1.4
 */
class Phlickr_Group extends Phlickr_Framework_ObjectBase {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'group';
    /**
     * The name of the Flickr API method that provides the info on this object.
     *
     * @var string
     */
    const XML_METHOD_NAME = 'flickr.groups.getInfo';

    /**
     * Constructor.
     *
     * You can construct a group from an Id or XML.
     *
     * @param   object Phlickr_API $api
     * @param   mixed $source string Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException, Phlickr_XmlParseException
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
        return array('group_id' => (string) $id);
    }

    /**
     * Find a group based on its Flickr URL.
     *
     * The URL can be in the following forms:
     * - http://flickr.com/groups/infrastructure/
     * - http://flickr.com/groups/infrastructure/pool/
     * - http://flickr.com/groups/97544914@N00/
     *
     * This will thrown an Phlickr_MethodFailureException exception if no group
     * can be found.
     *
     * @param   object Phlickr_Api An API object.
     * @param   string The groups's URL.
     * @return  object Flickr_User
     * @since   0.1.8
    */
    static function findByUrl(Phlickr_Api $api, $url) {
        $resp = $api->executeMethod(
            'flickr.urls.lookupGroup',
            array('url' => $url)
        );
        $id = (string) $resp->xml->{self::XML_RESPONSE_ELEMENT}['id'];
        return new Phlickr_Group($api, $id);
    }

    public function getId() {
        // most of the xml as the id in the "id" attribute but
        // flickr.people.getPublicGroups returns it in the "nsid" attribute
        if (!isset($this->_cachedXml['id']) && isset($this->_cachedXml['nsid'])) {
            return (string) $this->_cachedXml['nsid'];
        }
        return (string) $this->_cachedXml['id'];
    }

    /**
     * Return the name of this Group
     *
     * @return  string
     */
    public function getName() {
        if (!isset($this->_cachedXml->name)) {
            // some of the short group definitions have the title in the group
            // attribute. if it isn't, get the full (long) version.
            if (isset($this->_cachedXml['name'])) {
                return (string) $this->_cachedXml['name'];
            } else {
                $this->load();
            }
        }
        return (string) $this->_cachedXml->name;
    }

    /**
     * Return a PhotoList of this group's photo pool.
     *
     * @param   integer $perPage Number of photos per page
     * @return  object Phlickr_PhotoList
     * @since   0.1.5
     */
    public function getPhotoList($perPage = 10) {
        $request = $this->getApi()->createRequest(
            'flickr.groups.pools.getPhotos',
            array('group_id'=>$this->getId())
        );
        return new Phlickr_PhotoList($request, $perPage);
    }

    /**
     * Build a URL to access the group's main page.
     *
     * @return  string
     */
    public function buildUrl() {
        return "http://flickr.com/groups/{$this->getId()}/";
    }

    /**
     * Build a URL to a group's discussion feed.
     *
     * @param   $format string specifying the desired feed format. Acceptable
     *          values include 'rss', 'rss2', 'atom', and 'rdf' but you should
     *          check http://flickr.com/services/feeds/ for a complete list of
     *          formats.
     * @return  string URL
     * @since   0.2.6
     */
    public function buildDiscussFeedUrl($feed = 'atom') {
        return "http://flickr.com/groups_feed.gne?id={$this->getId()}&format={$feed}";
    }

    /**
     * Build a URL to a group's photo feed.
     *
     * @param   $format string specifying the desired feed format. Acceptable
     *          values include 'rss', 'rss2', 'atom', and 'rdf' but you should
     *          check http://flickr.com/services/feeds/ for a complete list of
     *          formats.
     * @return  string URL
     * @since   0.2.6
     */
    public function buildPhotoFeedUrl($feed = 'atom') {
        return "http://flickr.com/groups/{$this->getId()}/pool/feed?format={$feed}";
    }
}
