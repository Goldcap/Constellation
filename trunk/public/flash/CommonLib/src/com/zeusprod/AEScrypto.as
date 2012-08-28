package com.zeusprod {
	
	import com.lia.crypto.AES;
	import com.zeusprod.Log;
	public class AEScrypto
	{
		private static const DELIMITER:String = "|";
		private static const DECRYPT_KEYSTR:String = "lockmeAmadeus256";
		private static const ENCRYPT_KEYSTR:String = DECRYPT_KEYSTR;
		private static const KEY_LENGTH:Number = AES.BIT_KEY_256;
		private static const PERIOD:String = ".";
		private static const SLASH:String = "/";
		private static const HYPHEN:String = "-";
		private static const EQUALS:String = "=";
		private static var _substitution:Boolean;
		
		public function AEScrypto()
		{
		}
		
		public static function set substitution(val:Boolean):void {
			_substitution = val;
		}
		
		public static function get substitution():Boolean {
			return _substitution;
		}
		public static function reencrypt (encryptedStr:String):String {
			var tmp:String = decrypt(encryptedStr);
			Log.traceMsg ("De-encrypted string is " + tmp, Log.WARN);
			tmp = addTimeStamp(tmp);
			Log.traceMsg ("De-encrypted with addTimeStamp string is " + tmp, Log.WARN);
			tmp = encrypt (tmp);
			Log.traceMsg ("Encrypted String is " + tmp, Log.WARN);
			return tmp;
		}
		
		public static function decrypt(encryptedStr:String):String {
			// Prior to decrypting, replace all "." with "/"
			var tmp:String = replace(encryptedStr, PERIOD, SLASH);
			// Prior to decrypting, replace all "-" with "="
			var tmp2:String = replace(tmp, HYPHEN, EQUALS);
			return AES.decrypt(tmp2, DECRYPT_KEYSTR, KEY_LENGTH);
		}
		
		public static function encrypt(unencryptedStr:String):String {
			var tmp:String = AES.encrypt(unencryptedStr, ENCRYPT_KEYSTR, KEY_LENGTH);
			// After encrypting, replace all "/" with "."
			tmp = replace(tmp, SLASH, PERIOD);
			// After encrypting, replace all "=" with "-"
			var tmp2:String = replace(tmp, EQUALS, HYPHEN);
			return tmp2;
		}
		
		private static function replace (inString:String, replaceThis:String, withThis:String):String {
			if (substitution) {
				var tmp:String = (inString.split(replaceThis)).join(withThis);
				return tmp;
			} else {
				return inString;
			}	
		} 
		
		public static function unitTest():void {
			var test1:String = "foo";
			trace ("Replace test1: " + test1 + " '" + replace(test1, SLASH, PERIOD) + "'");
			
			test1 = "foo/";
			trace ("Replace test1: " + test1 + " '" + replace(test1, SLASH, PERIOD) + "'");
			
			test1 = "foo.";
			trace ("Replace test1: " + test1 + " '" + replace(test1, SLASH, PERIOD) + "'");
			
			test1 = "/foo.";
			trace ("Replace test1: " + test1 + " '" + replace(test1, SLASH, PERIOD) + "'");
			
			test1 = "///foo///";
			trace ("Replace test1: " + test1 + " '" + replace(test1, SLASH, PERIOD) + "'");
			
			
			var sampleAESText256:String = "seatID_123|filmID_456|HMAC_token_789";
			if (decrypt(encrypt(sampleAESText256)) == sampleAESText256) {
				trace ("Successful test of AES crypto");
			} else {
				trace ("Failed test of AES crypto");
			}
			
			var tmp:String = encrypt(sampleAESText256);
			trace ("Testing: " + decrypt(reencrypt(tmp)));
		}
		
		public static function addTimeStamp(inString:String):String {
			var utcDate:Date = new Date();
			var utcTimeStamp:String;
			utcTimeStamp = utcDate.toUTCString();
			trace( "utcTimeStamp: toUTCString is " + utcTimeStamp);
			utcTimeStamp = String(utcDate.time);
			trace( "utcTimeStamp: getMilliseconds is " + utcTimeStamp);
			return (inString + DELIMITER + utcTimeStamp);
		}
			
	}
}
