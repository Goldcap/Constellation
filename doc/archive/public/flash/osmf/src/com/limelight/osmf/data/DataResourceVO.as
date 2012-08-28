package com.limelight.osmf.data
{
	import com.limelight.osmf.chrome.AlertChrome;
	import com.limelight.osmf.chrome.ControlsChrome;
	import com.limelight.osmf.chrome.DebugChrome;
	import com.limelight.osmf.chrome.PlayOverlayChrome;
	import com.limelight.osmf.configuration.Configuration;
	
	import org.osmf.containers.MediaContainer;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaPlayer;
	
	
	public class DataResourceVO
	{
		private static var _instance:DataResourceVO;
		
		public function DataResourceVO(p_key:SingletonBlocker):void
		{
			if (p_key == null) 
			{
				throw new Error("Error: Instantiation failed: Use DataResourceVO.getInstance() instead of new.");
			}
		}
		
		public static function getInstance():DataResourceVO 
		{
			if (_instance == null) {
				_instance = new DataResourceVO(new SingletonBlocker());
			}
			return _instance;
		}
		
		
		private static var _container:MediaContainer = new MediaContainer();
		
		private static var _mediaFactory:DefaultMediaFactory = new DefaultMediaFactory();
		
		private static var _mediaPlayer:MediaPlayer = new MediaPlayer();
		
		private static var _controlBar:ControlsChrome = new ControlsChrome();
		
		private static var _playOverlay:PlayOverlayChrome = new PlayOverlayChrome();
		
		private static var _debugPanel:DebugChrome = new DebugChrome();
		
		private static var _alertPanel:AlertChrome = new AlertChrome();
		
		private static var _configuration:Configuration = new Configuration();
		
		
		public function get container():MediaContainer
		{
			return _container;
		}
		
		public function get mediaFactory():DefaultMediaFactory
		{
			return _mediaFactory;
		}
		
		public function get mediaPlayer():MediaPlayer
		{
			return _mediaPlayer;
		}
		
		public function get controlBar():ControlsChrome
		{
			return _controlBar;
		}
		
		public function get playOverlay():PlayOverlayChrome
		{
			return _playOverlay;
		}
		
		public function get debugPanel():DebugChrome
		{
			return _debugPanel;
		}
		
		public function get alertPanel():AlertChrome
		{
			return _alertPanel;
		}
		
		public function get configuration():Configuration
		{
			return _configuration;
		}
		
		
		public var resizeWidth:Number;
		public var resizeHeight:Number;
		
	}
}

internal class SingletonBlocker {}