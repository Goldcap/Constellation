<?php

/**
 * @version $Id: Request.php 528 2006-10-30 21:48:41Z drewish $
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
 * The Phlickr_Request executes a Flickr API method and returns a
 * Phlickr_Response object with the results.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET, FLICKR_TOKEN);
 *
 * // create a request to search for photos tagged with person and happy
 * // from all users.
 * $request = $api->createRequest(
 *     'flickr.photos.search',
 *     array(
 *         'tags' => 'person,happy',
 *         'tag_mode' => 'all',
 *         'user_id' => ''
 *     )
 * );
 *
 * // use the photo list and photo list iterator to display the titles and urls
 * // of each of the photos.
 * $photolist = new Phlickr_PhotoList($request);
 * $iterator = new Phlickr_PhotoListIterator($photolist);
 * foreach ($iterator as $photos) {
 *     foreach ($photos as $photo) {
 *         print "Photo: {$photo->getTitle()}\n";
 *         print "\t{$photo->buildImgUrl()}\n";
 *     }
 * }
 * //?//>
 * </code>
 *
 * This class is responsible for:
 * - Assembling the information needed to build a REST URL.
 * - Submitting an HTTP POST request.
 * - Creating a Phlickr_Response from the results of an HTTP request.
 * - Providing callers with the details of the HTTP request for debugging
 *   purposes.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @since   0.1.0
 */
class Phlickr_Request {
    /**
     * Number of seconds to wait while connecting to the server.
     */
    const TIMEOUT_CONNECTION = 10;
    /**
     * Total number of seconds to wait for a request.
     */
    const TIMEOUT_TOTAL = 20;

    /**
     * Phlickr_API object
     *
     * @var object
     */
    private $_api = null;
    /**
     * Name of the method.
     *
     * @var string
     */
    private $_method = null;
    /**
     * Parameters used in the last request.
     *
     * @var array
     */
    private $_params = array();
    /**
     * Should an exception be thrown when an API call fails?
     *
     * @var boolean
     */
    private $_throwOnFail = false;
    
    /**
     * Constructor.
     *
     * See the {@link http://flickr.com/services/api/ Flickr API} for a complete
     * list of methods and parameters.
     *
     * @param  object Phlickr_API $api
     * @param  string $method The name of the method.
     * @param  array $params Associative array of parameter name/value pairs.
     */
    public function __construct(Phlickr_Api $api, $method, $params = array())
    {
        $this->_api = $api;
        $this->_method = (string) $method;
        if (!is_null($params)) {
            $this->_params = $params;
        }
    }

    public function __toString()
    {
        return $this->buildUrl();
    }

    /**
     * Submit a POST request with to the specified URL with given parameters.
     *
     * @param   string $url
     * @param   array $params An optional array of parameter name/value
     *          pairs to include in the POST.
     * @param   integer $timeout The total number of seconds, including the
     *          wait for the initial connection, wait for a request to complete.
     * @return  string
     * @throws  Phlickr_ConnectionException
     * @uses    TIMEOUT_CONNECTION to determine how long to wait for a
     *          for a connection.
     * @uses    TIMEOUT_TOTAL to determine how long to wait for a request
     *          to complete.
     * @uses    set_time_limit() to ensure that PHP's script timer is five
     *          seconds longer than the sum of $timeout and TIMEOUT_CONNECTION.
     */
    static function submitHttpPost($url, $postParams = null, $timeout = self::TIMEOUT_TOTAL)
    {
        $ch = curl_init();

        // set up the request
        curl_setopt($ch, CURLOPT_URL, $url);
        // make sure we submit this as a post
        curl_setopt($ch, CURLOPT_POST, true);
        if (isset($postParams)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        }
        // make sure problems are caught
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        // return the output
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // set the timeouts
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT_CONNECTION);
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
        // set the PHP script's timeout to be greater than CURL's
        set_time_limit(self::TIMEOUT_CONNECTION + $timeout + 5);

        $result = curl_exec($ch);
        // check for errors
        if (0 == curl_errno($ch)) {
            curl_close($ch);
            return $result;
        } elseif ($_throwOnFail) {
            $ex = new Phlickr_ConnectionException(
                'Request failed. ' . curl_error($ch), curl_errno($ch), $url);
            curl_close($ch);
            throw $ex;
        }
    }

    /**
     * Create a signed signature of the parameters.
     *
     * Return a parameter string that can be tacked onto the end of a URL.
     * Items will be sorted and an api_sig element will be on the end.
     *
     * @param   string  $secret
     * @param   array   $params
     * @return  string
     * @since   0.2.3
     * @link    http://flickr.com/services/api/auth.spec.html
     */
    static function signParams($secret, $params)
    {
        $signing = '';
        $values = array();
        ksort($params);

        foreach($params as $key => $value) {
            $signing .= $key . $value;
            $values[] = $key . '=' . urlencode($value);
        }
        $values[] = 'api_sig=' . md5($secret . $signing);

        return implode('&', $values);
    }

    /**
     * Return a reference to this Request's Phlickr_Api.
     *
     * @return  object Phlickr_Api
     * @see     __construct()
     */
    public function getApi()
    {
        return $this->_api;
    }

    /**
     * Return the name of the method
     *
     * @return  string
     * @see     __construct()
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Return the array of parameters.
     *
     * @return  array
     * @see     setParams()
     */
    public function &getParams()
    {
        return $this->_params;
    }

    /**
     * Assign parameters to the request.
     *
     * @param   array $params Associative array of parameter name/value pairs
     * @return  void
     * @see     __construct, getParams()
     */
    public function setParams($params)
    {
        if (is_null($params)) {
            $this->_params = array();
        } else {
            $this->_params = $params;
        }
    }

    /**
     * Return true if an exception will be thrown if the API returns a fail
     * for the request.
     *
     * @return  boolean
     * @see     setExceptionThrownOnFailure()
     */
    public function isExceptionThrownOnFailure()
    {
        return $this->_throwOnFail;
    }
    /**
     * Set an exception will be thrown if the API returns a fail for the
     * request.
     *
     * @param   boolean $throwOnFail
     * @return  void
     * @see     isExceptionThrownOnFailure()
     */
    public function setExceptionThrownOnFailure($throwOnFail)
    {
        $this->_throwOnFail = (boolean) $throwOnFail;
    }

    /**
     * Build a signed URL for this Request.
     *
     * The Api will provide the key and secret and token values.
     *
     * @return  string
     * @link    http://flickr.com/services/api/auth.spec.html
     * @see     buildUrl, Phlickr_Api::getKey(), Phlickr_Api::getSecret()
     * @uses    signParams() to create a signed URL.
     */
    public function buildUrl()
    {
        $api = $this->getApi();

        // merge the api's parameters with the user's. the order of array_merge
        // parameters is designed so that user values will overwrite api values
        // if there are duplicates.
        $params = array_merge(
            $api->getParamsForRequest(),
            $this->getParams()
        );
        $params['method'] = $this->getMethod();

        return $api->getEndpointUrl() . '?'
            . self::signParams($api->getSecret(), $params);
    }

    /**
     * Execute a Flickr API method.
     *
     * All requests are cached but cached data is only used when the caller
     * specifically allows it. This allows the unittests to load a cache full
     * of expected responses and avoid a network connection.
     *
     * @param   boolean $allowCached If a cached result exists, should it be returned?
     * @return  object Flicrk_Response
     * @throws  Phlickr_XmlParseException, Phlickr_ConnectionException
     * @uses    submitHttpPost() to submit the request.
     * @uses    Phlickr_Cache to load and cached requests.
     * @uses    Phlickr_Response to return results.
     */
    public function execute($allowCached = false)
    {
        $url = $this->buildUrl();
        $cache =& $this->getApi()->getCache();
//print "\nREQUEST: $url\n";
        if ($allowCached && $cache->has($url)) {
            $result = $cache->get($url);
        } else {
            $result = self::submitHttpPost($url);
            $cache->set($url, $result);
        }
//print "RESULT: $result\n";
        return new Phlickr_Response($result, $this->_throwOnFail);
    }
}
