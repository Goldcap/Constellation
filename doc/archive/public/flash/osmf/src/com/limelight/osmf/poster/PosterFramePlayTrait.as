/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
* 
*/

package com.limelight.osmf.poster
{
	import org.osmf.media.MediaElement;
	import org.osmf.traits.PlayState;
	import org.osmf.traits.PlayTrait;
	
	internal class PosterFramePlayTrait extends PlayTrait
	{
		public function PosterFramePlayTrait()
		{
			super();
		}
		
		override public function get canPause():Boolean
		{
			return false;
		}
		
		override protected function playStateChangeEnd():void
		{
			super.playStateChangeEnd();
			
			if (playState == PlayState.PLAYING)
			{
				// When the play() is finished, we reset our state to "not playing",
				// since this trait has completed its work.
				stop();
			}
		}
	}
}