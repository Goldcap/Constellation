<?php
/* phpYoutube Class 1.1 based on phpFlickr Class 1.6.1
 * Modified by Erik de Vries (info@erikdevries.nl)
 * phpFlickr written by Dan Coulter (dan@dancoulter.com)
 * Released under GNU Lesser General Public License (http://www.gnu.org/copyleft/lgpl.html)
 */
if (session_id() == "") {
    session_start();
}
require_once('xml.php');

// Decides which include path delimiter to use.  Windows should be using a semi-colon
// and everything else should be using a colon.  If this isn't working on your system,
// comment out this if statement and manually set the correct value into $path_delimiter.
if (strpos(__FILE__, ':') !== false) {
    $path_delimiter = ';';
} else {
    $path_delimiter = ':';
}

// This will add the packaged PEAR files into the include path for PHP, allowing you
// to use them transparently.  This will prefer officially installed PEAR files if you
// have them.  If you want to prefer the packaged files (there shouldn't be any reason
// to), swap the two elements around the $path_delimiter variable.  If you don't have
// the PEAR packages installed, you can leave this like it is and move on.

ini_set('include_path', ini_get('include_path') . $path_delimiter . dirname(__FILE__) . '/PEAR');

// If you have problems including the default PEAR install (like if your open_basedir
// setting doesn't allow you to include files outside of your web root), comment out
// the line above and uncomment the next line:

// ini_set('include_path', dirname(__FILE__) . '/PEAR' . $path_delimiter . ini_get('include_path'));

class phpYoutube {
    var $dev_id;
    var $REST = 'http://www.youtube.com/api2_rest';
    var $xml_parser;
    var $req;
    var $response;
    var $parsed_response;
    var $cache = false;
    var $cache_db = null;
    var $cache_table = null;
    var $cache_dir = null;
    var $cache_expire = null;
    var $die_on_error;
    var $error_code;
    var $error_msg;
    var $php_version;
    var $service;
    
    function phpYoutube ($dev_id, $die_on_error = true) 
    {
        //The DEV Id must be set before any calls can be made.  You can 
        //get your own at http://www.youtube.com/signup?next=my_profile_dev
        $this->dev_id = $dev_id;
        $this->die_on_error = $die_on_error;
        $this->service = "youtube";
        
        //Find the PHP version and store it for future reference
        $this->php_version = explode("-", phpversion());
        $this->php_version = explode(".", $this->php_version[0]);
        
        //All calls to the API are done via the POST method using the PEAR::HTTP_Request package.
        require_once 'HTTP/Request.php';
        $this->req =& new HTTP_Request();
        $this->req->setMethod(HTTP_REQUEST_METHOD_POST);
        
        //setup XML parser using Aaron Colflesh's XML class.
        $this->xml_parser = new phpyoutube_xml(false, true, true);
    }
    
    function enableCache($type, $connection, $cache_expire = 600, $table = 'youtube_cache') 
    {
        // Turns on caching.  $type must be either "db" (for database caching) or "fs" (for filesystem).
        // When using db, $connection must be a PEAR::DB connection string. Example:
        //      "mysql://user:password@server/database"
        // If the $table, doesn't exist, it will attempt to create it.
        // When using file system, caching, the $connection is the folder that the web server has write
        // access to. Use absolute paths for best results.  Relative paths may have unexpected behavior 
        // when you include this.  They'll usually work, you'll just want to test them.
        if ($type == 'db') {
            require_once 'DB.php';
            $db =& DB::connect($connection);
            if (PEAR::isError($db)) {
                die($db->getMessage());
            }
            
            $db->query("
                CREATE TABLE IF NOT EXISTS `$table` (
                    `request` CHAR( 35 ) NOT NULL ,
                    `response` MEDIUMTEXT NOT NULL ,
                    `expiration` DATETIME NOT NULL ,
                    INDEX ( `request` )
                ) TYPE = MYISAM");
            $db->query("DELETE FROM $table WHERE expiration < DATE_SUB(NOW(), INTERVAL $cache_expire second)");
            if (strpos($connection, 'mysql') !== false) {
                $db->query('OPTIMIZE TABLE ' . $table);
            }
            $this->cache = 'db';
            $this->cache_db = $db;
            $this->cache_table = $table;
        } elseif ($type = 'fs') {
            $this->cache = 'fs';
            $connection = realpath($connection);
            $this->cache_dir = $connection;
            if ($dir = opendir($this->cache_dir)) {
                while ($file = readdir($dir)) {
                    if (substr($file, -6) == '.cache' && ((filemtime($this->cache_dir . '/' . $file) + $cache_expire) < time()) ) {
                        unlink($this->cache_dir . '/' . $file);
                    }
                }
            }
        }
        $this->cache_expire = $cache_expire;
    }
    
    function getCached ($request) 
    {
        //Checks the database or filesystem for a cached result to the request.
        //If there is no cache result, it returns a value of false. If it finds one,
        //it returns the unparsed XML.
        $reqhash = md5(serialize($request));
        if ($this->cache == 'db') {
            $result = $this->cache_db->getOne("SELECT response FROM " . $this->cache_table . " WHERE request = '" . $reqhash . "'");
            if (!empty($result)) {
                return $result;
            }
        } elseif ($this->cache == 'fs') {
            $file = $this->cache_dir . '/' . $reqhash . '.cache';
            if (file_exists($file)) {
				if ($this->php_version[0] > 4 || ($this->php_version[0] == 4 && $this->php_version[1] >= 3)) {
					return file_get_contents($file);
				} else {
					return implode('', file($file));
				}
            }
        }
        return false;
    }
    
    function cache ($request, $response) 
    {
        //Caches the unparsed XML of a request.
        $reqhash = md5(serialize($request));
        if ($this->cache == 'db') {
            $this->cache_db->query("DELETE FROM $this->cache_table WHERE request = '$reqhash'");
            $sql = "INSERT INTO " . $this->cache_table . " (request, response, expiration) VALUES ('$reqhash', '" . str_replace("'", "''", $response) . "', '" . strftime("%Y-%m-%d %H:%M:%S") . "')";
            $this->cache_db->query($sql);
        } elseif ($this->cache == "fs") {
            $file = $this->cache_dir . "/" . $reqhash . ".cache";
            $fstream = fopen($file, "w");
            $result = fwrite($fstream,$response);
            fclose($fstream);
            return $result;
        }
        return false;
    }
    
    function request ($command, $args = array(), $nocache = false) 
    {
        //Sends a request to Youtube's REST endpoint via POST.
        $this->req->setURL($this->REST);
        $this->req->clearPostData();
        if (substr($command,0,8) != "youtube.") {
            $command = "youtube." . $command;
        }

        //Process arguments, including method and login data.
        $args = array_merge(array("method" => $command, "dev_id" => $this->dev_id), $args);
        ksort($args);
        $auth_sig = "";
        if (!($this->response = $this->getCached($args)) || $nocache) {
            foreach ($args as $key => $data) {
                $auth_sig .= $key . $data;
                $this->req->addPostData($key, $data);
            }
            //Send Requests
            if ($this->req->sendRequest()) {
                $this->response = $this->req->getResponseBody();
                $this->cache($args, $this->response);
            } else {
                die("There has been a problem sending your command to the server.");
            }
        }
        return $this->response;
    }
    
    function parse_response ($xml = NULL) 
    {
        //Sends response data through XML parser and returns an associative array.
        if ($xml === NULL) {
            $xml = $this->response;
        }
        $this->parsed_response = $this->xml_parser->parse($xml);

        //Check for an error and die if it finds one.
        if (!empty($this->parsed_response['ut_response']['error']) && $this->die_on_error) {
            die("The Youtube API returned error code #" . $this->parsed_response['ut_response']['error']['code'] . ": " . $this->parsed_response['ut_response']['error']['description']);
        } elseif (!empty($this->parsed_response['ut_response']['error'])) {
			$this->error_code = $this->parsed_response['ut_response']['error']['code'];
			$this->error_msg = "The Youtube API returned error code #" . $this->parsed_response['ut_response']['error']['code'] . ": " . $this->parsed_response['ut_response']['error']['description'];
			return false;
        } else {
			$this->error_code = false;
			$this->error_msg = false;
        }
        
        return $this->parsed_response['ut_response'];
    }
    
    function setService($service)
    {
		// Sets which service to connect to.  Currently supported service is
		// "youtube"
		if (strtolower($service) == "youtube") {
			$this->service = "youtube";
			$this->REST = 'http://www.youtube.com/api2_rest';
		} else {
			die ("You have entered a service that does not exist or is not supported at this time.");
		}
    }
    
    function setProxy($server, $port) 
    {
        // Sets the proxy for all phpYoutube calls.
        $this->req->setProxy($server, $port);
    }
    
    function useSAXY($useIt = true) {
        $this->xml_parser->useSAXY($useIt);
    }
    
    function getErrorCode() 
    {
		// Returns the error code of the last call.  If the last call did not
		// return an error. This will return a false boolean.
		return $this->error_code;
    }
    
    function getErrorMsg() 
    {
		// Returns the error message of the last call.  If the last call did not
		// return an error. This will return a false boolean.
		return $this->error_msg;
    }
    
    /*******************************
    *******************************/
    
    function call($method, $arguments)
    {
        $this->request($method, $arguments);
        return $this->parse_response();
    }
    
    /* 
        These functions are the direct implementations of youtube calls.
        For method documentation, including arguments, visit the address
        included in a comment in the function. 
    */
     
    function users_getprofile($user = NULL) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.users.get_profile */
        $this->request("youtube.users.get_profile", array("user" => $user));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['user_profile'];
        return $result;
    }
    
    function users_listfavoritevideos($user = NULL) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.users.list_favorite_videos */
        $this->request("youtube.users.list_favorite_videos", array("user" => $user));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function users_listfriends($user_id = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.users.list_friends */
        $this->request("youtube.users.list_friends", array("user" => $user_id, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['friend_list'];
        return $result;
    }
    
    function videos_getdetails($video_id = NULL) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.get_details */
        $this->request("youtube.videos.get_details", array("video_id" => $video_id));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_details'];
        return $result;
    }
    
    function videos_listbytag($tag = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_by_tag */
        $this->request("youtube.videos.list_by_tag", array("tag" => $tag, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listbyuser($user = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_by_user */
        $this->request("youtube.videos.list_by_user", array("user" => $user, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listfeatured() 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_featured */
        $this->request("youtube.videos.list_featured", array());
        $this->parse_response();
		$result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listbyrelated($tag = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_by_related */
        $this->request("youtube.videos.list_by_related", array("tag" => $tag, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listbyplaylist($id = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_by_playlist */
        $this->request("youtube.videos.list_by_playlist", array("id" => $id, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listpopular($time_range = "all") 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_popular */
        $this->request("youtube.videos.list_popular", array("time_range" => $time_range));
        $this->parse_response();
		$result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listbycategory($category_id = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_by_category */
        $this->request("youtube.videos.list_by_category", array("category_id" => $category_id, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }
    
    function videos_listbycategoryandtag($category_id = NULL,$tag = NULL,$page = 1, $per_page = 20) 
    {
        /* http://www.youtube.com/dev_api_ref?m=youtube.videos.list_by_category_and_tag */
        $this->request("youtube.videos.list_by_category_and_tag", array("category_id" => $category_id, "tag" => $tag, "page" => $page, "per_page" => $per_page));
        $this->parse_response();
        $result = $this->parsed_response['ut_response']['video_list'];
        return $result;
    }    
}

?>
