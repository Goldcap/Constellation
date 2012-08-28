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
package com.llnw.valueObjects
{
	/**
	 * Object representation for a stream's playback stats
	 * 
	 * @author LLNW
	 */
	public class StreamStatVO
	{
		/**
		 * Total bytes 
		 */		
		public var bytesTotal:Number;
		
		/**
		 * Total bytes loaded 
		 */		
		public var bytesLoaded:Number;
		
		/**
		 * Total bytes 
		 */		
		public var bufferTime:Number;
		
		/**
		 * Total bytes loaded 
		 */		
		public var bufferLength:Number;
		
		/**
		 * Current playback time
		 */
		public var time:Number;
		
		/**
		 * Current media duration
		 */
		public var duration:Number;
	}
}