//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//

package com.constellation.utilities
{
	/**
	 *  The TimeUtil utility class is an all-static class.
	 *  You do not create instances of TimeUtil;
	 *  instead you call methods such as 
	 *  the <code>TimeUtil.timeCode()</code> method.  
	 */
	public class TimeUtil
	{
		/** Utility function for generating time code given a number seconds.
		 * 
		 */
		public static function timeCode(sec:Number):String 
		{
			var h:Number = Math.floor(sec/3600);
			var m:Number = Math.floor((sec%3600)/60);
			var s:Number = Math.floor((sec%3600)%60);
			return (h == 0 ? "":(h<10 ? "0"+h.toString()+":" : h.toString()+":"))+(m<10 ? "0"+m.toString() : m.toString())+":"+(s<10 ? "0"+s.toString() : s.toString());
		}
		
		/**
		 * Utility function for returning a time in seconds given a time as a string.
		 * <p>
		 * Supports:<ul>
		 * <li>full clock format in "hours:minutes:seconds" (for example 00:03:00).</li>
		 * <li>offset time (for example 10s or 2m).</li>
		 * </ul></p>
		 * Times without units (for example 10) are assumed to be seconds.
		 */
		public static function parseTime(value:String):Number 
		{
			var time:Number = 0;
			var a:Array = value.split(":");
			
			if (a.length > 1) 
			{
				// Clock format, e.g. "hh:mm:ss"
				time = a[0] * 3600;
				time += a[1] * 60;
				time += Number(a[2]);
			}
			else 
			{
				// Offset time format, e.g. "1h", "8m", "10s"
				var mul:int = 0;
				
				switch (value.charAt(value.length-1)) 
				{
					case 'h':
						mul = 3600;
						break;
					case 'm':
						mul = 60;
						break;
					case 's':
						mul = 1;
						break;
				}
				
				if (mul) 
				{
					time = Number(value.substr(0, value.length-1)) * mul;
				}
				else 
				{
					time = Number(value);
				}
			}
			
			return time;
		}		
	}
}
