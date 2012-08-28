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
package org.osmf.elements.compositeClasses
{
	import flexunit.framework.TestCase;
	
	import org.osmf.elements.VideoElement;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitBase;
	import org.osmf.traits.MediaTraitType;	

	public class TestTraitAggregator extends TestCase
	{
		public function testListenedChild():void
		{			
			var aggr:TraitAggregator = new TraitAggregator();
			var video:VideoElement = new VideoElement();
			aggr.listenedChild = video;
			
			assertEquals(aggr.listenedChild, video);			
		}

		public function testGetNextChildWithTrait():void
		{			
			var aggr:TraitAggregator = new TraitAggregator();
			var video1:VideoElement = new VideoElement(new URLResource('http://test1.com/'));
			aggr.addChild(video1);
			var video2:VideoElement = new VideoElement(new URLResource('http://test2.com/'));
			aggr.addChild(video2);
			var video3:VideoElement = new VideoElement(new URLResource('http://test3.com/'));
			aggr.addChild(video3);
			
			assertEquals(aggr.getNextChildWithTrait(null, MediaTraitType.LOAD), video1);			
			assertEquals(aggr.getNextChildWithTrait(video1, MediaTraitType.LOAD), video2);
			assertEquals(aggr.getNextChildWithTrait(video2, MediaTraitType.LOAD), video3);
			assertEquals(aggr.getNextChildWithTrait(video3, MediaTraitType.LOAD), null);
		}
		
		public function testForEachChildTrait():void
		{			
			var aggr:TraitAggregator = new TraitAggregator();			
			var video:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			var loadableTrait:LoadTrait = video.getTrait(MediaTraitType.LOAD) as LoadTrait;
			
			aggr.addChild(video);
						
			var gotCallback:Boolean = false;
			function callback(trait:MediaTraitBase):void
			{
				gotCallback = true;
				assertEquals(trait, loadableTrait)
			}			
			aggr.forEachChildTrait(callback, MediaTraitType.LOAD);
			
			assertTrue(gotCallback);
			
			aggr.removeChild(video);
			gotCallback = false;
			
			aggr.forEachChildTrait(callback, MediaTraitType.LOAD);
			
			assertFalse(gotCallback);	
		}
		
		public function testInvokeOnEachChildTrait():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video1:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			var video2:VideoElement = new VideoElement(new URLResource('http://test2.com/'));
			aggr.addChild(video1);
			aggr.addChild(video2);
			
			aggr.invokeOnEachChildTrait("load", [], MediaTraitType.LOAD);
			
			assertEquals( (video1.getTrait(MediaTraitType.LOAD) as LoadTrait).loadState, LoadState.READY);
			assertEquals( (video2.getTrait(MediaTraitType.LOAD) as LoadTrait).loadState, LoadState.READY);
			
			(video1.getTrait(MediaTraitType.LOAD) as LoadTrait).unload();
			(video2.getTrait(MediaTraitType.LOAD) as LoadTrait).unload();
			
			aggr.removeChild(video1);
			aggr.removeChild(video2);
			
			aggr.invokeOnEachChildTrait("load", [], MediaTraitType.LOAD);  //Should be a no-op
			
			assertEquals( (video1.getTrait(MediaTraitType.LOAD) as LoadTrait).loadState, LoadState.UNINITIALIZED);
			assertEquals( (video2.getTrait(MediaTraitType.LOAD) as LoadTrait).loadState, LoadState.UNINITIALIZED);			
		}
		
		public function testHasTrait():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video1:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			var video2:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			aggr.addChild(video1);
			aggr.addChild(video2);
			
			assertTrue(aggr.hasTrait(MediaTraitType.LOAD));
			
			aggr.removeChild(video1);
				
			assertTrue(aggr.hasTrait(MediaTraitType.LOAD));	
			
			aggr.removeChild(video2);
					
			assertFalse(aggr.hasTrait(MediaTraitType.LOAD));			
		}
		
		public function testGetNumTraits():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video1:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			var video2:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			
			assertEquals( aggr.getNumTraits(MediaTraitType.LOAD), 0);
			
			aggr.addChild(video1);
			aggr.addChild(video2);
			
			assertEquals(aggr.getNumTraits(MediaTraitType.LOAD), 2);
			
			aggr.removeChild(video1);
				
			assertEquals(aggr.getNumTraits(MediaTraitType.LOAD), 1);	
			
			aggr.removeChild(video2);
					
			assertEquals(aggr.getNumTraits(MediaTraitType.LOAD), 0);			
		}
		
		public function testGetChildIndex():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video1:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			var video2:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			
			assertEquals(aggr.getChildIndex(null), -1);
			assertEquals(aggr.getChildIndex(video1), -1);
			assertEquals(aggr.getChildIndex(video2), -1);
			
			aggr.addChild(video1);
			aggr.addChild(video2);
			
			assertEquals(aggr.getChildIndex(video1), 0);
			assertEquals(aggr.getChildIndex(video2), 1);
			
			aggr.removeChild(video1);
				
			assertEquals(aggr.getChildIndex(video1), -1);
			assertEquals(aggr.getChildIndex(video2), 0);
			
			aggr.removeChild(video2);
					
			assertEquals(aggr.getChildIndex(video1), -1);
			assertEquals(aggr.getChildIndex(video2), -1);
		}
		
		public function testAddChild():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			
			var loadTraitSeen:Boolean = false;
			var playTraitSeen:Boolean = false;
						
			function onTraitAggregated(event:TraitAggregatorEvent):void
			{
				if (event.traitType == MediaTraitType.LOAD)
				{
					loadTraitSeen = true;
				}
				if (event.traitType == MediaTraitType.PLAY)
				{
					playTraitSeen = true;
				}
			}
			
			aggr.addEventListener(TraitAggregatorEvent.TRAIT_AGGREGATED, onTraitAggregated);
			
			aggr.addChild(video);
						
			assertTrue(loadTraitSeen);
			assertFalse(playTraitSeen);
			
			// Should add the PlayTrait, among others.
			aggr.invokeOnEachChildTrait("load", [], MediaTraitType.LOAD);
			
			assertTrue(loadTraitSeen);
			assertTrue(playTraitSeen);
		}
		
		public function testAddChildAt():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			
			var loadTraitSeen:Boolean = false;
			var playTraitSeen:Boolean = false;
						
			function onTraitAggregated(event:TraitAggregatorEvent):void
			{
				if (event.traitType == MediaTraitType.LOAD)
				{
					loadTraitSeen = true;
				}
				if (event.traitType == MediaTraitType.PLAY)
				{
					playTraitSeen = true;
				}
			}
			
			aggr.addEventListener(TraitAggregatorEvent.TRAIT_AGGREGATED, onTraitAggregated);
			
			aggr.addChildAt(video, 0);
			
			assertTrue(loadTraitSeen);
			assertFalse(playTraitSeen);
			
			aggr.invokeOnEachChildTrait("load", [], MediaTraitType.LOAD);  //Should add the playable trait, among others.
			
			assertTrue(loadTraitSeen);
			assertTrue(playTraitSeen);
			
			var video2:VideoElement = new VideoElement(new URLResource('http://test.com/2'));
			
			aggr.addChildAt(video2, 0);
			
			assertEquals(aggr.getNextChildWithTrait(video2, MediaTraitType.LOAD), video);
		}
		
		public function testRemoveChild():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			
			var loadTraitSeen:Boolean = false;
			var playTraitSeen:Boolean = false;
						
			function onTraitAggregated(event:TraitAggregatorEvent):void
			{
				if (event.traitType == MediaTraitType.LOAD)
				{
					loadTraitSeen = true;
				}
				if (event.traitType == MediaTraitType.PLAY)
				{
					playTraitSeen = true;
				}
			}
			
			aggr.addEventListener(TraitAggregatorEvent.TRAIT_UNAGGREGATED, onTraitAggregated);			
			aggr.addChild(video);									
			aggr.invokeOnEachChildTrait("load", [], MediaTraitType.LOAD);  //Should add the playable trait, among others.
									
			assertFalse(loadTraitSeen);
			assertFalse(playTraitSeen);		
			
			aggr.invokeOnEachChildTrait("unload", [], MediaTraitType.LOAD);	 ///should remove playable trait.
			
			assertFalse(loadTraitSeen);
			assertTrue(playTraitSeen);		
			
			//Should unaggregate all traits
			aggr.removeChild(video);
			
			assertTrue(loadTraitSeen);
			assertTrue(playTraitSeen);	
		}
		
		public function testRemoveChildAt():void
		{	
			var aggr:TraitAggregator = new TraitAggregator();			
			var video:VideoElement = new VideoElement(new URLResource('http://test.com/'));
			
			var loadTraitSeen:Boolean = false;
			var playTraitSeen:Boolean = false;
						
			function onTraitAggregated(event:TraitAggregatorEvent):void
			{
				if (event.traitType == MediaTraitType.LOAD)
				{
					loadTraitSeen = true;
				}
				if (event.traitType == MediaTraitType.PLAY)
				{
					playTraitSeen = true;
				}
			}
			
			aggr.addEventListener(TraitAggregatorEvent.TRAIT_UNAGGREGATED, onTraitAggregated);			
			aggr.addChild(video);									
			aggr.invokeOnEachChildTrait("load", [], MediaTraitType.LOAD);  //Should add the playable trait, among others.
									
			assertFalse(loadTraitSeen);
			assertFalse(playTraitSeen);		
			
			aggr.invokeOnEachChildTrait("unload", [], MediaTraitType.LOAD);	 ///should remove playable trait.
			
			assertFalse(loadTraitSeen);
			assertTrue(playTraitSeen);		
			
			// Should unaggregate all traits
			aggr.removeChildAt(0);
			
			assertTrue(loadTraitSeen);
			assertTrue(playTraitSeen);
			
			try
			{
				aggr.removeChildAt(0);
				
				fail();
			}
			catch (e:RangeError)
			{
			}
		}
	}
}