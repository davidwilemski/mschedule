//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################
var allowSlide = false;
var previewBoxThickness = 2;

//moves the specified object (named by CSS ID) according to rescaled values from another object
function move_large(event,id,obj,scale,targetboxSize,prevboxID,overrideSlide)
{	
	if (allowSlide == false && overrideSlide != true)
		return;
	
	var sourceCoords = get_relativeMouseCoords(event,obj);

	//re-center the coordinates
	sourceCoords.x -= targetboxSize.x/2/scale + previewBoxThickness;
	sourceCoords.y -= targetboxSize.y/2/scale + previewBoxThickness;

	//scale the coordinates
	var newX = -1*(sourceCoords.x)*scale;
	var newY = -1*(sourceCoords.y)*scale;

	//move the target box
	document.getElementById(id).style.left = newX+"px";
	document.getElementById(id).style.top = newY+"px";

	//if the proper preview box DIV exists, set its properties
	var previewBox = document.getElementById(prevboxID);
	
	if (!previewBox || browser == "Safari")
		return; //return early if this div doesnt exist.  Also, this doesnt currently work in Safari

	previewBox.style.borderWidth = previewBoxThickness+"px";
	previewBox.style.left = sourceCoords.x+"px";
	previewBox.style.top = sourceCoords.y+"px";
	previewBox.style.width = targetboxSize.x/scale+"px";
	previewBox.style.height = targetboxSize.y/scale+"px";
}

//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################

//decide whether the div can slide (usually activated by mouseup/mousedown, for a "drag" effect)
function move_large_allow(){allowSlide = true;}
function move_large_disallow(){allowSlide = false;}

//show/hide elements by ID
function showID(id)
{
	document.getElementById(id).style.visibility = "visible";
}

function hideID(id)
{
	document.getElementById(id).style.visibility = "hidden";
}

//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################
function center_large_onCoords(largeID,x,y,targetboxSize,prevboxID,scale)
{
	var large = document.getElementById(largeID);

	//re-center the coordinates
	newX = -1*(x-targetboxSize.x/2);
	newY = -1*(y-targetboxSize.y/2);
	
	large.style.left = newX+"px";
	large.style.top = newY+"px";
	
	//if the proper preview box DIV exists, set its properties
	var previewBox = document.getElementById(prevboxID);
	
	if (!previewBox || browser == "Safari")
		return; //return early if this div doesnt exist.  Also, this doesnt currently work in Safari
	
	//scale coords
	x = -1*newX/scale - previewBoxThickness;
	y = -1*newY/scale - previewBoxThickness;
		
	previewBox.style.borderWidth = previewBoxThickness+"px";
	previewBox.style.left = x+"px";
	previewBox.style.top = y+"px";
	previewBox.style.width = targetboxSize.x/scale+"px";
	previewBox.style.height = targetboxSize.y/scale+"px";
}

//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################
