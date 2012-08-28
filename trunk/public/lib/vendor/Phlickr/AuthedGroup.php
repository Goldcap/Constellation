<?php

/**
 * @version $Id: AuthedGroup.php 506 2006-01-28 04:17:13Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class extends Group_Photo.
 */
require_once 'Phlickr/Group.php';

/**
 * Phlickr_AuthedGroup allows a user to photos to groups they are a member of.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_Group
 * @since   0.2.1
 * @todo    Add sample code.
 */
class Phlickr_AuthedGroup extends Phlickr_Group {
    /**
     * Constructor.
     *
     * You can construct a group from an Id or XML.
     *
     * @param   object Phlickr_API $api
     * @param   mixed $source string Id, object SimpleXMLElement
     * @throws  Phlickr_Exception, Phlickr_ConnectionException,
     *          Phlickr_XmlParseException
     */
    function __construct(Phlickr_Api $api, $source) {
        parent::__construct($api, $source);
    }

    /**
     * Add a photo to the group.
     *
     * @param   string $photo Flickr photo id.
     * @return  void
     * @see     remove()
     * @since   0.2.1
     */
    function add($photo) {
        $this->getApi()->executeMethod(
            'flickr.groups.pools.add',
            array(
                'photo_id' => $photo,
                'group_id' => $this->getId()
            )
        );
    }

    /**
     * Remove a photo from the group.
     *
     * @param   string $photo Flickr photo id.
     * @return  void
     * @see     remove()
     * @since   0.2.1
     */
    function remove($photo) {
        $this->getApi()->executeMethod(
            'flickr.groups.pools.remove',
            array(
                'photo_id' => $photo,
                'group_id' => $this->getId()
            )
        );
    }
}
