<?php

/**
 * @version $Id: Gallery.php 499 2006-01-03 22:35:52Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class implements the Phlickr_Framework_IUploadBatch interface.
 */
include_once ('Phlickr/Framework/IUploadBatch.php');

/**
 * Import a {@link http://gallery.sf.net/ Gallery} album so it can be uploaded
 * to Flickr.
 *
 * This class doesn't intentionally make any changes to your galleries but
 * either way you better BACK EVERYTHING UP FIRST. Further, it is assumed
 * that you're using Gallery 1.5.1. If you try using an earlier version you'll
 * probably get some errors.
 *
 * The import process works as follows:
 * - All photos in the album will be loaded, nested albums and movies will be
 *   ignored.
 * - The photo's caption will be used as the Flickr title.
 * - No photo description will be set.
 * - The photo's keywords field will be converted to tags. If double quotes are
 *   found in the keyword string spaces will be used to divide tags with quoted
 *   multi-word tags allowed. If commas are found they'll be used to separate
 *   the tags. In all other cases spaces will be used.
 * - If Gallery lists a capture date for the photo that will be used as Flickr's
 *   taken date.
 * - Finally, a photo set will be created using the album name and description.
 *
 * <code>
 * <?php
 * require_once 'Phlickr/Import/Gallery.php';
 * require_once 'Phlickr/TextUi/UploadListener.php';
 * require_once 'Phlickr/Api.php';
 * require_once 'Phlickr/Uploader.php';
 *
 * // set up the api connection
 * $api = Phlickr_Api::createFrom(API_CONFIG_FILE);
 *
 * // create an uploader
 * $uploader = new Phlickr_Uploader($api);
 *
 * // pass in the gallery directory and name of the album
 * $batch = new Phlickr_Import_Gallery(GALLERY_DIRECTORY, ALBUM_NAME);
 *
 * // if you want, assign additional tags on all photos
 * $uploader->setTags(array('tags', 'for','all photos'));
 *
 * // create a listener to display the upload status
 * $listener =  new Phlickr_TextUi_UploaderListener();
 *
 * // hand the batch and listener the uploader and away it will go.
 * $uploader->uploadBatch($batch, $listener);
 * ?>
 * </code>
 *
 * @package     Phlickr
 * @subpackage  Import
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @since       0.2.5
 */
class Phlickr_Import_Gallery implements Phlickr_Framework_IUploadBatch
{
    /**
     * Array of full filenames.
     * @var array
     */
    protected $_files = array();
    /**
     * Photoset name.
     * @var string
     */
    protected $_setName = '';
    /**
     * Photoset description.
     * @var string
     */
    protected $_setDesc = '';
    /**
     * Photoset's primary photo (full file name)
     * @var string
     */
    protected $_setPrimary = null;
    /**
     * Array of titles keyed on the complete filenames.
     * @var array
     */
    protected $_titles = array();
    /**
     * Array of descriptions keyed on the complete filenames.
     * @var array
     */
    protected $_descriptions = array();
    /**
     * Array of tags keyed on the complete filenames.
     * @var array
     */
    protected $_tags = array();
    /**
     * Array of photo capture dates.
     * @var array
     */
    protected $_dates = array();

    /**
     * Constructor. For the given directory open the description file and
     * load all the titles and descriptions.
     *
     * @param   string $dir Directory where Gallery is installated
     * @param   string $albumName Name of the album to import (this also serves
     *          as the name of the album's directory.
     * @todo    still need to import capture/taken dates.
     */
    public function __construct($dir, $albumName) {
        // initialize gallery
        $initFile = realpath($dir) . DIRECTORY_SEPARATOR . 'init.php';
        if (! include_once($initFile)) {
            throw new Exception("The Gallery could not be loaded ($initFile).");
        }
        // load the album
        $album = new Album;
        if (! $album->load($albumName)) {
            throw new Exception("The album '$albumName' could not be found.");
        }

        // photoset info
        $this->_setName = $album->fields['title'];
        $this->_setDesc = $album->fields['description'];

        // photos
        $dirAlbum = realpath($album->getAlbumDir()) . DIRECTORY_SEPARATOR;
        foreach($album->photos as $item) {
            // only upload photos (ignore albums and movies)
            if (!$item->isAlbum() && !$item->isMovie()) {
                $file = "$dirAlbum{$item->image->name}.{$item->image->type}";
                $this->_files[] = $file;
                $this->_titles[$file] = $item->getCaption();
                $this->_tags[$file] = self::splitKeywords($item->getKeyWords());
                if ($item->getItemCaptureDate()) {
                    $this->_dates[$file] = $item->getItemCaptureDate();
                }
                if ($item->isHighlight()) {
                    $this->_setPrimary = $file;
                }
            }
        }
    }

    static function splitKeywords($keywords) {
        $tags = array();
        $keywords = trim($keywords);

        // check for quotes
        if (false !== stripos($keywords, '"')) {
            // has quotes, split by unquoted spaces
            $instring = false;
            foreach (explode('"', $keywords) as $val) {
                if ($instring) {
                    // add quoted strings to the output array. ignore empty
                    // strings like the one at the begining and end.
                    if ($val != '') {
                        $tags[] = $val;
                    }
                } else {
                    // split take unquoted stuff and split them up again.
                    $temparray = preg_split('/\s/', $val, -1, PREG_SPLIT_NO_EMPTY);
                    $tags = array_merge($tags, $temparray);
                }
                // string was split at the quotes so next pass will we'll be
                // in the other state.
                $instring = !$instring;
            }
        } else {
            // no quotes, check for commas
            if (false !== stripos($keywords, ',')) {
                // split by commas
                $pattern = '/\s*,\s*/';
            } else {
                // split by spaces
                $pattern = '/\s+/';
            }
            $tags = preg_split($pattern, $keywords, -1, PREG_SPLIT_NO_EMPTY);
        }

        return $tags;
    }

    public function getFiles() {
        return $this->_files;
    }

    public function isSetWanted() {
        return true;
    }

    public function getSetTitle() {
        return $this->_setName;
    }

    public function getSetDescription() {
        return $this->_setDesc;
    }

    public function getSetPrimary() {
        return $this->_setPrimary;
    }

    public function getTitleForFile($filePath) {
        return (string) $this->_titles[$filePath];
    }

    public function getDescriptionForFile($filePath) {
        return (string) $this->_descriptions[$filePath];
    }

    public function getTagsForFile($filePath) {
        return $this->_tags[$filePath];
    }

    public function getTakenDateForFile($filePath) {
        return $this->_dates[$filePath];
    }
}
