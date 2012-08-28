<?php

/**
 * @version $Id: AuthedUser.php 500 2006-01-03 23:29:08Z drewish $
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
 * This class extends Phlickr_User.
 */
require_once 'Phlickr/User.php';
/**
 * One or more methods returns Phlickr_AuthedPhotosetList objects.
 */
require_once 'Phlickr/AuthedPhotosetList.php';
/**
 * This class extends Phlickr_User to perform actions on a logged in user.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/AuthedUser.php';
 *
 * // instantiate the object
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 * $user = new Phlickr_AuthedUser($api);
 *
 * // go through your favorite photos
 * $photolist = $user->getFavoritePhotoList();
 *
 * // add a new favorite photo
 * $user->addFavorite(12871545);
 *
 * // remove a favorite photo
 * $user->removeFavorite(12871545);
 * ?>
 * </code>
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_User
 * @since   0.1.6
 */
class Phlickr_AuthedUser extends Phlickr_User {
    /**
     * Constructor.
     *
     * @param   object Phlickr_Api $api This object must have valid
     *          authentication information or an exception will be thrown.
     */
    function __construct(Phlickr_Api $api) {
        assert($api->isAuthValid());
        parent::__construct($api, $api->getUserId());
    }

    /**
     * Return a UserList of this user's contacts.
     *
     * @return  object Phlickr_UserList
     */
    public function getContactUserList() {
        $request = $this->getApi()->createRequest(
            'flickr.contacts.getList',
            array('user_id'=>$this->getId())
        );
        return new Phlickr_UserList($request);
    }

    /**
     * Return a PhotoList of this user's favorite photos.
     *
     * @param   integer $perPage Number of photos per page
     * @return  object Phlickr_PhotoList
     */
    public function getFavoritePhotoList($perPage = Phlickr_PhotoList::PER_PAGE_DEFAULT) {
        $request = $this->getApi()->createRequest('flickr.favorites.getList',
            array('user_id'=>$this->getId())
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
            'flickr.groups.pools.getGroups',
            array('user_id'=>$this->getId())
        );
        return new Phlickr_GroupList($request);
    }

    /**
     * Return a PhotoList of this user's photos.
     *
     * @param   integer $perPage Number of photos per page
     * @return  object Phlickr_PhotoList
     */
    public function getPhotoList($perPage = Phlickr_PhotoList::PER_PAGE_DEFAULT) {
        $request = $this->getApi()->createRequest('flickr.photos.search',
            array('user_id'=>$this->getId())
        );
        return new Phlickr_PhotoList($request, $perPage);
    }

    /**
     * Return a PhotosetList for this user.
     *
     * @return  object Phlickr_PhotosetList
     */
    public function getPhotosetList() {
        return new Phlickr_AuthedPhotosetList($this->getApi());
    }

    /**
     * Add a photo to the user's list of favorites.
     *
     * Add the photo and return the updated favorite list.
     *
     * @param   interger Photo id.
     * @return  object Phlickr_PhotoList the new favorites. Note: the photo list
     *          is refreshed before it is returned but the new favorite might
     *          not be listed. If this is the case, you'll need to call
     *          refresh() again.
     * @since   0.1.7
     */
    public function addFavorite($photo_id) {
        $resp = $this->getApi()->executeMethod(
            'flickr.favorites.add',
            array('photo_id' => $photo_id)
        );

        // call refresh to clear out the cached favorite list
        $ret = $this->getFavoritePhotoList();
        $ret->refresh();
        return $ret;
    }

    /**
     * Remove a photo from the user's list of favorites.
     *
     * Remove the photo and return the updated favorite list.
     *
     * @param   interger Photo id.
     * @return  object Phlickr_PhotoList the new favorites. Note: the photo list
     *          is refreshed before it is returned but the old favorite might
     *          still be listed. If this is the case, you'll need to call
     *          refresh().
     * @since   0.1.7
     */
    public function removeFavorite($photo_id) {
        $resp = $this->getApi()->executeMethod(
            'flickr.favorites.remove',
            array('photo_id' => $photo_id)
        );

        $ret = $this->getFavoritePhotoList();
        // call refresh to clear out the cached favorite list
        $ret->refresh();
        return $ret;
    }
}
