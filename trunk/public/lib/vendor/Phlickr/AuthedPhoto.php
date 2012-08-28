<?php

/**
 * @version $Id: AuthedPhoto.php 512 2006-02-05 03:46:12Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class extends Phlickr_Photo.
 */
require_once 'Phlickr/Photo.php';
/**
 * This class extends Phlickr_Photo to allow modifications of a photo.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_Photo
 * @since   0.2.1
 * @todo    Add sample code.
 * @todo    Implement a setPerms().
 */
class Phlickr_AuthedPhoto extends Phlickr_Photo {
    /**
     * Constructor.
     *
     * You can construct a photo from an Id or XML.
     *
     * @param   object Phlickr_Api $api This object must have valid
     *          authentication information or an exception will be thrown.
     * @param   mixed $source string Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source) {
        parent::__construct($api, $source);
    }

    /**
     * Delete the photo. This requires write permission.
     *
     * @return  boolean
     * @since   0.2.6
     */
    function delete() {
        $this->getApi()->executeMethod(
            'flickr.photos.delete',
            array('photo_id' => $this->getId())
        );
    }

    /**
     * Change the photo's title and description
     *
     * @param   string $title The title of the photo.
     * @param   string $description The description of the photo.
     * @return  void
     * @throws  Phlickr_Exception
     */
    public function setMeta($title, $description) {
        $resp = $this->getApi()->executeMethod(
            'flickr.photos.setMeta',
            array(
                'photo_id' => $this->getId(),
                'title' => $title,
                'description' => $description
            )
        );
        $this->refresh();
    }

    /**
     * Change the photo's permissions.
     *
     * <b>THIS IS NOT IMPLEMENTED YET</b>.
     *
     * @param   boolean     $forPublic Should the photo be visible to everyone?
     * @param   boolean     $forFriends If the photo is private, should friends
     *          be able to view it?
     * @param   boolean     $forFamily If the photo is private, should family
     *          be able to view it?
     * @param   integer     $whoCanComment 0: nobody, 1: friends & family, 2:
     *          contacts, 3: everybody
     * @param   integer     $whoCanAddMeta 0: nobody, 1: friends & family, 2:
     *          contacts, 3: everybody
     * @return  void
     * @throws  Phlickr_Exception
     * @see     isForPublic(), isForFriends(), isForFamily()
     * @todo    implement this...
     */
    public function setPerms($forPublic, $forFriends, $forFamily, $whoCanComment, $whoCanAddMeta) {
        throw new Exception('sorry, not implemented');
    }

    /**
     * Set the photo's posted date.
     *
     * @param   integer $ts UNIX timestamp.
     * @return  void
     * @since   0.2.1
     * @see     getPostedTimestamp(), getPostedDate()
     */
    public function setPosted($ts) {
        $resp = $this->getApi()->executeMethod(
            'flickr.photos.setDates',
            array('photo_id' => $this->getId(),
                'date_posted' => $ts)
        );
        $this->refresh();
    }

    /**
     * Specify the photo's tags.
     *
     * Note that this will remove any existing tags.
     *
     * @param   array $tags An array of the tags to add to the photo.
     * @return  void
     * @see     addTags(), getTags(), removeTag()
     */
    public function setTags($tags) {
        $quotedTags = '"' . implode('" "', $tags) . '"';

        $resp = $this->getApi()->executeMethod(
            'flickr.photos.setTags',
            array('photo_id' => $this->getId(),
                'tags' => $quotedTags)
        );
        $this->refresh();
    }

    /**
     * Set the photo's taken date.
     *
     * Note that on Windows times before 1970, the UNIX epoch, won't work.
     *
     * The granularity is the accuracy to of the date. At present, Flickr only
     * uses three granularities:
     *   - 0 = Y-m-d H:i:s
     *   - 4 = Y-m
     *   - 6 = Y
     *
     * @param   mixed $date Integer UNIX timestamp or String date.
     * @param   integer $granularity The granularity of the date.
     * @return  void
     * @since   0.2.1
     * @see     getTakenTimestamp(), getTakenGranularity(), getTakenDate()
     */
    public function setTaken($date, $granularity = null) {
        if (is_long($date)) {
            // convert the time stamp to the mysql format required by flickr.
            switch ($granularity) {
            case 6:
                $takenDate = date('Y', $date);
                break;

            case 4:
                $takenDate = date('Y-m-d', $date);
                break;

            case 0:
                // make 0 the default case.
            default:
                $takenDate = date('Y-m-d H:i:s', $date);
                break;
            }
        } else {
            // treat it as a string.
            $takenDate = (string) $date;
        }

        // build a parameter array...
        $params = array(
            'photo_id' => $this->getId(),
            'date_taken' => $takenDate
        );
        // ...include the granularity if it was specified
        if (!is_null($granularity)) {
            $params['date_taken_granularity'] = $granularity;
        }

        $this->getApi()->executeMethod('flickr.photos.setDates', $params);
        $this->refresh();
    }
}
