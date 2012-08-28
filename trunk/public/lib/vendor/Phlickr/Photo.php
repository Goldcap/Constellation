<?php

/**
 * @version $Id: Photo.php 523 2006-08-28 18:30:20Z drewish $
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
 * Phlickr_Photo allows the manipuation of a Flickr photo.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @see     Phlickr_AuthedPhoto
 * @todo    Add sample code.
 * @todo    Implement getNoteList() (after creating Phlickr_Note and
 *          Phlickr_NoteList classes).
 * @since   0.1.0
 */
class Phlickr_Photo extends Phlickr_Framework_ObjectBase {
    /**
     * The name of the XML element in the response that defines the object.
     *
     * @var string
     */
    const XML_RESPONSE_ELEMENT = 'photo';
    /**
     * The name of the Flickr API method that provides the info on this object.
     *
     * @var string
     */
    const XML_METHOD_NAME = 'flickr.photos.getInfo';
    /**
     * Small square, 75x75px photo size.
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_75PX = 's';
    /**
     * Thumbnail, 100px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_100PX = 't';
    /**
     * Small, 240px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_240PX = 'm';
    /**
     * Medium, 500px on longest side
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_500PX = '-';
    /**
     * Large, 1024px on longest side (only exists for very large original images)
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_1024PX = 'b';
    /**
     * Original image, either a jpg, gif or png, depending on source format.
     * Call getSizes() to find out the format.
     *
     * @link http://www.flickr.com/services/api/misc.urls.html
     * @see buildImgUrl(), getSizes()
     * @var string
    */
    const SIZE_ORIGINAL = 'o';

    /**
     * Constructor.
     *
     * You can construct a photo from an Id or XML.
     *
     * @param object Phlickr_API $api
     * @param mixed $source string Id, object SimpleXMLElement
     * @throws Phlickr_Exception, Phlickr_ConnectionException, Phlickr_XmlParseException
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
        return array('photo_id' => (string) $id);
    }

    public function getId() {
        return (string) $this->_cachedXml['id'];
    }

    /**
     * Is this photo visible to the public?
     *
     * @return  boolean
     * @since   0.2.2
     * @see     isForFriends(), isForFamily(), AuthedPhoto::setPerms()
     */
    public function isForPublic() {
        if (!isset($this->_cachedXml->visibility['ispublic'])) {
            $this->load();
        }
        return ($this->_cachedXml->visibility['ispublic'] == '1');
    }
    /**
     * Is this photo visible to friends?
     *
     * If isForPublic() is true, this setting doesn't matter much.
     *
     * @return  boolean
     * @since   0.2.2
     * @see     isForPublic(), isForFamily(), AuthedPhoto::setPerms()
     */
    public function isForFriends() {
        if (!isset($this->_cachedXml->visibility['isfriend'])) {
            $this->load();
        }
        return ($this->_cachedXml->visibility['isfriend'] == '1');
    }
    /**
     * Is this photo visible to friends?
     *
     * If isForPublic() is true, this setting doesn't matter much.
     *
     * @return  boolean
     * @since   0.2.2
     * @see     isForPublic(), isForFriends(), AuthedPhoto::setPerms()
     */
    public function isForFamily() {
        if (!isset($this->_cachedXml->visibility['isfamily'])) {
            $this->load();
        }
        return ($this->_cachedXml->visibility['isfamily'] == '1');
    }

    /**
     * Get the photo's description
     *
     * @return  string
     * @see     AuthedPhoto::setMeta()
     */
    public function getDescription() {
        if (!isset($this->_cachedXml->description)) {
            $this->load();
        }
        return (string) $this->_cachedXml->description;
    }

    /**
     * Get a string of the day and time when the photo was posted.
     *
     * The date format string is 'Y-m-d H:i:s' (2004-11-19 12:51:19).
     *
     * This is a GMT time, you'll need to take local timezones into account.
     *
     * @return  integer UNIX timestamp.
     * @see     getPostedTimestamp(), getTakenDate()
     * @since   0.2.1
     */
    public function getPostedDate() {
        return date('Y-m-d H:i:s', $this->getPostedTimestamp());
    }
    /**
     * Get the timestamp of the photo posting date.
     *
     * This is a GMT time, you'll need to take local timezones into account.
     *
     * Note: on Windows (and OSX?) negative timestamps (meaning dates before
     * 1970) are not allowed.  This shouldn't be a problem considering that
     * Flickr wasn't around in 1969.
     *
     * @return  integer UNIX timestamp.
     * @see     getPostedDate(), getTakenTimestamp()
     * @since   0.2.1
     */
    public function getPostedTimestamp() {
        if (!isset($this->_cachedXml->dates['posted'])) {
            $this->load();
        }
        return (integer) $this->_cachedXml->dates['posted'];
    }

    /**
     * Get the granularity of the date this photo was taken.
     *
     * The granularity is the accuracy to of the date. At present, Flickr only
     * uses three granularities:
     *   - 0 = Y-m-d H:i:s
     *   - 4 = Y-m
     *   - 6 = Y
     *
     * @return  integer 0-10.
     * @see     getTakenDate(), getTakenTimestamp()
     * @since   0.2.1
     */
    public function getTakenGranularity() {
        if (!isset($this->_cachedXml->dates['takengranularity'])) {
            $this->load();
        }
        return (integer) $this->_cachedXml->dates['takengranularity'];
    }
    /**
     * Get a string of the day and time when the photo was taken.
     *
     * The date format string is 'Y-m-d H:i:s' (2004-11-19 12:51:19).
     *
     * Don't forget that the taken dates have a "graularity" to them.
     *
     * @return  string
     * @since   0.2.1
     * @see     getTakenGranularity(), getTakenTimestamp(), getPostedDate()
     */
    public function getTakenDate() {
        if (!isset($this->_cachedXml->dates['taken'])) {
            $this->load();
        }
        return (string) $this->_cachedXml->dates['taken'];
    }
    /**
     * Get the timestamp when this photo was taken.
     *
     * Note that on Windows negative timestamps (meaning dates before 1970)
     * aren't supported so you might need to call getTakeDate() instead.
     *
     * Don't forget that the taken dates have a "graularity" to them.
     *
     * @return  integer UNIX timestamp.
     * @since   0.2.1
     * @see     getTakenDate(), getTakenGranularity(), getPostedTimestamp()
     */
    public function getTakenTimestamp() {
        return strtotime($this->getTakenDate());
    }

    /**
     * Return the photo's secret (used to bypass permissions checks)
     *
     * @return  string
     * @see     buildImgUrl()
     */
    public function getSecret() {
        if (!isset($this->_cachedXml['secret'])) {
            $this->load();
        }
        return (string) $this->_cachedXml['secret'];
    }

    /**
     * Return the number of the server where this photo is stored.
     *
     * @return  integer
     * @see     buildImgUrl()
     */
    public function getServer() {
        if (!isset($this->_cachedXml['server'])) {
            $this->load();
        }
        return (integer) $this->_cachedXml['server'];
    }

    /**
     * Returns an array indicating the available sizes of this photo.
     *
     * The returned array contains arrays keyed off the SIZE_* constants.
     * Each sub-array contains width, height, and image type.
     *
     * For example:
     * <code>
     * array(
     *      self::SIZE_75PX => array(75, 75, type=>'jpg'),
     *      self::SIZE_100PX => array(100, 62, type=>'jpg'),
     *      self::SIZE_240PX => array(112, 69, type=>'jpg'),
     *      self::SIZE_ORIGINAL => array(112, 69, type=>'png')
     *  );
     *  </code>
     *
     * Sizes for SIZE_75PX, SIZE_100PX, SIZE_240PX, and SIZE_ORIGINAL should
     * always be returned. When the image has a dimension greater than
     * SIZE_500PX or SIZE_1024PX then they too will be returned.
     *
     * @return  array containing arrays with width at index 0, height at index
     *          1, and and the file type at index 2.
     * @since   0.2.3
     * @see     buildImgUrl(), SIZE_75PX, SIZE_100PX, SIZE_240PX, SIZE_500PX,
     *          SIZE_1024PX, SIZE_ORIGINAL
     */
    public function getSizes() {
        $resp = $this->getApi()->executeMethod(
            'flickr.photos.getSizes',
            array('photo_id' => $this->getId())
        );
        $ret = array();
        foreach ($resp->xml->sizes->size as $size) {
            // convert their label into our constants
            switch ($size['label']) {
            case 'Square':
                $index = self::SIZE_75PX;
                break;

            case 'Thumbnail':
                $index = self::SIZE_100PX;
                break;

            case 'Small':
                $index = self::SIZE_240PX;
                break;

            case 'Medium':
                $index = self::SIZE_500PX;
                break;

            case 'Large':
                // Flickr's getSize returns some odd results. small images (say
                // 100x100px) have a "large" size but not an "original"
                // size. it seems to me that there should always be an
                // "orgiginal" size and only a "large" if the image has a
                // dimension greater than 1024px.
                //
                // if no original is listed (ie no size element with attribute
                // label="Original", use this in its place. if there is an
                // original, use this as the large image.
                if (count($resp->xml->xpath("//size[@label='Original']")) == 1) {
                    $index = self::SIZE_1024PX;
                } else {
                    $index = self::SIZE_ORIGINAL;
                }
                break;

            case 'Original':
                $index = self::SIZE_ORIGINAL;
                break;
            }

            // put the width, height, and type into a sub-array
            $ret[$index] = array(
                (integer) $size['width'],
                (integer) $size['height'],
                'type' => pathinfo($size['source'], PATHINFO_EXTENSION)
            );
        }

        return $ret;
    }

    /**
     * Get the tags associated with this photo.
     *
     * These tags will not contain spaces or other non-alphanumeric characters.
     * If you need these use getRawTags().
     *
     * @return  array
     * @see     addTags(), getRawTags(), removeTag(), Phlickr_AuthedPhoto::setTags()
     */
    public function getTags() {
        $ret = array();
        if (!isset($this->_cachedXml->tags)) {
            $this->load();
        }
        foreach ($this->_cachedXml->tags[0] as $tag) {
            $ret[] = (string) $tag;
        }
        return $ret;
    }

    /**
     * Get the raw tags associated with this photo.
     *
     * This array of tags has no spaces and other non-alphanumeric characters.
     * If you need these use getRawTags().
     *
     * @return  array
     * @see     addTags(), getTags(), removeTag(), Phlickr_AuthedPhoto::setTags()
     */
    public function getRawTags() {
        $ret = array();
        if (!isset($this->_cachedXml->tags)) {
            $this->load();
        }
        foreach ($this->_cachedXml->tags[0] as $tag) {
            $ret[] = (string) $tag['raw'];
        }
        return $ret;
    }

    /**
     * Get the photo's title.
     *
     * @return  string
     * @see     AuthedPhoto::setMeta()
     */
    public function getTitle() {
        if (!isset($this->_cachedXml->title)) {
            // some of the short photo definitions have the title in the photo
            // attribute. if it isn't, get the full (long) version.
            if (isset($this->_cachedXml['title'])) {
                return (string) $this->_cachedXml['title'];
            } else{
                $this->load();
            }
        }
        return (string) $this->_cachedXml->title;
    }

    /**
     * Return the user id of the photo's owner.
     *
     * @return  string
     * @since   0.2.1
     * @see     Phlickr_User
     */
    public function getUserId() {
        if (!isset($this->_cachedXml->owner['nsid'])) {
            // some of the short photo definitions have the owner in the photo
            // element, if it isn't just get the full version
            if (isset($this->_cachedXml['owner'])) {
                return (string) $this->_cachedXml['owner'];
            } else {
                $this->load();
            }
        }
        return (string) $this->_cachedXml->owner['nsid'];
    }

    /**
     * Add a tags to the photo
     *
     * @param   array $tags An array of the tags to add to the photo.
     * @return  void
     * @see     getTags(), removeTag()
     */
    public function addTags($tags) {
        $quotedTags = '"' . implode($tags, '" "') . '"';

        $resp = $this->getApi()->executeMethod(
            'flickr.photos.addTags',
            array('photo_id' => $this->getId(), 'tags' => $quotedTags)
        );
        $this->refresh();
    }

    /**
     * Remove a tag from the photo.
     *
     * @param   string $tag The name of the tag to remove.
     * @return  void
     * @see     getTags(), addTags()
     */
    public function removeTag($tag) {
        $tagid = 0;
        // this bit might be able to be refactored into $this->getTags()
        if (!isset($this->_cachedXml->tags[0])) {
            $this->load();
        }
        foreach ($this->_cachedXml->tags[0] as $xmlTag) {
            // compare both the raw and cleaned tags
            if ((string) $xmlTag == $tag || (string) $xmlTag['raw'] == $tag) {
                $tagid = (integer) $xmlTag['id'];
                break;
            }
        }
        $resp = $this->getApi()->executeMethod(
            'flickr.photos.removeTag',
            array('photo_id' => $this->getId(), 'tag_id' => $tagid)
        );
        $this->refresh();
    }

    /**
     * Build a URL to access the photo.
     *
     * @return  string
     * @see     buildImgUrl()
     */
    public function buildUrl() {
        return "http://flickr.com/photos/{$this->getUserId()}/{$this->getId()}/";
    }

    /**
     * Build a URL to access a photo suitable for use in an IMG tag.
     *
     * Phlickr defines several constants to specify desired image size
     * (SIZE_75PX, SIZE_100PX, SIZE_240PX, SIZE_500PX, SIZE_1024PX,
     * SIZE_ORIGINAL). For a complete list of sizes check the Flickr API's
     * {@link http://www.flickr.com/services/api/misc.urls.html Photo URL
     * section}.
     *
     * @param   string $size A character representing the size of photo. It's a
     *          good idea to use one of the SIZE_* constants.
     * @return  string
     * @see     getSizes(), saveAs(), SIZE_75PX, SIZE_100PX, SIZE_240PX,
     *          SIZE_500PX, SIZE_1024PX, SIZE_ORIGINAL, buildUrl()
     */
    public function buildImgUrl($size = self::SIZE_240PX) {
        $type = 'jpg';
        $sizeStr = "_$size";

        switch ($size) {
        case self::SIZE_500PX:
            $sizeStr = '';
            break;

        case self::SIZE_ORIGINAL:
            $sizes = $this->getSizes();
            $type = $sizes[self::SIZE_ORIGINAL]['type'];
            break;
        }

        $url = sprintf("http://static.flickr.com/%d/%d_%s%s.%s",
            $this->getServer(), $this->getId(), $this->getSecret(), $sizeStr, $type);
        return $url;
    }


    /**
     * Save a copy of the photo to a local file.
     *
     * Note: Flickr returns the original images in their original format so it's
     * a good idea to call getSizes() to find out the file type.
     *
     * @param   string $filename The complete filename the photo should be
     *          saved as.
     * @param   string $size A character representing the size of photo. It's a
     *          good idea to use one of the SIZE_* constants.
     * @return  void
     * @see     getSizes(), buildImgUrl(), SIZE_75PX, SIZE_100PX, SIZE_240PX,
     *          SIZE_500PX, SIZE_1024PX, SIZE_ORIGINAL
     * @since   0.2.0
     */
    public function saveAs($filename, $size = self::SIZE_240PX) {
        $url = self::buildImgUrl($size);

        $fh = fopen($filename, 'wb');

        // set up the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // make sure problems are caught
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        // save to the file
        curl_setopt($ch, CURLOPT_FILE, $fh);

        $result = curl_exec($ch);
        fclose($fh);

        // check for errors
        if (0 != curl_errno($ch)) {
            $ex = new Phlickr_ConnectionException(
                'Request failed. ' . curl_error($ch), curl_errno($ch), $url);
            curl_close($ch);
            throw $ex;
        }
        curl_close($ch);
    }
}
