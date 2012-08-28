<?php

/**
 * Package:     URL-Based Authentication Token Generator for PHP
 * Title:       URLToken
 * Description: Implements token generation functionality necessary for
 *              URLs using URL-Based Authentication
 * Copyright:   Copyright (c) Akamai Technologies, Inc. 2005
 * Company:     Akamai Technologies, Inc.
 */

/**
 * Returns the URL path with the authorization token appended. See the
 *   README for more details.
 */
function urlauth_gen_url($sUrl, $sParam, $nWindow,
                         $sSalt, $sExtract, $nTime) {

	$sToken = urlauth_gen_token($sUrl, $nWindow, $sSalt,
                                    $sExtract, $nTime);
	
	if ($sToken == null) {
		return;
	}

	if (($sParam == "") || (!is_string($sParam))) {
		$sParam = "__gda__";
	}

  if ((strlen($sParam) < 5) || (strlen($sParam) > 12)) {
      return;
  }
	
	if (($nWindow < 0) || (!is_integer($nWindow))) {
		return;
	}

	if (($nTime <= 0) || (!is_integer($nTime))) {
		$nTime = time();
	}

	$nExpires = $nWindow + $nTime;
	
	/*Added by AMADSEN*/
	$res = $nExpires . "_" . $sToken;
	return $res;
	
	if (strpos($sUrl, "?") === false) {
		$res = $sUrl . "?" . $sParam . "=" . $nExpires . "_" . $sToken;
	} else {
		$res = $sUrl . "&" . $sParam . "=" . $nExpires . "_" . $sToken;
	}
	;
	return $res;
}

/**
 * Returns the hash portion of the token. This function should not be
 *   called directly.
 */
function urlauth_gen_token($sUrl, $nWindow, $sSalt,
                           $sExtract, $nTime) {
	if (($sUrl == "") || (!is_string($sUrl))) {
		return;
	}

	if (($nWindow < 0) || (!is_integer($nWindow))) {
		return;
	}

	if (($sSalt == "") || (!is_string($sSalt))) {
		return;
	}

	if (!is_string($sExtract)) {
		$sExtract = "";
	}

	if (($nTime <= 0) || (!is_integer($nTime))) {
		$nTime = time();
	}

	$nExpires = $nWindow + $nTime;
	$sExpByte1 = chr($nExpires & 0xff);
	$sExpByte2 = chr(($nExpires >> 8) & 0xff);
	$sExpByte3 = chr(($nExpires >> 16) & 0xff);
	$sExpByte4 = chr(($nExpires >> 24) & 0xff);

	$sData = $sExpByte1 . $sExpByte2 . $sExpByte3 . $sExpByte4
                 . $sUrl . $sExtract . $sSalt;

	$sHash = _unHex(md5($sData));

	$sToken = md5($sSalt . $sHash);
	return $sToken;
}

/**
 * Helper function used to translate hex data to binary
 */
function _unHex($str) {
	$res = "";
	for ($i = 0; $i < strlen($str); $i += 2) {
        $res .= chr(hexdec(substr($str, $i, 2)));
	}
	return $res;
}

?>
