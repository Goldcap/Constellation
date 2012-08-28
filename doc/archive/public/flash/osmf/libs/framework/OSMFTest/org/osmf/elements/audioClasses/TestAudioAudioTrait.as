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
package org.osmf.elements.audioClasses
{
	import flash.net.URLRequest;
	import flash.media.Sound;
	
	import org.osmf.media.MediaElement;
	import org.osmf.traits.TestAudioTrait;
	import org.osmf.utils.TestConstants;

	public class TestAudioAudioTrait extends TestAudioTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			var sound:Sound = new Sound(new URLRequest(TestConstants.LOCAL_SOUND_FILE));
			var soundAdapter:SoundAdapter = new SoundAdapter(new MediaElement(), sound);

			return new AudioAudioTrait(soundAdapter);
		}
	}
}