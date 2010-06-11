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
import java.io.*;

public class SectionList extends MIScheduleThread
{
    private Vector sections;
    private String division;
    private String course;
    private MIScheduleReader reader;

    public SectionList(MIScheduleReader pReader, String pDivision, String pCourse)
    {
        reader = pReader;
	division = pDivision;
	course = pCourse;
    }

    public void run()
    {

	BufferedReader stream = new BufferedReader(new InputStreamReader( reader.getSectionList( division, course ) ));
 	
	try 
	{
	    // sometimes, there is space at the beginning
	    String s = stream.readLine();
	    while (s.length() == 0) 
	    { 
		s = stream.readLine();
	    }


            boolean validNumber = false;
            sections = null;
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
                    sections = new Vector(50,50);
                }

                for (int i=0; i<num; i++)
	        {
		    Section sc = new Section();
                    sc.division = division;
                    sc.course = course;
                    sc.classNum = stream.readLine();
                    sc.credits = stream.readLine();
                    sc.openSeats = Integer.parseInt( stream.readLine() );
                
                    try 
		    {
                        sc.waitlistNum = Integer.parseInt( stream.readLine() );
                    }
                    catch (NumberFormatException e)
		    {
                        sc.waitlistNum = 0;
                    }
		    if (sc.waitlistNum == -1)
			sc.waitlistDisplay = "No";
		    else
			sc.waitlistDisplay = new Integer(sc.waitlistNum).toString();	
                    sc.sectionType = stream.readLine();
		    sc.sectionTypeAbbrev = sc.sectionType.substring(0,3) + ".";
                    sc.sectionNum = stream.readLine();
                    sc.instructor = stream.readLine();
                    sc.linkageGroup = Integer.parseInt( stream.readLine() );
                    sc.numLocations = Integer.parseInt( stream.readLine() );
                    sc.timeString = new String[sc.numLocations];
                    sc.location = new String[sc.numLocations];
                    for (int j=0; j<sc.numLocations; j++)
                    {
                        sc.timeString[j] = stream.readLine();
                        sc.location[j] = stream.readLine();
                    }
                    sc.numMeetings = Integer.parseInt( stream.readLine() );
                    sc.startTime = new int[sc.numMeetings];
                    sc.endTime = new int[sc.numMeetings];
                    sc.campus = new String[sc.numMeetings];
                    for (int j=0; j<sc.numMeetings; j++)
		    {                
                        sc.startTime[j] = Integer.parseInt( stream.readLine() );
                        sc.endTime[j] = Integer.parseInt( stream.readLine() );
                        sc.campus[j] = stream.readLine();
                    }               
		    sections.addElement(sc);
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

    public Section getSectionAt(int i)
    { 
        if (sections == null) return null;
	return (Section) sections.elementAt(i);
    }
    
    public int getNumSections()
    { 
        if (sections == null) return -1;
	return sections.size();
    }

    public synchronized void fireMIScheduleEvent() 
    {
        MIScheduleEvent e = new MIScheduleEvent( this, this, MIScheduleEvent.GOT_SECTION_LIST );
        super.fireMIScheduleEvent(e);
    }

    public String getDivision()
    {
        return division;
    }

    public String getCourse()
    {
        return course;
    }

    private void readError(String s)
    {
        sections = null;
        System.out.println("Error getting section list for " + division + " " + course + ": " + s);
    }	
	
}
