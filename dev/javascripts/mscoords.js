var allowDrag = false;


//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################

//CLASS: holds an x,y coordinate
function Coordinate(x,y)
{
	this.x = x;
	this.y = y;
}

//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################

//gets the coordinates of the mouse in the window
function get_mousePosition(event)
{
	var coord;
		
	//try to do a Mozilla-style get position
	if (browser == "Mozilla" || browser == "Safari")
		coord = moz_get_mousePosition(event);
	
	//try to do an IE-style get position
	if (browser == "IE")
		coord = ie_get_mousePosition();
	
	//return coord if it is valid, (-1,-1) on failure
	if(coord)
		return coord;
	
	return new Coordinate(-1,-1);
}

function moz_get_mousePosition(myEvent)
{
	var clickX = myEvent.pageX ? myEvent.pageX : myEvent.clientX + parent.document.body.scrollLeft;
	var clickY = myEvent.pageY ? myEvent.pageY : myEvent.clientX + parent.document.body.scrollTop;
	//alert("(MOZ) x: "+clickX+", y: "+clickY);

	return new Coordinate(clickX, clickY);
}

function ie_get_mousePosition()
{
	var clickX = event.pageX ? event.pageX : event.clientX + parent.document.body.scrollLeft;
	var clickY = event.pageY ? event.pageY : event.clientY + parent.document.body.scrollTop;
	//alert("(IE) x: "+clickX+", y: "+clickY);

	return new Coordinate(clickX, clickY);
}

/*
function safari_get_mousePosition()
{
	var clickX = event.pageX;
	var clickY = event.pageY;
	alert("(SAF) x: "+clickX+", y: "+clickY);

	return new Coordinate(clickX, clickY);
}
*/

//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################

//gets the coordinates of a CSS object
function get_objectPosition(obj)
{
	objX = obj.offsetLeft;
	objY = obj.offsetTop;
	//alert("(OBJECT) x: "+objX+", y: "+objY);
	
	return new Coordinate(objX, objY);
}

//########################################################################################################################################
//########################################################################################################################################
//########################################################################################################################################

//gets the mouse coordinates relative to the object (obj) specified
function get_relativeMouseCoords(event,obj)
{
	var mousePos = get_mousePosition(event);
	var objPos = get_objectPosition(obj);
	
	var relX = mousePos.x - objPos.x;
	var relY = mousePos.y - objPos.y;
	//alert(mousePos.x+","+mousePos.y+"\n"+objPos.x+","+objPos.y+"\n(RELATIVE) x:"+relX+" y:"+relY);

	return new Coordinate(relX,relY);
}

