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

/* The model holds all the data the applet uses.
   There is one model per instance, creating by 
   the applet on startup.  All data should be stored
   in the model, and no where else.  (One version 
   of the truth)
*/

public class Model 
{
    // Basic values either gotten from applet parameters, or set 
    // in the constructor
    private String term;
    private String requestURL;
    private int numSlots;

    // The object that will call php pages on the server
    private MIScheduleReader reader;

    // Holds information read in from php pages on the server
    private DivisionList divisionList;
    private Hashtable allCourseLists;
    private Hashtable allSectionLists;
    private Hashtable allBooks;
  
    // The information about available times entered by the user
    // into the applet.
    private TimeHolder timeHolder;

    // Holds the classes a user wants to take
    private Slot slots[];

    // All the sections in all the active, retrieved sectionLists
    public Vector sectionPackage;

    private Vector bestSchedules;

    public Model(String pTerm, String pRequestURL)
    {
        term = pTerm;
        requestURL = pRequestURL;
        numSlots = 8;
        slots = new Slot[numSlots];
        for (int i=0; i<numSlots; i++)
	{
            slots[i] = new Slot();
            slots[i].division = "";
            slots[i].course = "";
	}
        sectionPackage = new Vector(10,5);
    }

    public String getRequestURL()
    {
        return requestURL;
    }

    public boolean isDivisionValid(String abbrev)
    {
	if (divisionList == null) return false;
	else return divisionList.inList(abbrev);
    }

    public void requestDivisionList(MIScheduleListener listener)
    {
        if (divisionList == null)
        {
            //Get the division list from the server.  When the 
            //division list is ready, the listener will receive
            //an event.
            divisionList = new DivisionList(getReader());
            divisionList.addMIScheduleListener(listener);
            divisionList.start();
        }
        else
        {
            // Don't add a listener here.  The object should already have a listener.
            // If you add a listener, then we'll get multiple events thrown.
            divisionList.fireMIScheduleEvent();
	}
    }

    public void requestCourseList(MIScheduleListener listener, String division)
    {
        if (allCourseLists == null)
        {
            allCourseLists = new Hashtable();
        }
        if (allCourseLists.get(division) == null)
        {
            CourseList c = new CourseList(getReader(), division);
            allCourseLists.put(division, c);
            c.addMIScheduleListener(listener);
            c.start();
        }
        else
        {
            CourseList c = (CourseList) allCourseLists.get(division);
            // Don't add a listener here.  The object should already have a listener.
            // If you add a listener, then we'll get multiple events thrown.
            c.fireMIScheduleEvent();
        }
    }

    public void requestSectionList(MIScheduleListener listener, String division, String course)
    {
        if (allSectionLists == null)
        {
            allSectionLists = new Hashtable();
        }
        String key = division + ":" + course;
        if (allSectionLists.get(key) == null)
        {
            SectionList s = new SectionList(getReader(), division, course);
            allSectionLists.put(key, s);
            s.addMIScheduleListener(listener);
            s.start();
        }
        else
        {
            SectionList s = (SectionList) allSectionLists.get(key);
            // Don't add a listener here.  The object should already have a listener.
            // If you add a listener, then we'll get multiple events thrown.
            s.fireMIScheduleEvent();
        }
    }

    /*
    public void requestBooks(MIScheduleListener listener, String division, String course)
    {
        if (allBooks == null)
        {
            allBooks = new Hashtable();
        }
        String key = division + ":" + course;
        if (allBooks.get(key) == null)
        {
            BooksForCourse b = new BooksForCourse(this, division, course);
            allBooks.put(key, b);
            b.addMIScheduleListener(listener);
            b.start();
        }
        else
        {
            BooksForCourse b = (BooksForCourse) allBooks.get(key);
            // Don't add a listener here.  The object should already have a listener.
            // If you add a listener, then we'll get multiple events thrown.
            b.fireMIScheduleEvent();
        }
    }

    public Vector getBooksForUsed()
    {
        Vector v = new Vector();
        if (allBooks != null) 
	{
	    int i = 0;
            Slot s = new Slot();
            i = getNextFilledSlot(i,s);
            while (i != -1)
	    {
               String key = s.division + ":" + s.course;
               BooksForCourse b = (BooksForCourse) allBooks.get(key);
               if (b != null) 
	       {
		   v.addElement(b);
	       }
               i = getNextFilledSlot(i+1,s);
            }
        }
        return v;
    }
    */

    public MIScheduleReader getReader()
    {
        if (reader == null) 
        {
            reader = new MIScheduleReader(this);
        }
        return reader;
    }

    public String getTerm()
    {
        return term;
    }

    public TimeHolder getTimeHolder()
    {
        if (timeHolder == null)
        {
            timeHolder = new TimeHolder();
        }
        return timeHolder;
    }

    public int getNumClassesToUse()
    {
        return 0;
    }
    
    public int getNumSlots()
    {
        return numSlots;
    }

    public Slot getSlotAt(int i)
    {
        if (slots[i] == null) slots[i] = new Slot();
        return slots[i];
    }


    // Returns the number of slots that have complete data
    public int getNumFilledSlots()
    {
        int num = 0;
        for (int i=0; i<numSlots; i++)
        {
            if (slots[i] != null)
            {
                if (slots[i].division.length() > 0 && slots[i].course.length() > 0)
                num++;
            }
        }
        return num;
    }

    // Get the next slot that has complete data, after, not including, slot i.
    public int getNextFilledSlot(int i, Slot s)
    {
        if (slots == null) return -1;

        for (;i<numSlots; i++)
        {
            if (slots[i] != null)
            {
                if (slots[i].division.length() > 0 && slots[i].course.length() > 0)
                {
                    s.division = slots[i].division;
                    s.course = slots[i].course;
                    return i;
                }
            }
        }
        return -1;
    }

    public Vector getBestSchedules()
    {
	return bestSchedules;
    }

    public void setBestSchedules(Vector s)
    {
	bestSchedules = s;
    }
}
