<?php

/**
 * @version $Id: AuthedPhotosetList.php 519 2006-04-24 06:10:30Z drewish $
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
 * This class extends Phlickr_PhotosetList.
 */
require_once 'Phlickr/PhotosetList.php';
/**
 * One or more methods returns Phlickr_AuthedPhotoset objects.
 */
require_once 'Phlickr/AuthedPhotoset.php';

/**
 * Phlickr_PhotosetList is a modifiable list of an authenticated user's
 * photosets.
 *
 * This class requires that the Phlickr_Api have valid authentication
 * information.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_PhotosetList
 * @since   0.1.9
 * @todo    Add sample code.
 */
class Phlickr_AuthedPhotosetList extends Phlickr_PhotosetList {
    /**
     * Constructor.
     *
     * @param object Phlickr_Api $api This object must have valid
     *      authentication information or an exception will be thrown.
     */
    function __construct(Phlickr_Api $api) {
        parent::__construct($api, $api->getUserId());
    }

    /**
     * Return an array of Phlickr_Photosets.
     *
     * @return  array
     */
    public function getPhotosets() {
        if (!isset($this->_cachedXml->{$this->getResponseElement()})) {
            $this->load();
        }
        $ret = array();
        foreach ($this->_cachedXml->{$this->getResponseElement()} as $xml) {
            $ret[] = new Phlickr_AuthedPhotoset($this->getApi(), $xml);
        }
        return $ret;
    }

    /**
     * Create a new Photoset
     *
     * @param   string  $title Photoset's title.
     * @param   string  $description Photoset's description.
     * @param   string  $primaryPhotoId Id of the photo that will represent the Photoset.
     * @return  string  Id of the new Photoset.
     * @throws  Phlickr_Exception
     * @see     delete()
     */
    public function create($title, $description, $primaryPhotoId) {
        $resp = $this->getApi()->executeMethod(
            'flickr.photosets.create',
            array('title' => $title,
                'description' => $description,
                'primary_photo_id' => $primaryPhotoId
            )
        );
        // update the cashed xml after the changes
        $this->refresh();

        return (string) $resp->xml->photoset['id'];
    }

    /**
     * Adjust the ordering of the Photosets.
     *
     * @param   array $ids Array of Photo Ids in the order desired.
     * @return  void
     * @throws  Phlickr_Exception
     */
    public function reorder($ids) {
        $this->getApi()->executeMethod(
            'flickr.photosets.orderSets',
            array('photoset_ids' => implode(',', $ids))
        );
        // update the cashed xml after the changes
        $this->refresh();
    }

    /**
     * Delete a Photoset.
     *
     * @param   string $id Id of Photoset to delete
     * @return  void
     * @throws  Phlickr_Exception
     * @see     create()
     */
    public function delete($id) {
        $this->getApi()->executeMethod(
            'flickr.photosets.delete',
            array('photoset_id' => $id)
        );
        // update the cashed xml after the changes
        $this->refresh();
    }
}
