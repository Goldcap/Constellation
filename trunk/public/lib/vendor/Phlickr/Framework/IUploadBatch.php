<?php

/**
 * @version $Id: IUploadBatch.php 499 2006-01-03 22:35:52Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * An interface for providing information needed when uploading a batch of
 * photos to Flickr. The interface acts as a source for photos' file names,
 * titles, descriptions, taken dates and tags.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @since       0.2.5
 * @see         Phlickr_Uploader::uploadBatch()
 */
interface Phlickr_Framework_IUploadBatch {
    /**
     * Return an array of full file names to be uploaded. These will be uploaded
     * in the order given.
     *
     * @return  array of full file names.
     */
    public function getFiles();

    /**
     * Should a photoset be created?
     *
     * @return  boolean
     * @see     getSetTitle(), getSetDescription(), getSetPrimary()
     */
    public function isSetWanted();

    /**
     * Return the name of the photoset. If isSetWanted() is true this should
     * return something.
     *
     * @return  string Name of the photoset.
     * @see     isSetWanted(), getSetDescription(), getSetPrimary()
     */
    public function getSetTitle();

    /**
     * Optional, photoset description.
     *
     * @return  string Description of the photoset.
     * @see     isSetWanted(), getSetTitle(), getSetPrimary()
     */
    public function getSetDescription();

    /**
     * Which photo should be the primary photo in the set.
     *
     * @return  string The photo's full filename or an empty string if there's
     *          no preference.
     * @see     isSetWanted(), getSetTitle(), getSetDescription()
     */
    public function getSetPrimary();

    /**
     * Get a photo title for a file. The $fullPath parameter will be a value
     * from the array returned by getFiles().
     *
     * @param   string $filePath Full path to a photo.
     * @return  string title for the photo
     */
    public function getTitleForFile($filePath);

    /**
     * Get a photo description for a file.
     *
     * @param   string $filePath Full path to a photo.
     * @return  string Photo's description
     */
    public function getDescriptionForFile($filePath);

    /**
     * Get an array of tags for a file.
     *
     * If the file specified in $fullPath doesn't exist in the list returned by
     * {@link getFiles()} then the return value is undefined.
     *
     * @param   string $filePath Full path to a photo.
     * @return  array of tags to add to the photo.
     */
    public function getTagsForFile($filePath);

    /**
     * Get a taken time string for a file. If a value that evaluates to false
     * is returned, the photo's taken date/time will not be set.
     *
     * @param   string $filePath Full path to a photo.
     * @return  false|string|integer This could be a UNIX timestamp or a MySQL
     *          date time string.
     * @see     Phlickr_AuthedPhoto::setTaken()
     */
    public function getTakenDateForFile($filePath);
}
