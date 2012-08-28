<?php
//
// PHP5 Flickr_API 
// Should be mostly API compatible with Cal Henderson's PEAR::Flickr_API
// but uses Curl and SimpleXML
//
// Rasmus Lerdorf, August 2005
//

class Flickr_API {
    private $_cfg = array('api_key'         => '',
                          'api_secret'      => '',
                          'endpoint'        => 'http://www.flickr.com/services/rest/',
                          'auth_endpoint'   => 'http://www.flickr.com/services/auth/?',
                          'upload_endpoint' => 'http://www.flickr.com/services/upload/',
                          'conn_timeout'    => 20,
                          'io_timeout'      => 60 );

    function __construct($params = array()) {
        if(isset($params['token'])) $this->token = $params['token'];
        foreach($params as $k => $v) {
            $this->_cfg[$k] = $v;
        }
        if(!$this->_cfg['api_key'] || !$this->_cfg['api_secret']) {
            throw new Exception("You must supply an api_key and an api_secret");
        }
    }

    function callMethod($method, $params = array()) {
        $this->_err_code = 0;
        $this->_err_msg = '';

        $req = curl_init();

        if($method=='upload') {
            $photo = $params['photo'];
            unset($params['photo']);
        } else {
            $params['method'] = $method;
        }
        $params['api_key'] = $this->_cfg['api_key'];
        $params['api_sig'] = $this->signArgs($params);

        if($method=='upload') {
            $params['photo'] = '@'.$photo;
            curl_setopt($req, CURLOPT_URL, $this->_cfg['upload_endpoint']);
            curl_setopt($req, CURLOPT_TIMEOUT, 0);
#            curl_setopt($req, CURLOPT_INFILESIZE, filesize($photo));
        } else {
            curl_setopt($req, CURLOPT_URL, $this->_cfg['endpoint']);
            curl_setopt($req, CURLOPT_TIMEOUT, $this->_cfg['io_timeout']);
            curl_setopt($req, CURLOPT_POST, 1);    
        }

        // Sign and build request parameters
        curl_setopt($req, CURLOPT_POSTFIELDS, $params);
        curl_setopt($req, CURLOPT_CONNECTTIMEOUT, $this->_cfg['conn_timeout']);
        curl_setopt($req, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($req, CURLOPT_HEADER, 0);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        $this->_http_body = curl_exec($req);

        if (curl_errno($req)) {
            throw new Exception(curl_error($req));
        }

        curl_close($req);
        file_put_contents('/tmp/curl.log',print_r($params,true)."\n".$this->_http_body."\n\n",FILE_APPEND);

        $xml = simplexml_load_string($this->_http_body);
        $this->xml = $xml;

        if((string)$xml['stat'] == 'fail') {
            $this->_err_code = (int)$xml->err['code'];
            $this->_err_msg  = (string)$xml->err['msg'];
            return 0;
        }

        if ((string)$xml['stat'] != 'ok') {
            $this->_err_code = 0;
            $this->_err_msg = "Unrecognised REST response status";
            return 0;
        }

        return $xml;
    }

    function getErrorCode() {
        return $this->_err_code;
    }

    function getErrorMessage() {
        return $this->_err_msg;
    }

    function showError() {
        echo "<br />ErrorCode: {$this->_err_code}<br />ErrorMessage: {$this->_err_msg}<br />\n";
    }

    function getAuthUrl($perms, $frob='') {
        $args = array('api_key'=>$this->_cfg['api_key'],'perms'=>$perms);

        if (strlen($frob)) { $args['frob'] = $frob; }

        $args['api_sig'] = $this->signArgs($args);

        $fields =  '';
        foreach($args as $k => $v) {
            if($fields) $fields.='&';
            $fields .= urlencode($k).'='.urlencode($v);
        }

        return $this->_cfg['auth_endpoint'].$fields;
    }


    function signArgs($args){
        ksort($args);
        $a = '';
        foreach($args as $k => $v) {
            $a .= $k . $v;
        }
        return md5($this->_cfg['api_secret'].$a);
    }
}


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: sw=4 ts=4 fdm=marker
 * vim<600: sw=4 ts=4
 */
?>
