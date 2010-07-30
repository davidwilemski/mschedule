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

public class DivisionList extends MIScheduleThread
{
    private Vector divisions;
    private ClassesPanel panel;
    private MIScheduleReader reader;

    public DivisionList(MIScheduleReader pReader)
    {
        reader = pReader;	
    }

    public void run()
    {
	BufferedReader stream = new BufferedReader(new InputStreamReader( reader.getDivisionList() ));
 	
	try 
	{
	    // sometimes, there is space at the beginning
	    String s = stream.readLine();
	    while (s.length() == 0) 
	    { 
		s = stream.readLine();
	    }

            boolean validNumber = false;
            divisions = null;
            int num = 0;

	    // read the number of divisions
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
                    //Initial capacity 300, capacity increment 100
                    divisions = new Vector(300,100);

                    for (int i=0; i<num; i++)
	            {
	                Division d = new Division();
    		        d.abbrev = stream.readLine();
		        d.name = stream.readLine();
		        divisions.addElement(d);
	            }
	        }
            }
	}
	catch (IOException e)
	{
            readError("IOException");
	}
        catch (NumberFormatException e)
	{
            readError("Number Format Exception");
	}
        catch (Exception e)
	{
            readError("General Exception");
	}

        fireMIScheduleEvent();
    }

    public boolean inList(String abbrev)
    {
        for (int i=0; i<divisions.size(); i++)
	{
	    if ( ( (Division) divisions.elementAt(i)).abbrev.compareTo(abbrev) == 0)
		return true;
	}
	return false;
    }

    public Division getDivisionAt(int i)
    {
        if (divisions == null) return null;
	return (Division) divisions.elementAt(i);
    }
    
    public int getNumDivisions()
    {
        if (divisions == null) return -1;
	return divisions.size();
    }

    public void fireMIScheduleEvent() 
    {
        MIScheduleEvent e = new MIScheduleEvent( this, this, MIScheduleEvent.GOT_DIVISION_LIST );
        super.fireMIScheduleEvent(e);
    }

    private void readError(String s)
    {
        divisions = null;
        System.out.println("Error getting division list: " + s);
    }	
}
