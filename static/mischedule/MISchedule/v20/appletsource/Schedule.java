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

public class Schedule
{
    public Vector sections;
    public int overlap[];
    private int overlapPenalty=500;
    private int failedLinkagePenalty=1500;
    public int maxSections;
    public int commuteProblems=0;
    public int linkProblems=0;
    public int score = 0;

    private final static int ADD_TO_OVERLAP = 1;
    private final static int REMOVE_FROM_OVERLAP = 2;

    public Schedule(int pMaxSections)
    {
        maxSections = pMaxSections;
        sections = new Vector(maxSections, maxSections);
        overlap = new int[TimeHolder.NUM_TIME_VALUES];
        for (int i=0; i<TimeHolder.NUM_TIME_VALUES; i++) 
        {
            overlap[i] = 0;
        }
    }

    public Schedule(Schedule s)
    {
        maxSections = s.maxSections;
        sections = new Vector(maxSections, maxSections);
        overlap = new int[TimeHolder.NUM_TIME_VALUES];
        for (int i=0; i<TimeHolder.NUM_TIME_VALUES; i++) 
            overlap[i] = s.overlap[i];
        for (int i=0; i<s.sections.size(); i++)
            sections.addElement( s.sections.elementAt(i) );
        commuteProblems = s.commuteProblems;
        linkProblems = s.linkProblems;
        score = s.score;
    }

    public int getScore()
    {
        return score;
    }

    public int computeScore()
    {
        score = 0;
        for (int i=0; i<sections.size(); i++)
	{
            Section s = (Section) sections.elementAt(i);
            score += s.score;
	}
        for (int i=0; i<overlap.length; i++)
	{
            if (overlap[i] > 1) score -= (overlapPenalty * (overlap[i]-1));
	} 
        score -= commuteProblems * overlapPenalty;
        score -= linkProblems * failedLinkagePenalty;
        return score;
    }
    

    public int getNumSections()
    {
	return sections.size();
    }
   
    public Section getSectionAt(int i)
    {
        if (i<0 || i>=sections.size()) return null;

	return (Section) sections.elementAt(i);
    }

    public void addSection(Section s)
    {
        calcSectionOverlap( s, ADD_TO_OVERLAP );
        for (int i=0; i<sections.size(); i++)
	{
            commuteProblems += numCommuteProblems(s, (Section) sections.elementAt(i));
        }
        if (sections.size() >= 1)
	{ 
            //System.out.println("Adding section " + linkProblems);
            linkProblems += numLinkProblems(s, (Section) sections.elementAt( sections.size() - 1 ));
            //System.out.println("Finished adding " + linkProblems);
	}
        sections.addElement(s);
        computeScore();
    }

    public void removeLastSection()
    {
        int sectionToRemove = sections.size() - 1;
        calcSectionOverlap( (Section) sections.elementAt(sectionToRemove), REMOVE_FROM_OVERLAP );
        for (int i=0; i<sectionToRemove; i++)
	{
            commuteProblems -= numCommuteProblems((Section) sections.elementAt(sectionToRemove),(Section) sections.elementAt(i));
        }
        if (sectionToRemove > 0)
	{
            //System.out.println("removing section " + linkProblems);
	    linkProblems -= numLinkProblems((Section) sections.elementAt(sectionToRemove), (Section) sections.elementAt(sectionToRemove-1));
            //System.out.println("finished removing " + linkProblems);
	}
        sections.removeElementAt(sectionToRemove);
        computeScore();
    }

    public void calcSectionOverlap(Section s, int mode)
    {
        for (int i=0; i<s.startTime.length; i++)
	{
            for (int j=s.startTime[i]; j<s.endTime[i]; j++)
	    {
                if (mode == ADD_TO_OVERLAP)
                    overlap[j]++; 
                else overlap[j]--;
            }
	}
    }
    
    public int getEarliestStartTime()
    {
        for (int i=0; i<48; i++)
	    for (int j=0; j<5; j++)
		if (overlap[j*48+i] > 0) return i;
	return -1;
    }
   
    public int getLatestEndTime()
    {
        for (int i=48-1; i>=0; i--)
	    for (int j=0; j<5; j++)
		if (overlap[j*48+i] > 0) return i+1;
	return -1;
    }  

    // Calculates if there is a commuting problem for sections
    // on different campuses  
    private int numCommuteProblems(Section s1, Section s2)
    {
        int ret = 0;
        for (int i=0; i<s1.startTime.length; i++)
	{
	    for (int j=0; j<s2.startTime.length; j++)
	    {
                if ( s1.campus[i].compareTo(s2.campus[j]) != 0)
		{
		    if ( s1.startTime[i] == s2.endTime[j]) ret++;
		    if ( s1.endTime[i] == s2.startTime[j]) ret++;
		}
	    }
	}
        return ret;
    }

    private int numLinkProblems(Section s1, Section s2)
    {
        int retVal = 0;
        if ( (s1.division.compareTo(s2.division) == 0) &&
             (s1.course.compareTo(s2.course) == 0) &&
             (s1.linkageGroup != s2.linkageGroup) )
        {
	    if ( (s1.division.compareTo("CHEM") != 0 ) ||
                 (s1.course.compareTo("125") != 0) )
	     {  
		 //System.out.println("Link Problem " + s1.classNum + " " + s2.classNum);
   	         retVal++;
	     }
   	    else
	    {
		// for CHEM 125, if one of the sections is a lecture, we have to be within 
		// 100, else we have to be exact
		if ( s1.linkageGroup % 100 != 0 && s2.linkageGroup % 100 != 0 )
		    {
		    retVal++;
		    }
		else if ( Math.abs(s1.linkageGroup - s2.linkageGroup) > 100 )
		    {
		    retVal++;
		    }
	    }
	}         
        return retVal;
    }
}
