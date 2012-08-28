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
package org.osmf.elements.htmlClasses
{
	import org.osmf.elements.HTMLElement;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.TestLoadTrait;

	public class TestHTMLLoadTrait extends TestLoadTrait
	{
		public function testHTMLLoadTrait():void
		{
			htmlElement = new HTMLElement();
			var trait:HTMLLoadTrait = new HTMLLoadTrait(htmlElement);
			assertNotNull(trait);
			
			trait.loadState = LoadState.READY;
			assertThrows(trait.load);
			
			trait.loadState = LoadState.LOADING;
			assertThrows(trait.load);
			
			htmlElement.resource = new MediaResourceBase();
			assertThrows(trait.load);
			
			trait.loadState = LoadState.UNLOADING;
			assertThrows(trait.unload);
			
			trait.loadState = LoadState.UNINITIALIZED;
			assertThrows(trait.unload);
		}
		
		private var htmlElement:HTMLElement;
	}
}