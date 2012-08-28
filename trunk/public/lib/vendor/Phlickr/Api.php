<?php
/**
 * @version $Id: Api.php 523 2006-08-28 18:30:20Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Include Phlickr_Cache, a core class.
 */
require_once 'Phlickr/Cache.php';
/**
 * Include the Phlickr exceptions, core classes.
 */
require_once 'Phlickr/Exception.php';
/**
 * Include Phlickr_Request, a core class.
 */
require_once 'Phlickr/Request.php';
/**
 * Include Phlickr_CResponse, a core class.
 */
require_once 'Phlickr/Response.php';

/**
 * Phlickr_Api acts as a connection to the Flickr API and provides several
 * shortcut methods for interacting with it.
 *
 * Sample usage:
 * <code>
 * <?php
 * include_once 'Phlickr/Api.php';
 * $api = new Phlickr_Api(FLICKR_API_KEY, FLICRK_API_SECRET);
 *
 * // Authentication is no longer done with an email/password. the first step
 * // is requesting a frob and then building a Flickr URL to log into.
 * $frob = $api->requestFrob();
 * // request read and write permissions (write implies read)
 * $url = $api->buildAuthUrl('write', $frob);
 * print "frob: $frob\n";
 * // you'll need open the link and log into you Flickr account to grant
 * // permissions to this API key.
 * print "url: $url\n";
 *
 * // once you've logged in and validated the fob you can turn it into an
 * // auth token that's good for a while.
 * print "token: " . $api->getTokenFromFrob($frob) . "\n";
 *
 * // check if the authentication token is valid.
 * print $api->isAuthValid();
 *
 * // execute a flickr echo test method.
 * $response = $api->executeMethod('flickr.test.echo', array('foo'=>'bar'));
 * print $response;
 * 
 * </code>
 *
 * This class is responsible for:
 * - Storing an API key.
 * - Storing user authentication information and verifying it can be used to
 *   login to the service.
 * - Storing a cache of results to previous API calls. This cache can be
 *   serialized and restored across sessions to allow offline, use.
 * - Acting as a facade to provide clients with "one line" access to API
 *   methods.
 *
 * Calls to the Flickr API have several steps. First, a Phlickr_Request is
 * constructed with a reference to a Phlickr_Api. When the Phlickr_Request
 * executes it returns a Phlickr_Response. All other classes just wrap
 * Phlickr_Response's output in some way, to make it easier to use.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @since   0.1.0
 */
class Phlickr_Api {
    /**
     * The default Flickr API endpoint URL for REST requests.
     *
     * @var string
     * @see setEndpointUrl()
     */
    const REST_ENDPOINT_URL = 'http://flickr.com/services/rest/';
    /**
     * The name of the API key label in the settings files created by saveAs()
     * and read by createFrom().
     *
     * @var string  This should be all lowercase.
     */
    const SETTING_API_KEY = 'api_key';
    /**
     * The name of the API secret label in the settings files created by saveAs()
     * and read by createFrom().
     *
     * @var string  This should be all lowercase.
     */
    const SETTING_API_SECRET = 'api_secret';
    /**
     * The name of the API authorization token label in the settings files
     * created by saveAs() and read by createFrom().
     *
     * @var string  This should be all lowercase.
     */
    const SETTING_API_TOKEN = 'api_token';
    /**
     * The name of the cache filename label in the settings files created by
     * saveAs() and read by createFrom().
     *
     * @var string  This should be all lowercase.
     */
    const SETTING_API_CACHE = 'cache_file';

    /**
     * A Flickr API key.
     *
     * To obtain one see http://flickr.com/services/api/misc.api_keys.html .
     *
     * @var string
     * @see getKey()
     */
    private $_key = null;
    /**
     * The shared secret that goes with the API key.
     *
     * @var     string
     * @since   0.2.3
     * @see     getSecret()
     */
    private $_secret = null;
    /**
     * The token associated with a Flickr account.
     *
     * @var     string
     * @see     getAuthToken()
     * @since   0.2.3
     */
    private $_token = null;
    /**
     * The Request Cache.
     *
     * @var object Phlickr_Cache
     * @see getCache(), setCache()
     */
    private $_cache = null;
    /**
     * The cache filename.
     *
     * @var string Full path of the cache file
     * @see getCacheFilename(), setCacheFilename()
     */
    private $_cacheFilename = '';
    /**
     * The Flickr REST endpoint URL.
     *
     * @var     string
     * @see     getEndpointUrl(), setEndpointUrl()
     * @uses    REST_ENDPOINT_URL Used as a default.
     */
    private $_endpointUrl = Phlickr_Api::REST_ENDPOINT_URL;
    /**
     * The cached Flickr user id. This will be null until isValidAuth() or
     * getUserId() is called.
     *
     * @var string
     * @see isValidAuth(), getUserId()
     */
    private $_userId = null;

    /**
     * Constructor.
     *
     * @param   string $key Flickr API key.
     * @param   string $secret Flickr API shared secret.
     * @param   string $token
     * @see     getKey(), getSecret(), getAuthToken(), setAuthToken()
     */
    public function __construct($key, $secret, $token = null) {
        // key (required)
        if (isset($key)) {
            $this->_key = (string) $key;
        } else {
            throw new Phlickr_Exception('Must provide a Flickr API key.');
        }
        // secret (required)
        if (isset($secret)) {
            $this->_secret = (string) $secret;
        } else {
            throw new Phlickr_Exception('Must provide a Flickr API secret.');
        }
        // token
        if (isset($token)) {
            $this->_token = (string) $token;
        }
        $this->_cache = new Phlickr_Cache();
    }
    /**
     * Destructor. If a cache filename is set, save the contents of the cache
     * to the file using Phlickr_Cache::saveAs().
     *
     * @since   0.2.4
     * @see     setCacheFilename()
     * @uses    Phlickr_Api::getCacheFilename() to determine if and where the
     *          destructor should save the cached data.
     * @uses    Phlickr_Cache::saveAs() to save the cache.
     */
    public function __destruct() {
        if ($this->_cacheFilename != '') {
            $this->_cache->saveAs($this->_cacheFilename);
        }
    }


    /**
     * Create an Api from settings saved into a file by saveAs().
     *
     * If the file does not exist, is invalid, or cannot be loaded, then you're
     * going to get an exception.
     *
     * The format of the file is:
     * <code>
     * api_key=0123456789abcdef0123456789abcedf
     * api_secret=abcedf0123456789
     * api_token=123-abcdef0123456789
     * cache_file=c:\temp\flickr.tmp
     * </code>
     * The token and cache filename settings are optional. The label of each
     * setting is defined by a constant in this class.
     *
     * @param   string $fileName Name of the file containing the saved Api
     *          settings.
     * @return  object Phlickr_Api
     * @since   0.2.4
     * @see     saveAs()
     * @uses    SETTING_API_KEY Label of the API key setting.
     * @uses    SETTING_API_SECRET Label of the API secret setting.
     * @uses    SETTING_API_TOKEN Label of the API token setting.
     * @uses    SETTING_API_CACHE Label of the cache filename setting.
     * @uses    setCacheFilename() to assign the cache filename if one is
     *          present in the settings file.
     */
    static public function createFrom($filename) {
        // create an array for the settings
        $config = array();
        // load the file
        $contents = file_get_contents($filename);
        // parse the key=value pairs into an associative array.
        preg_match_all('/([-_a-zA-Z]+)=(.+)/', $contents, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $config[strtolower($match[1])] = $match[2];
        }

        // build an api object from the settings
        $api = new Phlickr_Api(
            $config[self::SETTING_API_KEY],
            $config[self::SETTING_API_SECRET],
            $config[self::SETTING_API_TOKEN]
        );

        // set the cache filename
        if (isset($config[self::SETTING_API_CACHE])) {
            $api->setCacheFilename($config[self::SETTING_API_CACHE]);
        }

        return $api;
    }
    /**
     * Save the settings from an Api object so that it can be recreated later
     * by createFrom().
     *
     * @param   string $fileName Name of the file to save the Api settings.
     * @return  void
     * @since   0.2.4
     * @see     createFrom()
     * @uses    SETTING_API_KEY Label of the API key setting.
     * @uses    SETTING_API_SECRET Label of the API secret setting.
     * @uses    SETTING_API_TOKEN Label of the API token setting.
     * @uses    SETTING_API_CACHE Label of the cache filename setting.
     */
    public function saveAs($filename) {
        $settings = self::SETTING_API_KEY . "={$this->getKey()}\n";
        $settings .= self::SETTING_API_SECRET . "={$this->getSecret()}\n";
        $settings .= self::SETTING_API_TOKEN . "={$this->getAuthToken()}\n";
        $settings .= self::SETTING_API_CACHE . "={$this->getCacheFilename()}\n";

        file_put_contents($filename, $settings);
    }


    /**
     * Returns the Phlickr_Cache associated with this connection.
     *
     * @return  object Phlickr_Cache
     * @see     setCache()
     */
    public function getCache() {
        return $this->_cache;
    }
    /**
     * Assign a cache to this API.
     *
     * Be aware that if the new cache has data it will be used.
     *
     * @param   object Phlickr_Cache $cache The cache to use
     * @return  void
     * @see     getCache()
     */
    public function setCache(Phlickr_Cache $cache) {
        return $this->_cache = $cache;
    }
    /**
     * Get the file name where the cache is saved when the Api object is
     * destroyed.
     *
     * @return  string  The full path of the file.
     * @since   0.2.4
     * @see     __destruct(), setCacheFilename()
     */
    public function getCacheFilename() {
        return $this->_cacheFilename;
    }
    /**
     * Set the name of the file used to save the cache when the object is
     * destroyed.
     *
     * If the file exists and is readable, an attempt will be made to load it
     * as a cache object using Phlickr_Cache::createFrom(). If the file does
     * not contain a valid cache object then a new, empty, cache object will
     * used and any previous cached information will be discarded.
     *
     * @param   string  The full path of the file.
     * @return  void
     * @since   0.2.4
     * @see     __destruct(), getCacheFilename(), Phlickr_Cache::createFrom()
     */
    public function setCacheFilename($filename) {
        $this->_cacheFilename = (string) $filename;
        if (file_exists($this->_cacheFilename)) {
            $this->_cache = Phlickr_Cache::createFrom($this->_cacheFilename);
        }
    }
    /**
     * Add a response to the cache.
     *
     * Use this function to seed the cache with a response. It's helpful for
     * writing unit tests or offline use.
     *
     * @param   string $method Name of Flickr API method.
     * @param   array $params Array of parameters. The ordering isn't important,
     *          they'll be sorted when building the request.
     * @param   string $xml The XML return. This should be the payload, i.e.
     *          everything in the <resp></resp> element.
     * @return  void
     * @uses    Phlickr_Request::buildUrl() to construct the URL that the cache
     *          uses as a key.
     * @uses    Phlickr_Cache::set() to store the results in the cache.
    */
    public function addResponseToCache($method, $params, $xml) {
        $url = $this->createRequest($method, $params)->buildUrl();
//print "\nCACHED : $url\n";
        $this->_cache->set($url, $xml);
    }

    /**
     * Get the API key.
     *
     * @return  string
     * @see     __construct()
     * @uses    Phlickr_Api::$_key Value is loaded from this variable.
     */
    public function getKey() {
        return $this->_key;
    }
    /**
     * Get the API secret that corresponds to key.
     *
     * @return  string
     * @see     __construct()
     * @link    http://flickr.com/services/api/auth.spec.html
     */
    public function getSecret() {
        return $this->_secret;
    }
    /**
     * Return the token being used for authentication.
     *
     * @return  string
     * @see     __construct(), setAuthToken(), setAuthTokenFromFrob()
     * @todo    Deprecate and then rename this to getToken()
     */
    public function getAuthToken() {
        return $this->_token;
    }
    /**
     * Set the Flickr authentication token.
     *
     * @param   string $token
     * @return  void
     * @see     __construct(), getAuthToken(), setAuthTokenFromFrob()
     * @todo    Deprecate and then rename this to setToken()
     */
    public function setAuthToken($token) {
        $this->_token = (string) $token;
        // if the token changes the user has likely changed
        //$this->_userId = null;
    }
    /**
     * Set the auth token from a frob.
     *
     * The user needed to authenticate the frob.
     *
     * @param   string $frob
     * @return  string The new token
     * @see     __construct(), requestFrob(), getToken()
     * @since   0.2.3
     * @uses    executeMethod() to call flickr.auth.getToken
     * @todo    Deprecate and then rename this to setTokenFromFrob()
     */
    public function setAuthTokenFromFrob($frob) {
        $resp = $this->executeMethod('flickr.auth.getToken',
            array('frob' => (string) $frob));
        $xml = $resp->getXml()->auth;

        // assign the usefull stuff
        $this->_token = (string) $xml->token;
        $this->_userId = (string) $xml->user['nsid'];

        // return the token incase they're interested
        return $this->_token;
    }
    /**
     * Check if current authentication info is valid.
     *
     * @return  boolean
     * @see     setAuth()
     * @since   0.2.3
     * @uses    getUserId() To determine if the authentication is valid.
     */
    public function isAuthValid() {
        if (is_null($this->getUserId())) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return the Flickr user id of the current authenticated user.
     *
     * If the authentication info is incorrect, null will be returned.
     *
     * @return  string
     * @see     setAuth(), getAuthToken(), Phlickr_User,
     *          Phlickr_AuthedUser
     * @uses    executeMethod() to call flickr.auth.checkToken
     */
    public function getUserId() {
        if (is_null($this->_userId)) {
            try {
                $response = $this->executeMethod('flickr.auth.checkToken');
                $this->_userId = (string) $response->xml->auth->user['nsid'];
            } catch (Phlickr_Exception $ex) {
                // invalid login (or connection problem)
                $this->_userId = null;
            }
        }
        return $this->_userId;
    }

    /**
     * Return an array of the API's parameters for use with a Phlickr_Request.
     *
     * @return  array
     * @see     Phlickr_Request::buildUrl()
     */
    public function getParamsForRequest() {
        $params['api_key'] = $this->_key;
        if (isset($this->_token)) {
            $params['auth_token'] = $this->_token;
        }
        return $params;
    }

    /**
     * Return the URL of the Flickr endpoint.
     *
     * @return  string
     * @see     setEndpointUrl()
     */
    public function getEndpointUrl() {
        return $this->_endpointUrl;
    }
    /**
     * Set the URL of the Flickr endpoint.
     *
     * @return  string
     * @see     getEndpointUrl(), REST_ENDPOINT_URL
     */
    public function setEndpointUrl($endpointUrl) {
        $this->_endpointUrl = (string) $endpointUrl;
    }

    /**
     * Request a frob used to get a token.
     *
     * Hand this to buildAuthUrl() so the user can authenticate and grant
     * permissions to the application.
     *
     * I have no idea where the title frob came from.
     *
     * @return  string
     * @link    http://flickr.com/services/api/flickr.auth.getFrob.html
     * @see     buildAuthUrl()
     * @since   0.2.3
     * @uses    executeMethod() to call flickr.auth.getFrob
     */
    function requestFrob() {
        $resp = $this->executeMethod('flickr.auth.getFrob');
        return (string) $resp->getXml()->frob;
    }

    /**
     * Build a URL to request a token.
     *
     * If a frob is omitted it is assumed that you've registered a callback URL
     * as per the Flickr documentation.
     *
     * @param   string $perms The desired permissions 'read', 'write', or
     *          'delete'.
     * @param   string $frob optional Frob
     * @return  void
     * @see     requestFrob()
     * @since   0.2.3
     * @uses    Phlickr_Request::signParams() to create a signed URL.
     */
    function buildAuthUrl($perms, $frob ='') {
        $params = array('api_key' => $this->getKey(), 'perms' => $perms);
        if ($frob != '') {
            $params['frob'] = (string) $frob;
        }
        return 'http://flickr.com/services/auth/?'.
            Phlickr_Request::signParams($this->getSecret(), $params);
    }

    /**
     * Create a Phlickr_Request associated with this API object.
     *
     * See the {@link http://flickr.com/services/api/ Flickr API} for a complete
     * list of methods and parameters.
     *
     * @param   string $method Name of the Flickr API method
     * @param   array $params Associative array of parameter name/value pairs
     * @return  object Phlickr_Request
     * @uses    Phlickr_Request
     */
    public function createRequest($method, $params = array()) {
        return new Phlickr_Request($this, $method, $params);
    }

    /**
     * Execute a method with the given parameters.
     *
     * See the {@link http://flickr.com/services/api/ Flickr API} for a complete
     * list of methods and parameters.
     *
     * @param   string $method Name of the Flickr API method
     * @param   array $params Associative array of parameter name/value pairs
     * @param   boolean $allowCached If a cached response exists should it be
     *          returned?
     * @return  object Phlickr_Response
     * @throws  Phlickr_Exception, Phlickr_XmlParseException,
     *          Phlickr_ConnectionException
     * @uses    createRequest() to build the Phlickr_Request object.
     * @uses    Phlickr_Request::execute() to execute the method.
     */
    public function executeMethod($method, $params = array(), $allowCached = true) {
        return $this->createRequest($method, $params)->execute($allowCached);
    }
}
