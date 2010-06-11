/*  
    Copyright 2004, Dan Hostetler

    This file is part of MISchedule.

    MISchedule is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    MISchedule is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with MISchedule; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

import java.net.*;
import java.io.*;

public class MIScheduleReader 
{
    private Model model;
    private String requestURL;
    private int requestType;

    public MIScheduleReader(Model pModel)
    {
	model = pModel;
	requestURL = model.getRequestURL(); 
    }

    public InputStream getDivisionList()
    {
	String term = model.getTerm();
	return getURL(requestURL + "?command=divisions&term=" + term);
    }

    public InputStream getCourseList( String division )
    {
	String term = model.getTerm();
	return getURL(requestURL + "?command=courses&term="  + term + "&division=" + division);
    }

    public InputStream getSectionList( String division, String course )
    {
	String term = model.getTerm();
	return getURL(requestURL + "?command=sections&term="  + term + "&division=" + division + "&course=" + course);
    }

    public InputStream getBooksForCourse( String division, String course )
    {
	String term = model.getTerm();
	return getURL(requestURL + "?command=books&term="  + term + "&division=" + division + "&course=" + course);
    }

    private InputStream getURL( String s )
    {
	try 
	{
            //System.out.println("getting URL: " + s);
	    URL u = new URL(s);
	    InputStream i = u.openStream();
	    return i;
	}
	catch (MalformedURLException e)
	{
	    return null;
	}   
	catch(IOException e)
	{
	    return null;
	}	    
    }
}
