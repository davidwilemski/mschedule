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

import java.util.*;
import java.awt.*;

public class CourseListPanel extends DoubleListPanel
{
    private Vector firstColumn;
    private Vector secondColumn;

    public CourseListPanel() 
    {
	super();
    }

    public void setCourseList (CourseList pCourseList)
    {
	firstColumn = new Vector(300,100);
	secondColumn = new Vector(300,100);

	for (int i=0; i<pCourseList.getNumCourses(); i++)
	{
	    Course c = pCourseList.getCourseAt(i);
	    firstColumn.addElement(c.number);
	    secondColumn.addElement(c.name);
	}
	super.setData(firstColumn, secondColumn);	
    }

    public Dimension getPreferredSize()
    {
	return super.getPreferredSize();
    }

    // This function will get called from the super class when a row is changed
    public void rowChanged(int nSelected)
    {
        // fire an event saying that someone clicked a new division and now we need the new course list
        MIScheduleEvent e = new MIScheduleEvent( 
                                        this, 
                                        (String) firstColumn.elementAt( nSelected ),
                                        MIScheduleEvent.COURSE_SELECTED); 
	fireMIScheduleEvent( e );
    }
}
