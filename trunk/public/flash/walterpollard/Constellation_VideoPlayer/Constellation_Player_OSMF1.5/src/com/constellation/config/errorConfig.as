package com.constellation.config
{
	public class errorConfig
	{
		private var _classname:String = "com.constellation.config.errorConfig"
		//list of error types - actual code # will be in the call 
		private static var INIT:String = "init-";//any missing flashvars that are required to play, if any of them are missing
		private  static var HEART_ATTACK:String = "htbt-";
		private static var AUTH:String = "autx-";
		private static var STREAM:String = "stvl-";
		private static var DEFAULT:String = "dftf-";
		
		//codes for errors
		//init
		private static var missingTicketParams:String = "001";
		//auth
		private static var tokenHTTPError:String = "001";
		private static var initError:String = "002";
		
		private static var serverAuthRejection:String = "001" 
		//smil and stream related  related
		private static var smilMalformed:String = "001";
		private static var netStream_streamNotFound:String ="002";
		private static var netStream_securityError:String = "003";
		private static var osmf_relatedError:String = "004"
		//heart attack related
		private static var heartAttack_skippedBeats:String = "001";
		private static var heartAttack_failedResponseLimit:String = "002";
		private static var heartAttack_serverReject:String = "003";
		private static var heartAttack_unknownCause:String = "004";
		 
	//	private static var 
	//	private static var 
	//	private static var 
		private static var CODE_HTML_END_TAG:String = "</font>";
		private static var CODE_HTML_START_TAG:String ='<br><font size="22" color="#93c5fa">';
		
		public function errorConfig()
		{
		}
		public static function get osmf_error():String{
			return CODE_HTML_START_TAG+STREAM+osmf_relatedError+CODE_HTML_END_TAG
		}
		//runtime releated errors
		public static function get init_Error():String{
			return CODE_HTML_START_TAG+INIT+initError+CODE_HTML_END_TAG//init-002
		}
		public static function get ticketParamsMissing():String{
			return CODE_HTML_START_TAG+INIT+missingTicketParams+CODE_HTML_END_TAG;
		}
		//auth releated errors
		public static function get auth_authorizeError():String{
			return CODE_HTML_START_TAG+AUTH+serverAuthRejection+CODE_HTML_END_TAG;
		}
		//bad smil file
		public static function get stream_smilMalformed():String{
			return CODE_HTML_START_TAG+STREAM+smilMalformed+CODE_HTML_END_TAG;
		}
		public static function get stream_streamNotFound():String{
			return CODE_HTML_START_TAG+STREAM+netStream_streamNotFound+CODE_HTML_END_TAG;
		}
		public static function get stream_securityError():String{
			return CODE_HTML_START_TAG+STREAM+netStream_securityError+CODE_HTML_END_TAG;
		}
		
		//streaming auth error
		public static function get auth_tokenHTTPErrorCode():String{
			return CODE_HTML_START_TAG+AUTH+tokenHTTPError+CODE_HTML_END_TAG;
		}
		
		
		//heartbeat related errors
		public static function get heartbeat_SkippedBeats_ErrorCode():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_skippedBeats+CODE_HTML_END_TAG;
		}
		public static function get heartbeat_FailedResponse_ErrorCode():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_failedResponseLimit+CODE_HTML_END_TAG;
		}
		public static function get heartbeat_ServerReject_ErrorCode():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_serverReject+CODE_HTML_END_TAG;
		}
		public static function get heartbeat_Unknown():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_unknownCause+CODE_HTML_END_TAG;
		}
	}
}