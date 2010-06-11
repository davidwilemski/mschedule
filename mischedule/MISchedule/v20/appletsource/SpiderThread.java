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

public class SpiderThread extends MIScheduleThread implements MIScheduleListener
{
    private Spider spider;
    private MIScheduleReader reader;
    private DivisionList divList;
    private String startDiv;
    private String endDiv;
    private boolean getSections;
    private int numThreads;

    private Stack divisions;
    private Stack courses;
    private Stack courseDivisions;

    public SpiderThread(
                          Spider pSpider,
                          MIScheduleReader pReader, 
                          DivisionList pDivList,
                          String pStartDiv, 
                          String pEndDiv, 
                          boolean pGetSections, 
                          int pNumThreads)
    {
    
        spider = pSpider; 
        reader = pReader;
        divList = pDivList;
        startDiv = pStartDiv;
        endDiv = pEndDiv;
        getSections = pGetSections;
        numThreads = pNumThreads;
    }

    public void run()
    {
        divisions = new Stack();
        spider.sStatus = "Working...";

        if (getSections)
	{
            courses = new Stack();   
            courseDivisions = new Stack();          
        }

        int n = divList.getNumDivisions();
        boolean adding = false;
        for (int i=0; i<n; i++)
	{
            Division d = divList.getDivisionAt(i);
            if (d.abbrev == startDiv) adding = true;
            if (adding)
	        divisions.push(d.abbrev);
            if (d.abbrev == endDiv) break;
        }

        for (int i=0; i<numThreads; i++)
	{
            getNextData();
        }

    }

    public void getNextData()
    {
        if (getSections)
	{
	    if (!courses.empty())
	    {
                String num = (String) courses.pop();
                String div = (String) courseDivisions.pop();           
                SectionList s = new SectionList(reader, div, num);
                spider.nMade++;
                spider.repaint();       
		s.addMIScheduleListener(this);
                s.start(); 
                return;
            }
       }
 
       if (!divisions.empty())
       {
           String div = (String) divisions.pop();
           CourseList c = new CourseList(reader, div);
           spider.nMade++;
           spider.repaint();       
           c.addMIScheduleListener(this);
           c.start();
       }     
       else
       {
           if (spider.nMade == spider.nReceived)
	   {
	       spider.sStatus = "Finished";
               spider.repaint();
           }
       }
    }


    public void requestFinished( MIScheduleEvent e )
    {

	switch ( e.getType() )
	{
        case MIScheduleEvent.GOT_COURSE_LIST:
            CourseList c = (CourseList) e.getData();
            if (getSections)
	    {
                int n = c.getNumCourses();
                if (n < 0) 
                {
                    spider.nErrors++;
                    spider.repaint();
                }
 
                for (int i=0; i<n; i++)
	        {
                    courses.push(c.getCourseAt(i).number);
                    courseDivisions.push(c.getDivision());
                }
            }
            break;
        case MIScheduleEvent.GOT_SECTION_LIST:
            SectionList s = (SectionList) e.getData();
            int n = s.getNumSections();    
            if (n < 0) 
            {
                spider.nErrors++;
                spider.repaint();
            }
            break;
	}
        spider.nReceived++;
        spider.repaint();       

        getNextData();
    }

}
