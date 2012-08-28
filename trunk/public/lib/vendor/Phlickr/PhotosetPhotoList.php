<?php

/**
 * @version $Id: PhotosetPhotoList.php 506 2006-01-28 04:17:13Z drewish $
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
 * This class extends Phlickr_ListBase.
 */
require_once 'Phlickr/Framework/ListBase.php';
/**
 * This class implements IPhotoList.
 */
require_once 'Phlickr/Framework/IPhotoList.php';

/**
 * Phlickr_PhotosetPhotoList represents all the photos in a photoset.
 *
 * Unlike Phlickr_PhotoList, the photoset list is not segmented into pages.
 *
 * @todo    Add sample code.
 * @package Phlickr
 * @since   0.1.1
 */
class Phlickr_PhotosetPhotoList extends Phlickr_Framework_ListBase
    implements Phlickr_Framework_IPhotoList
{
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_LIST_ELEMENT = 'photoset';
    /**
     * The name of the XML element in the response that defines a member of the
     * list.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'photo';
    /**
     * The name of the Flickr API method that provides the info on this object.
     *
     * @var string
     */
    const XML_METHOD_NAME = 'flickr.photosets.getPhotos';

    /**
     * Constructor.
     *
     * @param object Phlickr_Request $request
     */
    function __construct(Phlickr_Request $request) {
        parent::__construct($request, self::XML_RESPONSE_ELEMENT,
            self::XML_RESPONSE_LIST_ELEMENT);
    }

    /**
     * Returns the name of this object's getInfo API method.
     *
     * @return  string
     */
    static function getRequestMethodName() {
        return self::XML_METHOD_NAME;
    }
    /**
     * Returns an array of parameters to be used when creating a
     * Phlickr_Request to call this object's getInfo API method.
     *
     * @param   string $id The id of this photoset.
     * @return  array
     */
    static function getRequestMethodParams($id) {
        return array('photoset_id' => (string) $id);
    }

    /**
     * Return the number of photos in the photoset.
     *
     * The count on a Photoset should always be 1 or more.
     *
     * @return  integer
     */
    public function getCount() {
        if (!isset($this->_cachedXml->photo)) {
            $this->load();
        }
        $ret = 0;
        foreach ($this->_cachedXml->photo as $xml) {
            $ret++;
        }
        return $ret;
    }

    public function getPhotos() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->load();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            $photo = new Phlickr_Photo($this->getApi(), $xml);
            $ret[] = $photo;
        }
        return $ret;
    }

    /**
     * Return a random photo from the list.
     *
     * @return  object Phlickr_Photo
     * @since   0.1.6
     */
    function getRandomPhoto() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->load();
        }
        $photos = $this->_cachedXml->{$this->getResponseElement()};
        $photoXml = $photos[rand(0, $this->getCount() - 1)];
        return new Phlickr_Photo($this->getApi(), $photoXml);
    }
}
