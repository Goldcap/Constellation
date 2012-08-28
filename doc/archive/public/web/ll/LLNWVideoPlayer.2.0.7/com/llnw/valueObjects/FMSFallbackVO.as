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
	 * Object represenation of a fallback port/protocol
	 * 
	 * @author LLNW
	 */
	public class FMSFallbackVO
	{
		/**
		 * The protocol to use in the fallback.
		 */
		public var protocol:String;
		
		/**
		 * The port to use in the fallback.
		 */		
		public var port:Number;
		
		/**
		 * Constructor.
		 * 
		 * @param protocol
		 * @param port Default is -1 which is equivalent to <code>null</code>.
		 */		
		public function FMSFallbackVO(protocol:String=null, port:Number=-1)
		{
			if(protocol) this.protocol = protocol;
			if(port > -1) this.port = port;
		}
	}
}