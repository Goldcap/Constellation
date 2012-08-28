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
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.traits.PlayState;

	public class TestHTMLPlayTrait extends TestCaseEx
	{
		public function testHTMLPlayTrait():void
		{
			htmlElement = new HTMLElement();
			var trait:HTMLPlayTrait = new HTMLPlayTrait(htmlElement);
			assertNotNull(trait);
			
			trait.playState = PlayState.PAUSED;
			trait.playState = PlayState.STOPPED;
			trait.playState = PlayState.PLAYING;
			
		}
		
		private var htmlElement:HTMLElement;
	}
}