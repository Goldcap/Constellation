/*---------------------------------------------------------------------------------------------

	[AS3] traceDL
	=======================================================================================

	Copyright (c) 2009 Tomek 'Og2t' Augustyn
	
	e	tomek@blog2t.net
	w	http://play.blog2t.net

	Please retain this info when redistributed.

	VERSION HISTORY:
	v0.1	30/4/2009	Initial concept
	v0.2	1/5/2009	Added more params, filter and depth
	
	USAGE:
	
	// displays the whole display list of any displayObject 
	traceDL(displayObject);
	
	// displays all displayObjects matching "filterString"
	traceDL(displayObject, "filterString");
	
	// displays the display list of any displayObject up to the given depth
	traceDL(displayObject, depth);

---------------------------------------------------------------------------------------------*/

package
{
	import flash.display.DisplayObjectContainer;
	import flash.display.DisplayObject;
    
    import com.zeusprod.Log;

	public function traceDL(container:DisplayObjectContainer, options:* = undefined, indentString:String = "", depth:int = 0, childAt:int = 0):void
	{
		if (typeof options == "undefined") options = Number.POSITIVE_INFINITY;
		
		if (depth > options) return;

		const INDENT:String = "   ";
		var i:int = container.numChildren;

		while (i--)
		{
			var child:DisplayObject = container.getChildAt(i);
			var output:String = indentString + (childAt++) + ": " + child.name + " ➔ " + child;

			// debug alpha/visible properties
			output += "\t\talpha: " + child.alpha.toFixed(2) + "/" + child.visible;

			// debug x and y position
			output += ", @: (" + child.x + ", " + child.y + ")";

			// debug transform properties
			output += ", w: " + child.width + "px (" + child.scaleX.toFixed(2) + ")";
			output += ", h: " + child.height + "px (" + child.scaleY.toFixed(2) + ")"; 
			output += ", r: " + child.rotation.toFixed(1) + "°";

			if (typeof options == "number") {
                Log.traceMsg (output, Log.LOG_TO_CONSOLE); 
                trace(output);
			} else if (typeof options == "string" && output.match(new RegExp(options, "gi")).length != 0)
				{
					
                    Log.traceMsg (output + "in" + container.name, Log.LOG_TO_CONSOLE); 
                    trace(output, "in", container.name, "➔", container);
				}

			if (child is DisplayObjectContainer) traceDL(DisplayObjectContainer(child), options, indentString + INDENT, depth + 1);
		}
	}
}
