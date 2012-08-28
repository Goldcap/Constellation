<?php

/**
 * @version $Id: Cache.php 500 2006-01-03 23:29:08Z drewish $
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
 * The Phlickr_Cache stores responses to previous Flickr API calls.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 *
 * $cache = new Phlickr_Cache();
 *
 * // add a value to the cache.
 * $cache->set('http://example.com/', 'sample response');
 *
 * // verify that it exists in the cache.
 * print $cache->has('http://example.com/');
 *
 * // retrieve a value from the cache.
 * print $cache->has('http://example.com/');
 * ?>
 * </code>
 *
 *
 * Serialization Example:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * include_once 'Phlickr/User.php';
 *
 * // file to save the cache into
 * define('FILENAME', '/tmp/phlickr.cache');
 *
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 *
 * // if there's a saved cache file load and unserialize it.
 * $api->setCache(Phlickr_Cache::createFrom(FILENAME));
 *
 * // print out your 10 newest photos
 * $user = new Phlickr_User($api, $api->getUserId());
 * foreach ($user->getPhotoList()->getPhotos(10) as $photo) {
 *     // getting the tags causes a separate request. caching is a big help here.
 *     printf("Photo: %s\n\tTags: %s \n",
 *         $photo->getTitle(),
 *         implode(',', $photo->getTags())
 *     );
 * }
 *
 * // serialize and save the cache file
 * $api->getCache()->saveAs(FILENAME);
 * ?>
 * </code>
 *
 * This class is responsible for:
 * - Associating a full request's URL with it's XML response. This means that
 *   separate users should be able to share a cache.
 * - Tracking the expiration times of each URL so that older, possibly invalid
 *   data is removed from the cache.
 * - Hashing URLs so minimize the risk of displaying passwords that are
 *   included as parameters.
 * - Providing a single class to serialize to allow request results to be
 *   stored across sessions.
 *
 * There were two motivations behind the design of this class:
 * - Allowing offline unit testing. This speeds up the time it takes to run the
 *   tests and allows me to use the real Phlickr classes instead of mocks or
 *   stubs.
 * - Letting me run one large search for photos with a certain tag, then
 *   display a unique photo each time a webpage was displayed. I didn't want to
 *   wait for a request each time the page was loaded and wanted the the photos
 *   to be randomized.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @since   0.1.6
 */
class Phlickr_Cache {
    /**
     * By default a cache entry is valid for 1 hour or 3600 seconds.
     *
     * @var integer
     * @see __construct(), getShelfLife()
    */
    const DEFAULT_SHELF_LIFE = 3600;

    /**
     * Array of cached values. The key is the MD5 hash of the URL and the value
     * is a string with the XML result.
     *
     * @var array
     */
    private $_values;
    /**
     * Array of expiration timestamps. The key is the MD5 hash of the URL and
     * the value is an integer timestamp when the entry expires.
     *
     * @var array
     */
    private $_expires;

    /**
     * Default shelf life in seconds.
     *
     * @var integer
     * @see getShelfLife(), setShelfLife(), DEFAULT_SHELF_LIFE
     */
    private $_shelfLife;

    /**
     * Constructor.
     *
     * @param   integer $shelfLife The default number of seconds to store an
     *          entry.
     * @see     getShelfLife(), DEFAULT_SHELF_LIFE
     */
    public function __construct($shelfLife = self::DEFAULT_SHELF_LIFE) {
        $this->_values = array();
        $this->_expires = array();
        $this->_shelfLife = (integer) $shelfLife;
    }

    /**
     * Load a serialized cache object from a file.
     *
     * If the file does not exist, is invalid, or cannot be loaded, then a new,
     * empty, cache object will be created and returned.
     *
     * @param   string $fileName Name of the file containing the serialized
     *          cache object.
     * @param   integer $defaultShelfLife The default number of seconds to
     *          store an entry.
     * @return  object Phlickr_Cache
     * @since   0.2.1
     * @see     saveAs()
     */
    static public function createFrom($fileName, $shelfLife = self::DEFAULT_SHELF_LIFE) {
        if (file_exists($fileName)) {
            $cache = unserialize(file_get_contents($fileName));
            if (!is_null($cache) && ($cache instanceof Phlickr_Cache)) {
                $cache->setShelfLife($shelfLife);
                return $cache;
            }
        }
        return new Phlickr_Cache($shelfLife);
    }

    /**
     * Attempt to retrieve a response from the cache.
     *
     * If there's a cached response, an XML string that is ready for use with
     * a Phlickr_Response will be returned. if . If not, return null.
     *
     * @param   string $url The Phlickr_Request's complete URL.
     * @return  string If there's a cached response, an XML string ready for
     *          use with a Phlickr_Response. If not, return null.
     * @since   0.1.6
     * @see     has(), set()
    */
    public function get($url) {
        if ($this->has($url)) {
            // use MD5 to obscure passwords in the urls
            return $this->_values[md5($url)];
        }
        return null;
    }

    /**
     * Get the default number of seconds a cached entry is considered valid.
     *
     * This value is used if none is specified when adding an entry with set().
     * A negative return value indicates that entries will not expire. A return
     * value of 0 indicates that no entries will be cached.
     *
     * @return  integer
     * @since   0.2.3
     * @see     __construct(), setShelfLife(), DEFAULT_SHELF_LIFE
     */
    public function getShelfLife() {
        return $this->_shelfLife;
    }

    /**
     * Cache a URL/response pair.
     *
     * Setting the $shelfLife parameter to 0 is equivalent to removing an entry
     * from the cache.
     *
     * @param   string $url The Phlickr_Request's complete URL.
     * @param   mixed $response Typically this is a XML string that will be
     *          passed to a Phlickr_Response. Other datatypes are allowed, and
     *          preserved so that applications can use this object for caching.
     * @param   integer $shelfLife The number of seconds this response is
     *          considered valid. A value of 0 indicates it should not be
     *          cached, while a negative number indicates it should not expire.
     *          If this parameter is omitted, the value returned by
     *          getShelfLife() will be used in its place.
     * @return  void
     * @since   0.1.6
     * @see     get(), has(), getShelfLife()
    */
    public function set($url, $response, $shelfLife = null) {
        // use MD5 to obscure passwords in the urls
        $md5 = md5($url);

        $shelfLife = (is_int($shelfLife)) ? $shelfLife : $this->getShelfLife();
        if ($shelfLife == 0) {
            // remove any existing values
            unset($this->_values[$md5]);
            unset($this->_expires[$md5]);
        } else {
            // if the shelf life is positive add it to the current time.
            if ($shelfLife > 0) {
                $this->_expires[$md5] = time() + $shelfLife;
            }
            $this->_values[$md5] = $response;
        }
    }

    /**
     * Set the default number of seconds a cached entry is considered valid.
     *
     * This value is used if none is specified when adding an entry with set().
     * A negative return value indicates that entries will not expire. A return
     * value of 0 indicates that no entries will be cached.
     *
     * @param   integer $shelfLife Number of seconds
     * @return  void
     * @since   0.2.3
     * @see     __construct(), getShelfLife(), DEFAULT_SHELF_LIFE
     */
    public function setShelfLife($shelfLife) {
        $this->_shelfLife = (integer) $shelfLife;
    }

    /**
     * Return a boolean value indicating if the cache has a valid reponse for
     * a given URL.
     *
     * @param   string $url The URL.
     * @return  boolean
     * @since   0.1.6
     * @see     get(), set()
     */
    public function has($url) {
        $md5 = md5($url);

        // check if there is an expiration time, and if it has passed
        if (isset($this->_expires[$md5]) && $this->_expires[$md5] < time()) {
            unset($this->_values[$md5]);
            unset($this->_expires[$md5]);
            return false;
        } else {
            return isset($this->_values[$md5]);
        }
    }

    /**
     * Serialize this object and save it to a file.
     *
     * @param   string $fileName The filename where the cache will be saved.
     * @return  void
     * @since   0.2.1
     * @see     createFrom()
     * @todo    Add some code to remove expired entries before saving.
     */
    public function saveAs($fileName) {
        file_put_contents($fileName, serialize($this));
    }
}
