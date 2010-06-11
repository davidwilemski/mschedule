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

public class WorkingSectionList
{
    private String division;
    private String course;
    private Vector sectionTypeLists;
    private int worstPossibleScore = -200;

    public WorkingSectionList(SectionList sl, int[] pTimeValues)
    {
        division = sl.getDivision();
        course = sl.getCourse();
        sectionTypeLists = new Vector(3,3);
        int n = sl.getNumSections();
        for (int i=0; i<n; i++)
	{
            Section s = sl.getSectionAt(i);
	    if (!s.consider) continue;
            score(s, pTimeValues);
            boolean match = false;
            for (int j=0; j<sectionTypeLists.size(); j++)
	    {
                Vector listForType = (Vector) sectionTypeLists.elementAt(j);
                if ( ((Section)listForType.elementAt(0)).sectionType.compareTo(s.sectionType) == 0 )
		{
                    match = true;
  		    listForType.addElement(s);
                    break;
                }                
            }
            if (!match)
	    {
                Vector v = new Vector(100,100);
                v.addElement(s);
                sectionTypeLists.addElement(v);
            }
        }

        System.out.println("Working Section List: Finished separation, beginning sorting"); 

        for (int i=0; i<sectionTypeLists.size(); i++)
	{
            sortByScore((Vector)sectionTypeLists.elementAt(i));
	}
    }

    public void addToMasterList(Vector masterList)
    {
        for (int i=0; i<sectionTypeLists.size(); i++)
	{
            masterList.addElement(sectionTypeLists.elementAt(i));
        }
    }

    private void score(Section s, int values[])
    {
        s.score = 0;
        // Score based on values
        for (int j=0; j<s.startTime.length; j++)
        {
            for (int k=s.startTime[j]; k<s.endTime[j]; k++)
                s.score -= values[k];
        }
        // Score based on open seats
        if (s.openSeats > 10) s.score -= 0;
        else if (s.openSeats > 5) s.score -= 10;
        else if (s.openSeats > 0) s.score -= 20;
        // the section is closed
        else if (s.waitlistNum == -1) s.score -= 50;
        else if (s.waitlistNum < 5) s.score -= 50;
        else if (s.waitlistNum < 10) s.score -= 75;
        else s.score -= 100;
      
        if (s.score < worstPossibleScore) s.score = worstPossibleScore;
    }

    private void sortByScore(Vector v)
    {
	// okay, we are using a mode sort, I think its called
        
        int numModes = (-1*worstPossibleScore)+1;
        int modes[] = new int[numModes];        
        for (int i=0; i<numModes; i++) modes[i] = 0;
        for (int i=0; i<v.size(); i++)
	{
            int index = -1*((Section)v.elementAt(i)).score;
            if (index < 0 || index > numModes)
	    {
		System.out.println("invalid score for section");
                return;
            }
            modes[index]++;
        }
        int positions[] = new int[numModes];
        positions[0] = 0;
        for (int i=1; i<numModes; i++)
	{
            positions[i] = positions[i-1] + modes[i-1];
        }

        /*for (int i=0; i<numModes; i++)
	{
            if (modes[i] !=0) System.out.println(i + " " + modes[i] + " " + positions[i]);
        }*/
	
        Section sorted[] = new Section[v.size()];
        for (int i=0; i<v.size(); i++)
	{
            Section s = (Section)v.elementAt(i);
            int index = -1*s.score;
            int newPos = positions[index] + modes[index] - 1;
            //System.out.println(newPos);
            sorted[newPos] = s;
            modes[index]--;
        }

        v.removeAllElements();
        for (int i=0; i<sorted.length; i++)
	{
            v.addElement(sorted[i]);
	}
    }
}
