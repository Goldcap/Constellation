<?php

/**
 * @version $Id: AuthedPhotoset.php 500 2006-01-03 23:29:08Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class extends Phlickr_Photoset.
 */
require_once 'Phlickr/Photoset.php';

/**
 * Phlickr_AuthedPhotoset represents a Flickr photoset.
 *
  * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * include_once 'Phlickr/AuthedPhotosetList.php';
 *
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 *
 * // get a list of your photosets
 * $apsl = Phlickr_AuthedPhotosetList($api);
 *
 * // build an array of ids of your photos (by uploading perhaps?)
 * $photo_ids = array(1, 3, 4, 99);
 *
 * // use the first image for simplicity.
 * $aps = $apsl->create('title', 'description', $photo_ids[0]);

 * // add the rest of the photos and change the primary photo.
 * $aps->editPhotos($photo_ids[2], $photo_ids);
 *
 * // change the title and description
 * $aps->setMeta('a new title', 'a longer description');
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_Photoset
 * @since   0.2.0
 */
class Phlickr_AuthedPhotoset extends Phlickr_Photoset {
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
     * @param   object Phlickr_Api $api This object must have valid
     *          authentication information or an exception will be thrown.
     * @param   mixed $source integer Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source) {
        assert($api->isAuthValid());
        parent::__construct($api, $source);
    }

    /**
     * Set the photoset's title and description.
     *
     * @param   string $title
     * @param   string $description
     * @return  void
     * @throws  Phlickr_Exception
     * @see     getTitle(), getDescription()
     */
    public function setMeta($title, $description) {
        $resp = $this->getApi()->executeMethod(
            'flickr.photosets.editMeta',
            array('photoset_id' => $this->getId(),
                'title' => $title,
                'description' => $description
            )
        );
        $this->refresh();
    }

    /**
     * Edit photos in the photoset.
     *
     * The primary photo must be in the set. All photos must be owned by the
     * current authenticated user.
     *
     * @param   integer $primaryId Id of the set's primary photo. This id must
     *          be in the array of photos.
     * @param   array $photoIds An array of photo ids in the order desired. The
     *          array must contain the primary photo.
     * @return  object Phlickr_PhotosetPhotoList
     * @throws  Phlickr_Exception
     * @see     getPrimaryId(), Phlickr_PhotosetPhotoList::getIds()
     */
    public function editPhotos($primaryId, $photoIds) {
        $req = $this->getApi()->executeMethod('flickr.photosets.editPhotos',
            array('photoset_id' => $this->getId(),
                'primary_photo_id' => $primaryId,
                'photo_ids' => implode(',', $photoIds)
            )
        );
        $this->refresh();

        // call refresh to clear out the cached photo list
        $ret = $this->getPhotoList();
        $ret->refresh();
        return $ret;
    }
}
