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

public class ScheduleThread extends MIScheduleThread
{
    private Vector masterList;
    private Vector sectionPackage;
    private Vector bestSchedules;
    private int maxSchedules = 10;
    private int nodesExamined = 0;  
    //private int maxNodes = 1000000;
    private int maxNodes = 500000;
    private int timeValues[];

    public ScheduleThread(Vector pSectionPackage, int[] pTimeValues)
    {
        sectionPackage = pSectionPackage;
        timeValues = pTimeValues;
    }

    public void run()
    {
        System.out.println("Starting scheduling");

        masterList = new Vector(20,20);

        for (int i=0; i<sectionPackage.size(); i++)
	{
            SectionList sl = (SectionList) sectionPackage.elementAt(i);
            
            WorkingSectionList wsl = new WorkingSectionList(sl, timeValues);

            wsl.addToMasterList(masterList);
        }

        /*for (int i=0; i<masterList.size(); i++)
	    {
		Vector v = (Vector) masterList.elementAt(i);
		System.out.println("********************");
                for (int j=0; j<v.size(); j++)
		    {
			Section s = (Section) v.elementAt(j);
                        if (s == null) System.out.println("S is NULL");
			System.out.println(s.score);
		    }
	    }
	*/
	
	
 
        Schedule s = new Schedule(masterList.size());
        bestSchedules = new Vector(maxSchedules, maxSchedules);
        System.out.println("Calling main routine");
        buildSchedules(s,0);
        System.out.println("Done building schedules: " + nodesExamined);
        fireMIScheduleEvent(new MIScheduleEvent(this, bestSchedules, MIScheduleEvent.FINISHED_BUILDING_SCHEDULES));
    }

    private void buildSchedules(Schedule sked, int depth)
    {
        nodesExamined++;

	if (nodesExamined % 5000 == 0) 
	    fireMIScheduleEvent( new MIScheduleEvent(this, new Integer(nodesExamined), MIScheduleEvent.SCHEDULE_STATUS) );

        if (nodesExamined >= maxNodes) return;

        //System.out.println("Building schedules at depth " + depth);

        if ( bestSchedules.size() == maxSchedules )
	{
            if (sked.getScore() <= ((Schedule)bestSchedules.elementAt(maxSchedules-1)).getScore())
	    {
		//System.out.println("score has fallen below minimum");
                return;
	    }
        }
        if ( depth == masterList.size() )
	{
            //System.out.println("at max depth, adding schedule");

            if ( bestSchedules.size() == maxSchedules)
	    {
		bestSchedules.removeElementAt(maxSchedules-1);
	    }

	    int pos = 0;
            for (int i=bestSchedules.size()-1; i>=0; i--)
	    {
                if (sked.getScore() <= ((Schedule)bestSchedules.elementAt(i)).getScore())
		{
                    pos = i+1;
                    break;
		}
	    } 
            //System.out.println("adding schedule at position " + pos);
            bestSchedules.insertElementAt(new Schedule(sked), pos);



            return;
        }
        Vector currentSections = (Vector) masterList.elementAt(depth);
        for ( int i=0; i<currentSections.size(); i++)
	{
            sked.addSection((Section)currentSections.elementAt(i));
            buildSchedules(sked,depth+1);
            sked.removeLastSection();
	}
    }
}
