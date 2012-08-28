package view
{
	import flash.filters.DropShadowFilter;
	import flash.media.Video;
	
	public class VideoScreen
	{
		private var _vid:Video;
		
		public function VideoScreen()
		{
			
		}
		
		public function get vid():Video
		{
			if (!_vid) 
			{ 	
				this._vid = new Video();
				this._vid.name += "VideoScreen constructor";
				_vid.deblocking = 2;
				_vid.smoothing = true;
				//_vid.filters = [new DropShadowFilter(10, 45, 0, .3)];
			}
			
			return _vid;
		}
	}
}