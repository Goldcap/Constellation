/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
*/
package com.llnw.utils 
{
	import flash.display.MovieClip;
	
	/**
	 * ...
	 * @author LLNW
	 * A simple class used to center MovieClips.
	 */
	public class CenterUtil 
	{
		
		public function CenterUtil() 
		{
			//constructed
		}
		
		/**
		 * Method handles centering an item with in its container.
		 * @param	targetItem
		 * @param	container
		 */
		
		public function center(targetItem:MovieClip, container:MovieClip):void {
			targetItem.x = (container.width / 2) - (targetItem.width / 2)
			targetItem.y = (container.height/2) - (targetItem.height/2)
		}
		/**
		 * Method centers a movieclip with the parameters set with in an object.
		 * @param	targetItem
		 * @param	container
		 */
		public function centerObj(targetItem:MovieClip, container:Object):void {
			targetItem.x = (container.width / 2) - (targetItem.width / 2)
			targetItem.y = (container.height/2) - (targetItem.height/2)
		}
		/**
		 * Centers a movieClip on the Y axis
		 * @param	targetItem
		 * @param	container
		 */
		public function centerY(targetItem:MovieClip, container:MovieClip):void {
			
			targetItem.y = (container.height/2) - (targetItem.height/2)
		}
		/**
		 * Centers a movieClip on the X axis
		 * @param	targetItem
		 * @param	container
		 */
		public function centerX(targetItem:MovieClip, container:MovieClip):void {
			targetItem.x = (container.width / 2) - (targetItem.width / 2)
			
		}
		
	}
	
}