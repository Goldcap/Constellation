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
package org.osmf.elements
{
	import flexunit.framework.TestCase;
	
	import org.osmf.events.PlayEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.PlayState;
	import org.osmf.traits.PlayTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicPlayTrait;
	
	public class TestParallelElementWithPlayTrait extends TestCase
	{
		public function testPlayTrait():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertTrue(parallel.getTrait(MediaTraitType.PLAY) == null);
			
			// Create a few media elements with the PlayTrait.
			//
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.PLAY]);
			var playTrait1:PlayTrait = mediaElement1.getTrait(MediaTraitType.PLAY) as PlayTrait;
			playTrait1.play();
			assertTrue(playTrait1.playState == PlayState.PLAYING);

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.PLAY]);
			var playTrait2:PlayTrait = mediaElement2.getTrait(MediaTraitType.PLAY) as PlayTrait;
			assertTrue(playTrait2.playState == PlayState.STOPPED);
			
			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.PLAY]);
			var playTrait3:PlayTrait = mediaElement3.getTrait(MediaTraitType.PLAY) as PlayTrait;
			playTrait3.play();
			assertTrue(playTrait3.playState == PlayState.PLAYING);

			// Adding a child that's playing should cause the composition
			// to be considered as playing.
			parallel.addChild(mediaElement1);
			var playTrait:PlayTrait = parallel.getTrait(MediaTraitType.PLAY) as PlayTrait;
			assertTrue(playTrait != null);
			assertTrue(playTrait.playState == PlayState.PLAYING);
			
			playTrait.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onPlayStateChanged);
			assertTrue(playStateChangedEventCount == 0);
			
			// Adding a non-playing child should not affect the composition's
			// state, but the added child should now be playing.
			parallel.addChild(mediaElement2);
			assertTrue(playTrait != null);
			assertTrue(playTrait.playState == PlayState.PLAYING);
			assertTrue(playTrait2.playState == PlayState.PLAYING);
			assertTrue(playStateChangedEventCount == 0);

			// Adding a playing child should not affect the composition's
			// state.
			parallel.addChild(mediaElement3);
			assertTrue(playTrait != null);
			assertTrue(playTrait.playState == PlayState.PLAYING);
			assertTrue(playTrait3.playState == PlayState.PLAYING);
			assertTrue(playStateChangedEventCount == 0);
			
			// If we tell a child to pause, the composite trait should also
			// pause.
			playTrait1.pause();
			assertTrue(playTrait.playState == PlayState.PAUSED)
			assertTrue(playTrait1.playState == PlayState.PAUSED)
			assertTrue(playTrait2.playState == PlayState.PAUSED);
			assertTrue(playTrait3.playState == PlayState.PAUSED);
			assertTrue(playStateChangedEventCount == 1);

			// If we tell a child to stop, the composite trait should *not*
			// stop.  Stopping only propagates to the composite trait when
			// all children are stopped.
			playTrait3.stop();
			assertTrue(playTrait.playState == PlayState.PAUSED)
			assertTrue(playTrait1.playState == PlayState.PAUSED)
			assertTrue(playTrait2.playState == PlayState.PAUSED);
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			assertTrue(playStateChangedEventCount == 1);

			playTrait2.stop();
			assertTrue(playTrait.playState == PlayState.PAUSED)
			assertTrue(playTrait1.playState == PlayState.PAUSED)
			assertTrue(playTrait2.playState == PlayState.STOPPED);
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			assertTrue(playStateChangedEventCount == 1);

			playTrait1.stop();
			assertTrue(playTrait.playState == PlayState.STOPPED)
			assertTrue(playTrait1.playState == PlayState.STOPPED)
			assertTrue(playTrait2.playState == PlayState.STOPPED);
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			assertTrue(playStateChangedEventCount == 2);
						
			// If we tell the composite trait to play, all children should
			// play.
			playTrait.play();
			assertTrue(playTrait.playState == PlayState.PLAYING)
			assertTrue(playTrait1.playState == PlayState.PLAYING)
			assertTrue(playTrait2.playState == PlayState.PLAYING);
			assertTrue(playTrait3.playState == PlayState.PLAYING);
			assertTrue(playStateChangedEventCount == 3);
			
			// Removing a child should have no effect on the composition.
			parallel.removeChild(mediaElement3);
			assertTrue(playTrait.playState == PlayState.PLAYING)
			assertTrue(playTrait1.playState == PlayState.PLAYING)
			assertTrue(playTrait2.playState == PlayState.PLAYING);
			assertTrue(playStateChangedEventCount == 3);
			
			// Adding a non-playing child to a playing composition should cause
			// the child to start playing.
			playTrait3.stop();
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			parallel.addChild(mediaElement3);
			assertTrue(playTrait.playState == PlayState.PLAYING)
			assertTrue(playTrait1.playState == PlayState.PLAYING)
			assertTrue(playTrait2.playState == PlayState.PLAYING);
			assertTrue(playTrait3.playState == PlayState.PLAYING);
			assertTrue(playStateChangedEventCount == 3);
			
			parallel.removeChild(mediaElement3);
			
			// Adding a playing child to a paused composition should cause
			// the child to pause.
			playTrait.pause();
			assertTrue(playStateChangedEventCount == 4);
			parallel.addChild(mediaElement3);
			assertTrue(playTrait.playState == PlayState.PAUSED)
			assertTrue(playTrait1.playState == PlayState.PAUSED)
			assertTrue(playTrait2.playState == PlayState.PAUSED);
			assertTrue(playTrait3.playState == PlayState.PAUSED);
			assertTrue(playStateChangedEventCount == 4);
		}
		
		public function testPlayTraitWithCanPause():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// Create a few media elements with the PlayTrait.
			//
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.PLAY], null, null, true);
			var playTrait1:DynamicPlayTrait = mediaElement1.getTrait(MediaTraitType.PLAY) as DynamicPlayTrait;
			assertTrue(playTrait1.canPause);
			playTrait1.play();
			assertTrue(playTrait1.playState == PlayState.PLAYING);

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.PLAY], null, null, true);
			var playTrait2:DynamicPlayTrait = mediaElement2.getTrait(MediaTraitType.PLAY) as DynamicPlayTrait;
			assertTrue(playTrait2.playState == PlayState.STOPPED);
			
			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.PLAY], null, null, true);
			var playTrait3:DynamicPlayTrait = mediaElement3.getTrait(MediaTraitType.PLAY) as DynamicPlayTrait;
			playTrait3.play();
			assertTrue(playTrait3.playState == PlayState.PLAYING);
			
			// Make some unpausable.
			playTrait1.canPause = false;
			playTrait3.canPause = false;
			
			// Adding a child that's unpausable should cause the composition
			// to be unpausable.
			parallel.addChild(mediaElement1);
			var playTrait:PlayTrait = parallel.getTrait(MediaTraitType.PLAY) as PlayTrait;
			assertTrue(playTrait != null);
			assertTrue(playTrait.canPause == false);
			
			playTrait.addEventListener(PlayEvent.CAN_PAUSE_CHANGE, onCanPauseChanged);
			
			// But the composition is pausable if there's at least one child
			// that's pausable.
			parallel.addChild(mediaElement2);
			assertTrue(playTrait.canPause == true);
			assertTrue(canPauseChangedEventCount == 1);
			
			parallel.addChild(mediaElement3);
			assertTrue(playTrait.canPause == true);
			assertTrue(canPauseChangedEventCount == 1);
			
			parallel.removeChild(mediaElement2);
			assertTrue(playTrait.canPause == false);
			assertTrue(canPauseChangedEventCount == 2);
			
			parallel.removeChild(mediaElement3);
			assertTrue(playTrait.canPause == false);
			assertTrue(canPauseChangedEventCount == 2);
			
			// If we add an unpausable child to a pausable composition that's
			// paused, then we cannot change the unpausable child's state to
			// match that of the composition.
			parallel.addChild(mediaElement2);
			parallel.removeChild(mediaElement1);
			playTrait.pause();
			assertTrue(playTrait.canPause == true);
			assertTrue(playTrait.playState == PlayState.PAUSED);
			assertTrue(playTrait2.playState == PlayState.PAUSED);
			assertTrue(canPauseChangedEventCount == 3);
			
			// If the added child is playing, then it should remain so.
			playTrait3.play();
			assertTrue(playTrait3.playState == PlayState.PLAYING);
			parallel.addChild(mediaElement3);
			assertTrue(playTrait.canPause == true);
			assertTrue(playTrait.playState == PlayState.PAUSED);
			assertTrue(playTrait2.playState == PlayState.PAUSED);
			assertTrue(playTrait3.playState == PlayState.PLAYING);
			assertTrue(canPauseChangedEventCount == 3);
			
			parallel.removeChild(mediaElement3);
			
			// If the added child is stopped, then it should remain so.
			playTrait3.stop();
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			parallel.addChild(mediaElement3);
			assertTrue(playTrait.canPause == true);
			assertTrue(playTrait.playState == PlayState.PAUSED);
			assertTrue(playTrait2.playState == PlayState.PAUSED);
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			assertTrue(canPauseChangedEventCount == 3);
			
			// If we then remove the paused child, the composition
			// should reflect the change accordingly.
			parallel.removeChild(mediaElement2);
			assertTrue(playTrait.canPause == false);
			assertTrue(playTrait.playState == PlayState.STOPPED);
			assertTrue(playTrait3.playState == PlayState.STOPPED);
			assertTrue(canPauseChangedEventCount == 4);
			
			// Changing the canPause state of a child should affect
			// the composite trait's state.
			playTrait3.canPause = true;
			assertTrue(playTrait3.canPause == true);
			assertTrue(playTrait.canPause == true);
			assertTrue(canPauseChangedEventCount == 5);
		}
		
		private function onPlayStateChanged(event:PlayEvent):void
		{
			playStateChangedEventCount++;
		}

		private function onCanPauseChanged(event:PlayEvent):void
		{
			canPauseChangedEventCount++;
		}
		
		private var playStateChangedEventCount:int = 0;
		private var canPauseChangedEventCount:int = 0;
	}
}