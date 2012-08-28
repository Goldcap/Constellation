/*****************************************************
*  
*  Copyright 2009 Adobe Systems Incorporated.  All Rights Reserved.
*  
*****************************************************
*  The contents of this file are subject to the Mozilla Public License
*  Version 1.1 (the "License"); you may not use this file except in
*  compliance with the License. You may obtain a copy of the License at
*  http://www.mozilla.org/MPL/
*   
*  Software distributed under the License is distributed on an "AS IS"
*  basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
*  License for the specific language governing rights and limitations
*  under the License.
*   
*  
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2009 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.httpstreaming
{
	import __AS3__.vec.Vector;
	
	public class AdobeFragmentRunTableDescriptor
	{
		public var timeScale:uint = 1000;
		public var qualitySegmentUrlModifiers:Vector.<String> = new Vector.<String>();
		public var fragmentRunTableEntries:Vector.<FragmentRunTableEntryDescriptor> = new Vector.<FragmentRunTableEntryDescriptor>();
		
		public function AdobeFragmentRunTableDescriptor()
		{
		}

		public function addQualitySegmentUrlModifier(modifier:String):void
		{
			qualitySegmentUrlModifiers.push(modifier);
		}

		public function addEntry(	
			firstFragment:uint, firstFragmentTimeStampH:uint, firstFragmentTimeStampL:uint, duration:uint, discontinuityIndicator:uint):void
		{
			fragmentRunTableEntries.push(new FragmentRunTableEntryDescriptor(firstFragment, firstFragmentTimeStampH, firstFragmentTimeStampL, duration, discontinuityIndicator)); 
		}	
	}
}