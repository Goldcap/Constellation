<?php

/**
 * @version $Id: Photoset.php 506 2006-01-28 04:17:13Z drewish $
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
 * One or more methods returns Phlickr_PhotoList objects.
 */
require_once 'Phlickr/PhotosetPhotoList.php';

/**
 * Phlickr_Photoset is a readonly representation of a Flickr photoset.
 *
 * @todo    Add sample code.
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_AuthedPhotoset
 * @since   0.1.0
 */
class Phlickr_Photoset extends Phlickr_Framework_ObjectBase {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'photoset';
    /**
     * The name of the Flickr API method that provides the info on this object.
     *
     * @var string
     */
    const XML_METHOD_NAME = 'flickr.photosets.getInfo';

    /**
     * Constructor.
     *
     * You can construct a photoset from an Id or XML.
     *
     * @param   object Phlickr_API $api
     * @param   mixed $source string Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException, Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source) {
        parent::__construct($api, $source, self::XML_RESPONSE_ELEMENT);
    }

    public function __toString() {
        return $this->getTitle() . ' (' . $this->getId() . ')';
    }

    static function getRequestMethodName() {
        return self::XML_METHOD_NAME;
    }

    static function getRequestMethodParams($id) {
        return array('photoset_id' => (string) $id);
    }

    public function getId() {
        return (string) $this->_cachedXml['id'];
    }

    /**
     * Return the user id of this photoset's owner.
     *
     * @return  string
     * @since   0.2.1
     */
    public function getUserId() {
        if (!isset($this->_cachedXml['owner'])) {
            $this->load();
        }
        return (string) $this->_cachedXml['owner'];
    }

    /**
     * Return the primary photo's Id.
     *
     * @return  string
     * @see     Phlickr_AuthedPhotoset::editPhotos()
     */
    public function getPrimaryId() {
        if (!isset($this->_cachedXml)) {
            $this->load();
        }
        return (string) $this->_cachedXml['primary'];
    }

    /**
     * Return the Photoset's title.
     *
     * @return  string
     * @see     Phlickr_AuthedPhotoset::setMeta()
     */
    public function getTitle() {
        if (!isset($this->_cachedXml->title)) {
            $this->load();
        }
        return (string) $this->_cachedXml->title;
    }

    /**
     * Return the Photoset's description.
     *
     * @return  string
     * @see     Phlickr_AuthedPhotoset::setMeta()
     */
    public function getDescription() {
        if (!isset($this->_cachedXml->description)) {
            $this->load();
        }
        return (string) $this->_cachedXml->description;
    }

    /**
    * Return the number of photos in the photoset.
    *
    * This does exactly the same thing as $photoset->getPhotoList()->getCount()
    * but it avoids the overhead of fetching the full photo list.
    *
    * @return   integer
    * @since    0.1.9
    */
    public function getPhotoCount() {
        if (!isset($this->_cachedXml)){
            $this->load();
        }
        return (integer) $this->_cachedXml['photos'];
    }

    /**
     * Return a PhotoList of the photos in this Photoset.
     *
     * @return  object Phlickr_PhotosetPhotoList
     */
    public function getPhotoList() {
        $req = $this->getApi()->createRequest(
            'flickr.photosets.getPhotos',
            array('photoset_id' => $this->getId())
        );
        return new Phlickr_PhotosetPhotoList($req);
    }

    /**
     * Build a URL to access the photoset.
     *
     * @return  string
     */
    public function buildUrl() {
        return "http://flickr.com/photos/{$this->getUserId()}/sets/{$this->getId()}/";
    }
}
