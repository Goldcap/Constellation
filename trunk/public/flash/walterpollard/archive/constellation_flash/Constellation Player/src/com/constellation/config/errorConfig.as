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
		
		//heart attack related
		private static var heartAttack_skippedBeats:String = "001";
		private static var heartAttack_failedResponseLimit:String = "002";
		private static var heartAttack_serverReject:String = "003";
		private static var heartAttack_unknownCause:String = "004";
		 
	//	private static var 
	//	private static var 
	//	private static var 
		
		public function errorConfig()
		{
		}
		//runtime releated errors
		public static function get init_Error():String{
			return INIT+initError//init-002
		}
		public static function get ticketParamsMissing():String{
			return INIT+missingTicketParams;
		}
		//auth releated errors
		public static function get auth_authorizeError():String{
			return AUTH+serverAuthRejection;
		}
		//bad smil file
		public static function get stream_smilMalformed():String{
			return STREAM+smilMalformed;
		}
		public static function get stream_streamNotFound():String{
			return STREAM+netStream_streamNotFound;
		}
		public static function get stream_securityError():String{
			return STREAM+netStream_securityError;
		}
		
		//streaming auth error
		public static function get auth_tokenHTTPErrorCode():String{
			return AUTH+tokenHTTPError;
		}
		
		
		//heartbeat related errors
		public static function get heartbeat_SkippedBeats_ErrorCode():String{
			return HEART_ATTACK+heartAttack_skippedBeats
		}
		public static function get heartbeat_FailedResponse_ErrorCode():String{
			return HEART_ATTACK+heartAttack_failedResponseLimit
		}
		public static function get heartbeat_ServerReject_ErrorCode():String{
			return HEART_ATTACK+heartAttack_serverReject;
		}
		public static function get heartbeat_Unknown():String{
			return HEART_ATTACK+heartAttack_unknownCause;
		}
	}
}