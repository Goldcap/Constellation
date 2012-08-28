<?php

/**
 * @version $Id: Uploader.php 529 2006-10-30 21:49:21Z drewish $
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
 * One or more methods returns Phlickr_Photo objects.
 */
require_once 'Phlickr/AuthedPhoto.php';
/**
 * uploadBatch() uses this to create a photoset.
 */
require_once 'Phlickr/AuthedPhotosetList.php';
/**
 * uploadBatch() accepts Phlickr_Framework_IUploadBatch as a parameter.
 */
require_once 'Phlickr/Framework/IUploadBatch.php';
/**
 * uploadBatch() accepts Phlickr_Framework_IUploadListener as a parameter.
 */
require_once 'Phlickr/Framework/IUploadListener.php';

/**
 * Uploads photos to Flickr.
 *
* Sample usage:
 * <code>
 * <?php
 * require_once 'Phlickr/Api.php';
 * require_once 'Phlickr/Uploader.php';
 *
 * define('UPLOAD_DIRECTORY', 'D:\sf\phlickr\samples\files_to_testupload');
 * define('PHOTO_EXTENSION', '.jpg');
 *
 * // create an api
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_API_TOKEN);
 * // create an uploader
 * $uploader = new Phlickr_Uploader($api);
 * // array to keep track of the photo ids as they're uploaded
 * $photo_ids = array();
 * // create a DirectoryIterator (part of the Standard PHP Library)
 * $di = new DirectoryIterator(UPLOAD_DIRECTORY);
 *
 * // upload all the photos in the directory
 * foreach($di as $item) {
 *     // only upload files with the given extension
 *     if ($item->isFile()) {
 *         $extension = substr($item, - strlen(PHOTO_EXTENSION));
 *         if (strtolower($extension) === strtolower(PHOTO_EXTENSION)) {
 *             print "Uploading $item...\n";
 *             // upload the photo
 *             $id = $uploader->upload($item->getPathname(), 'a title',
 *                 'a description', 'some tags');
 *             // save the photo's id to an array
 *             $photo_id[] = $id;
 *         }
 *     }
 * }
 *
 * // print out the post-upload edit link.
 * if (count($photo_ids)) {
 *     printf("All done! If you care to make some changes:\n%s",
 *         $uploader->buildEditUrl($photo_ids));
 * }
 * //?//>
 * </code>
 *
 *
 * This class is responsible for:
 * - Uploading a file to Flickr with the specified title, description, tags
 *   and permissions.
 *
 * To do this, makes use public static methods of the Phlickr_Request and
 * Phlickr_Response classes.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @since   0.1.0
 */
class Phlickr_Uploader {
    /**
     * @var Phlickr_API
     */
    protected $_api = null;
    /**
     * Should photos be visible to everyone?
     *
     * @var boolean
     */
    protected $_forPublic = true;
    /**
     * Should photos be visible to friend?
     *
     * @var boolean
     */
    protected $_forFriends = true;
    /**
     * Should photos be visible to family?
     *
     * @var boolean
     */
    protected $_forFamily = true;
    /**
     * Array of tags that will be added to the photo.
     *
     * @var array
     */
    protected $_tags = array();

    /**
     * The URL that photo uploads should be POSTed to.
     *
     * @var string
     */
    const UPLOAD_URL = 'http://www.flickr.com/services/upload/';
    /**
     * The URL that the user should be redirected to for editing uploads.
     *
     * @var string
     */
    const EDIT_URL = 'http://www.flickr.com/tools/uploader_edit.gne';
    /**
     * Number of seconds to wait for an upload to complete.
     * @var integer
     */
    const TIMEOUT = 200;

    /**
     * Constructor
     *
     * @param Phlickr_API $api
     */
    function __construct(Phlickr_Api $api) {
        $this->_api = $api;
    }

    /**
     * Return a URL that the user can visit to make changes to uploaded photos.
     *
     * @param array $ids Array of photos ids returned by upload()
     * @return string
     */
    static function buildEditUrl($ids) {
        return self::EDIT_URL . '?ids=' . implode(',', $ids);
    }

    /**
     * Will the uploaded photos be publicly visible?
     *
     * @return  boolean
     * @since   0.2.2
     * @see     setPerms()
     */
    public function isForPublic() {
        return $this->_forPublic;
    }
    /**
     * Will the uploaded photos be visible to contacts marked as friends?
     *
     * @return  boolean
     * @since   0.2.2
     * @see     setPerms()
     */
    public function isForFriends() {
        return $this->_forFriends;
    }
    /**
     * Will the uploaded photos be visible to contacts marked asfamily?
     *
     * @return  boolean
     * @since   0.2.2
     * @see     setPerms()
     */
    public function isForFamily() {
        return $this->_forFamily;
    }

    /**
     * Get an array of the tags that will be added to the photos.
     *
     * @return  array
     * @since   0.2.2
     * @see     setTags()
     */
    public function getTags() {
        return $this->_tags;
    }

    /**
     * Set the visibility permission for the uploaded photos.
     *
     * If the photo is visible to public, the friends and family settings are
     * pretty much ignored.
     *
     * @param   boolean $public Can anyone view the photos?
     * @param   boolean $friends Can contacts marked as friends view the photos?
     * @param   boolean $family Can contacts marked as family view the photos?
     * @return  void
     * @see     isForPublic(), isForFriends(), isForFamily()
     * @since   0.2.2
     */
    public function setPerms($public, $friends, $family) {
        $this->_forPublic = (boolean) $public;
        $this->_forFriends =(boolean) $friends;
        $this->_forFamily = (boolean) $family;
    }

    /**
     * Set the tags that will be added to the photos.
     *
     * @param   array $tags Array of tag strings
     * @return  void
     * @since   0.2.2
     * @see     getTags()
     */
    public function setTags($tags) {
        $this->_tags = (array) $tags;
    }

    /**
     * Upload a photo to Flickr.
     *
     * If tags are specified, they'll be appended to those listed in getTags().
     * If permissions are specified, they'll override those set by setPerms().
     *
     * The permissions assigned will based on those set using setPerms().
     *
     * @param   string $fullFilePath Full path and file name of the photo.
     * @param   string $title Photo title.
     * @param   string $desc Photo description.
     * @param   string|array $tags A space separated list of tags to add to the photo.
     *          These will be added to those listed in getTags().
     * @return  string id of the new photo
     * @throws  Phlickr_ConnectionException
     * @see     isForPublic(), isForFriends(), isForFamily(), getTags()
     */
    public function upload($fullFilePath, $title = '', $desc = '', $tags = '') {
        if (!file_exists($fullFilePath) || !is_readable($fullFilePath)) {
            throw new Phlickr_Exception(
                "The file '{$fullFilePath}' does not exist or can not be accessed."
            );
        }

        // concat the class's tags with this photos.
        if (is_array($tags)) {
            $tags = '"' . implode('" "', $this->_tags + $tags) . '"';
        } elseif ($tags) {
            $tags = '"' . implode('" "', $this->_tags) . '" ' . (string) $tags;
        } else {
            $tags = '';
        }

        // get the parameters ready for signing ...
        $params = array_merge(
            $this->_api->getParamsForRequest(),
            array(
                'title' => $title,
                'description' => $desc,
                'tags' => $tags,
                'is_public' => (integer) $this->_forPublic,
                'is_friend' => (integer) $this->_forFriends,
                'is_family' => (integer) $this->_forFamily
            )
        );
        // ... compute a signature ...
        ksort($params);
        $signing = '';
        foreach($params as $key => $value) {
            $signing .= $key . $value;
        }
        $params['api_sig'] = md5($this->_api->getSecret() . $signing);
        // ... then put in the photo. the @ tells CURL to upload the file's
        // contents
        $params['photo'] = '@' . $fullFilePath;

        // use the requst to submit
        $result = Phlickr_Request::SubmitHttpPost(self::UPLOAD_URL, $params, self::TIMEOUT);
        // use the reponse object to parse the results
        $resp = new Phlickr_Response($result, true);
        // return a photo id
        return  (string) $resp->getXml()->photoid;
    }

    /**
     * Upload a batch of files to Flickr.
     *
     * @param   Phlickr_Uploader $uploader
     * @param   Phlickr_Framework_IUploadBatch $batch Provides a list of files
     *          and information on them.
     * @param   Phlickr_Framework_IUploadListener $listener Listens to event
     *          notifications on the status of the upload process.
     * @return  array An array of Phlickr_AuthedPhoto objects with the ids as
     *          the keys.
     * @uses    upload() to do the actual file uploads.
     * @uses    Phlickr_AuthedPhotosetList::create() to create the photoset
     *          if it's requested.
     * @uses    Phlickr_AuthedPhotoset::editPhotos() to put the photos into the
     *          set and select the primary photo.
     * @uses    Phlickr_AuthedPhoto::setTaken() to set the taken date if it's
     *          provided.
     * @since   0.2.5
     */
    function uploadBatch(Phlickr_Framework_IUploadBatch $batch, Phlickr_Framework_IUploadListener $listener)
    {
        // array of uploaded photo objects keyed by id
        $photos = array();
        // array of photo ids keyed by original filename
        $photoIds = array();
        // notify that the upload is starting
        $listener->beforeUpload();

        foreach($batch->getFiles() as $file) {
            // notify that a file will be uploaded
            $listener->beforeFileUpload($file);

            // fetch the info for the photo
            $title = $batch->getTitleForFile($file);
            $desc = $batch->getDescriptionForFile($file);
            $tags = $batch->getTagsForFile($file);

            // upload it
            try {
                $photoId = $this->upload($file, $title, $desc, $tags);

                // some times it takes a second for the photo to show up.
                try {
                    $photo = new Phlickr_AuthedPhoto($this->_api, $photoId);
                } catch (Phlickr_MethodFailureException $ex) {
                    // give it 10 seconds and try again.
                    sleep(10);
                    $photo = new Phlickr_AuthedPhoto($this->_api, $photoId);
                }

                // keep a filename:id mapping...
                $photoIds[$file] = $photoId;
                // ... and a id:photo mapping
                $photos[$photoId] = $photo;

                // notify of success
                $listener->afterFileUpload($file, $photo);
            } catch (Phlickr_Exception $ex) {
                // notify of failure
                $listener->failedFileUpload($file, $ex);
            }

            // assign the taken date if one is provided
            try {
                $taken = $batch->getTakenDateForFile($file);
                if ($taken) {
                    $photo->setTaken($taken);
                }
            } catch (Phlickr_Exception $ex) {
                // don't worry about it.
            }
        }

        // create a photo set? only if we've got photos and a name.
        if ($photoIds && $batch->isSetWanted()) {
            // figure out the primary photo (if none was specified use the
            // first image)
            if (array_key_exists($batch->getSetPrimary(), $photoIds)) {
                $primaryId = $photoIds[$batch->getSetPrimary()];
            } else {
                // reset $photoIds so we can use current() to find the first value
                reset($photoIds);
                $primaryId = current($photoIds);
            }

            // create the photoset
            $list = new Phlickr_AuthedPhotosetList($this->_api);
            $set = $list->create(
                $batch->getSetTitle(),
                $batch->getSetDescription(),
                $primaryId
            );

            // add the photos to the set
            $set->editPhotos($primaryId, $photoIds);

            // notify of the photoset creation
            $listener->afterCreatePhotoset($set);
        }

        // notify that the upload is complete
        $listener->afterUpload($photos);

        return $photos;
    }
}
