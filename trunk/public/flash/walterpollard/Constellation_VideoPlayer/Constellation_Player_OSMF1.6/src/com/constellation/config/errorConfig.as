package com.constellation.config
{
	import com.constellation.externalConfig.ExternalConfig;

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
		private static var parseDataFileError:String = "002";
		private static var initError:String = "003";
		//auth
		private static var serverAuthRejection:String = "001" 
		private static var tokenHTTPError:String = "002";
		
		
	
		//smil and stream related  related
		private static var smilMalformed:String = "001";
		private static var netStream_streamNotFound:String ="002";
		private static var netStream_securityError:String = "003";
		
		private static var netStream_playFailed:String = "004";
		private static var netStream_timeout:String = "005";
		private static var stream_netRejectError:String = "006";
		private static var stream_netFailError:String = "007";
		private static var stream_ioError:String = "008";
		private static var stream_f4mFileInvalid:String = "009";
		private static var stream_httpGetFail:String = "010";
		
		
		//error not able to be id
		private static var osmf_relatedError:String = "099"
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
		private static var CODE_HTML_SEPARATOR:String = '</font><br><br><font size="14" color="#ffffff">Please include the following in the email: </font><br><br><font size="16" color="#93c5fa">';
		
		public function errorConfig()
		{
		}
		//auth releated errors
		//autx-001
		public static function get auth_authorizeError():String{
			return CODE_HTML_START_TAG+""+AUTH+serverAuthRejection+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		
		//streaming auth error
		//auth-002
		public static function get auth_tokenHTTPErrorCode():String{
			return CODE_HTML_START_TAG+AUTH+tokenHTTPError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		
		
		//runtime releated errors
		//init-001
		public static function get ticketParamsMissing():String{
			return CODE_HTML_START_TAG+INIT+missingTicketParams+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//init-002
		public static function get init_ParseDataError():String{
			return CODE_HTML_START_TAG+INIT+parseDataFileError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//init-003
		public static function get init_Error():String{
			return CODE_HTML_START_TAG+INIT+initError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG//init-002
		}
		
		
		
		//bad smil file
		//stvl-001
		public static function get stream_smilMalformed():String{
			return CODE_HTML_START_TAG+STREAM+smilMalformed+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-002
		public static function get stream_streamNotFound():String{
			return CODE_HTML_START_TAG+STREAM+netStream_streamNotFound+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-003
		public static function get stream_securityError():String{
			return CODE_HTML_START_TAG+STREAM+netStream_securityError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-004
		public static function get stream_playFailedError():String{
			return CODE_HTML_START_TAG+STREAM+netStream_playFailed+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-005
		public static function get stream_netTimeoutError():String{
			return CODE_HTML_START_TAG+STREAM+netStream_timeout+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-006
		public static function get stream_netRejectionError():String{
			return CODE_HTML_START_TAG+STREAM+stream_netRejectError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-007
		public static function get stream_netFailedError():String{
			return CODE_HTML_START_TAG+STREAM+stream_netFailError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-008
		public static function get stream_IOError():String{
			return CODE_HTML_START_TAG+STREAM+stream_ioError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-009
		public static function get stream_f4mInvalid():String{
			return CODE_HTML_START_TAG+STREAM+stream_f4mFileInvalid+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//stvl-010
		public static function get stream_httpGetFailed():String{
			return CODE_HTML_START_TAG+STREAM+stream_httpGetFail+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		//s
		//stvl-099
		public static function get osmf_error():String{
			return CODE_HTML_START_TAG+STREAM+osmf_relatedError+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG
		}
		
		
		
		//heartbeat related errors
		public static function get heartbeat_SkippedBeats_ErrorCode():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_skippedBeats+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		public static function get heartbeat_FailedResponse_ErrorCode():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_failedResponseLimit+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		public static function get heartbeat_ServerReject_ErrorCode():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_serverReject+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
		public static function get heartbeat_Unknown():String{
			return CODE_HTML_START_TAG+HEART_ATTACK+heartAttack_unknownCause+CODE_HTML_SEPARATOR+ExternalConfig.getInstance().ticketParams+CODE_HTML_END_TAG;
		}
	}
}