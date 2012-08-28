<?php

//PCI Complilance Reference
//http://pcianswers.com/2007/05/01/encryption-for-pci-compliance/
//http://blog.tevora.com/2010/03/29/HowToAddressTheCommonStumblingBlocksOfYourPCIAssessmentEncryption.aspx
//http://www.net-security.org/dl/articles/Key_Management_Procedures.pdf
//http://www.owasp.org/index.php/Cryptographic_Storage_Cheat_Sheet#Benefits

//For Key Generation
//openssl rand -out rand.bin 16

function encrypt( $string ) {
    if (strlen($string) > 0) {
      $key = preg_replace("/\n/","",file_get_contents(dirname(__FILE__)."/../../../cert/prod/crypt.txt"));
      
      //$key = sfConfig::get("sf_encrypt_key");
      return trim(base64_encode(mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $string, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		} else {
			return "";
		}
}

function decrypt( $string ) {
    if (strlen($string) > 0) {
      $key = preg_replace("/\n/","",file_get_contents(dirname(__FILE__)."/../../../cert/prod/crypt.txt"));
      
      //$key = sfConfig::get("sf_encrypt_key");
      return trim(mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, base64_decode($string), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		} else {
			return "";
		}
}

function encryptCookie($key, $value){
   if(!$value){return false;}
   $text = $value;
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
   $val = trim(base64_encode($crypttext)); //encode for cookie
   $val = str_replace("+",".",$val);
   return str_replace("=","-",$val);
}

function decryptCookie($key, $value){
   if(!$value){return false;}
   $value = str_replace("-","=",$value);
   $value = str_replace(".","+",$value);
   $crypttext = base64_decode($value); //decode cookie
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
   return trim($decrypttext);
}

?>
