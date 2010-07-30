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

import java.io.*;
import java.util.*;

public class CourseList extends MIScheduleThread
{
    private Vector courses;
    private String division;
    private MIScheduleReader reader;

    public CourseList(MIScheduleReader pReader, String pDivision)
    {
	reader = pReader;
	division = pDivision;
    }

    public void run()
    {
	BufferedReader stream = new BufferedReader(new InputStreamReader( reader.getCourseList( division ) ));
 	
	try 
	{
	    // sometimes, there is space at the beginning
	    String s = stream.readLine();
	    while (s.length() == 0) 
	    { 
		s = stream.readLine();
	    }


            boolean validNumber = false;
            courses = null;
            int num = 0;

            try 
	    {
	        num = Integer.parseInt(s);
                validNumber = true;
            }
            catch (NumberFormatException e)
	    {
                readError("Invalid number of sections " + s);
            } 

            if (validNumber)
	    {
                if (num == -1)
	        {
                    readError(stream.readLine());
                }
                else
		{
                    // initial capacity 50, capacity increment 50
	            courses = new Vector(50,50);
                }

                for (int i=0; i<num; i++)
                {
		    Course c = new Course();
    		    c.number = stream.readLine();
		    c.name = stream.readLine();
		    courses.addElement(c);
	        }
            }
	}
	catch (IOException e)
	{
            readError("IOException.");
	}
	catch (NumberFormatException e)
	{
            readError("Number Format Exception.");
	}
	catch (Exception e)
	{
            readError("General Exception.");
	}

        fireMIScheduleEvent();
    }

    public Course getCourseAt(int i)
    {
        if (courses == null) return null;
	return (Course) courses.elementAt(i);
    }
    
    public int getNumCourses()
    {
        if (courses == null) return -1;
	return courses.size();
    }

    public String getDivision()
    {
        return division;
    }

    public void fireMIScheduleEvent()
    {
        MIScheduleEvent e = new MIScheduleEvent( this, this, MIScheduleEvent.GOT_COURSE_LIST );
        super.fireMIScheduleEvent(e);
    }
 
    private void readError(String s)
    {
        courses = null;
        System.out.println("Error getting course list for Division " + division + ": " + s);
    }	
}
