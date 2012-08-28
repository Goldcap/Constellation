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
package org.osmf.traits
{
	import flash.display.Sprite;
	
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.utils.DynamicSeekTrait;

	public class TestSeekTraitAsSubclass extends TestSeekTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			return new DynamicSeekTrait(args.length > 0 ? args[0] : null);
		}
		
		override protected function get processesSeekCompletion():Boolean
		{
			// Some implementations of ISeekable will signal completion 
			// of a seek, although the default implementation doesn't.
			// Subclasses can override this to indicate that they process
			// completion.
			return true;
		}
	}
}
