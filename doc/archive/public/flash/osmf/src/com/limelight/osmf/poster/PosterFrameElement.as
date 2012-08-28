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
	import org.osmf.elements.ImageElement;
	import org.osmf.elements.ImageLoader;
	import org.osmf.media.URLResource;
	import org.osmf.traits.MediaTraitType;

	public class PosterFrameElement extends ImageElement
	{
		public function PosterFrameElement(resource:URLResource=null, loader:ImageLoader=null)
		{
			super(resource, loader);
		}
		
		/**
		 * @private
		 **/
		override protected function processReadyState():void
		{
			super.processReadyState();
			
			addTrait(MediaTraitType.PLAY, new PosterFramePlayTrait());
		}
		
		/**
		 *  @private 
		 */ 
		override protected function processUnloadingState():void
		{
			super.processUnloadingState();
			
			removeTrait(MediaTraitType.PLAY);	
		}
		
	}
}