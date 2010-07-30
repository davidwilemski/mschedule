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

import java.awt.*;
import java.awt.event.*;
import java.util.*;

public class SchedulesDisplayPanel extends MISchedulePanel implements ActionListener
{
    public final static int TABLE_MODE = 1;
    public final static int LIST_MODE = 2;

    private TableData topBar;
    private TableData schedulesTable;
    private TableData scheduleMode;
    private TableData schedulesList;

    private ComponentFactory factory;
    private Vector skeds;
    private Dimension preferredSize;
    private int activeSked = 0;
    private int mode = TABLE_MODE;  

    private Button print;

    public SchedulesDisplayPanel(ComponentFactory pFactory)
    {
        factory = pFactory;
        preferredSize = new Dimension(0,0);
        print = new Button("Print This Schedule");
        print.addActionListener(this);
    }

    public Dimension getPreferredSize()
    {
        return preferredSize;
    }

    public void setData(Vector bestSchedules)
    {
	skeds = bestSchedules;
        activeSked = 0;
        reset();
    }

    private void reset()
    {
        removeAll();
        addTopBar();
        addScheduleMode();
        Rectangle r = scheduleMode.getBounds();
        print.setBounds(r.x + r.width + 10, r.y, 130, r.height);
        add(print);
        if (mode == TABLE_MODE)
            addSchedulesTable();
        else
            addSchedulesList();
        calcPreferredSize();
        repaint();
    }

    private void calcPreferredSize()
    {
	Rectangle bounds;
	if (mode == TABLE_MODE)
	    bounds = schedulesTable.getBounds();
	else
	    bounds = schedulesList.getBounds();
        preferredSize = new Dimension(bounds.x + bounds.width + 10, bounds.y + bounds.height + 10);
    }

    public void setActiveSchedule(int a)
    {
        if (activeSked == a) return;
        activeSked = a;
        reset();        
    }

    public int getActiveSchedule()
    {
	return activeSked;
    } 

    public void setMode(int pMode)
    {
        if (mode == pMode) return;
        mode = pMode;
        reset();
    }

    public void paint(Graphics screen)
    {
        System.out.println("score = " + ((Schedule) skeds.elementAt(activeSked)).getScore());
        screen.setColor(new Color(0,0,0));
        screen.drawString("Click the bar that has numbers on it to change", 300, 20);
        screen.drawString("which schedule to view.", 300, 35);

        screen.drawString("Click the bar that says Table/List to change", 300, 55);
        screen.drawString("how the schedules are viewed.", 300, 70);

        if (topBar != null) 
	{
	    topBar.repaint();
	}
        if (scheduleMode != null) 
	{
	    scheduleMode.repaint();
	}
        if (mode == TABLE_MODE)
	{
            if (schedulesTable != null) schedulesTable.repaint();
	}
        else if (schedulesList != null) schedulesList.repaint();
    }

    private void addTopBar()
    {
	long a = System.currentTimeMillis();
        topBar = factory.getTopBarTableForSchedules();
        topBar.format.numRows = 1;
        topBar.format.numCols = skeds.size();
        topBar.format.font = new Font("TimesRoman", Font.PLAIN, 14);
        topBar.format.fontMetrics = getFontMetrics(topBar.format.font);
        topBar.format.inset = 5;
        topBar.format.outsideBorder = true;
        topBar.format.insideBorder = true;
        topBar.init();

	long b = System.currentTimeMillis();
        for (int c=0; c<skeds.size(); c++)
	{
	    String data = " " + new Integer(c+1).toString() + " ";
            if (c==activeSked) 
	    {
                CellFormat cf = new CellFormat();
                cf.bgColor = new Color(0,0,51);
                cf.fgColor = new Color(255,255,255);
                topBar.setData( data, cf, 0, c );           
	    }
	    else
	    {
		topBar.setData( data, 0, c );
	    }
        }
        topBar.finalizeData();
        topBar.setBounds(10,10,topBar.bounds.width,topBar.bounds.height);
        add(topBar);
    }

    private void addScheduleMode()
    {
        scheduleMode = factory.getScheduleModeTable();
        scheduleMode.format.numRows = 1;
        scheduleMode.format.numCols = 2;
        scheduleMode.format.font = new Font("TimesRoman", Font.PLAIN, 14);
        scheduleMode.format.fontMetrics = getFontMetrics(scheduleMode.format.font);
        scheduleMode.format.inset = 5;
        scheduleMode.format.outsideBorder = true;
        scheduleMode.format.insideBorder = true;
        scheduleMode.init();


        CellFormat cf = new CellFormat();
        cf.bgColor = new Color(0,0,51);
        cf.fgColor = new Color(255,255,255);

	if ( mode == TABLE_MODE )
	{
	    scheduleMode.setData( "Table", cf, 0, 0 );           
	    scheduleMode.setData( "List", 0, 1 );           
	}
	else
	{
	    scheduleMode.setData( "Table", 0, 0 );           
	    scheduleMode.setData( "List", cf, 0, 1 );           
	}


        scheduleMode.finalizeData();
        scheduleMode.setBounds(10,topBar.bounds.height + 20,scheduleMode.bounds.width,scheduleMode.bounds.height);
        add(scheduleMode);
    }


    private void addSchedulesTable()
    {
        Schedule sked = (Schedule) skeds.elementAt(activeSked);
        int top = sked.getEarliestStartTime();
        int bottom = sked.getLatestEndTime();

        schedulesTable = factory.getScheduleDataTable();
        schedulesTable.format.numRows = bottom-top+1;
        schedulesTable.format.numCols = 6;
        schedulesTable.format.font = new Font("TimesRoman", Font.PLAIN, 14);
        schedulesTable.format.fontMetrics = getFontMetrics(schedulesTable.format.font);
        schedulesTable.format.inset = 5;
        schedulesTable.format.outsideBorder = true;
        schedulesTable.format.insideBorder = true;
        schedulesTable.init();

        schedulesTable.setData("MONDAY", 0, 1 );
	schedulesTable.setData("TUESDAY", 0, 2 );
	schedulesTable.setData("WEDNESDAY", 0, 3 );
	schedulesTable.setData("THURSDAY", 0, 4 );
	schedulesTable.setData("FRIDAY", 0, 5 );
        for (int i=top; i<bottom; i++)
	{
            schedulesTable.setData(TimeHolder.getTimeStringFromHH(i), i+1-top, 0);
	}

        int whichClass = 0;
        for (int i=0; i<sked.getNumSections(); i++)
	{
            Section sect = sked.getSectionAt(i);
            if ( i != 0 )
	    {
                Section lastSect = sked.getSectionAt(i-1);
                if (lastSect.division != sect.division || lastSect.course != sect.course) whichClass++;
	    }
            for (int j=0; j<sect.startTime.length; j++)
	    {
                int c = TimeHolder.getDayFromTime(sect.startTime[j]) + 1;
                int rstart = TimeHolder.getHalfHourFromTime(sect.startTime[j]) + 1 - top;
                int rend = TimeHolder.getHalfHourFromTime(sect.endTime[j]) + 1 - top;
                

                // We don't want to start on an overlapping section
                while (sked.overlap[rstart] > 1 && rstart < rend) rstart++;
                if (rstart == rend) continue;


                CellFormat cf = new CellFormat();
                cf.rowSpan = rend-rstart;
                switch (whichClass)
		{
                   case 0: cf.bgColor = new Color(255, 128, 160);
                           break; 		     
                   case 1: cf.bgColor = new Color(128, 128, 255);
                           break; 		     
                   case 2: cf.bgColor = new Color(192, 255, 0);
                           break; 		     
                   case 3: cf.bgColor = new Color(192, 128, 255);
                           break; 		     
                   case 4: cf.bgColor = new Color(255, 255, 160);
                           break; 		     
                   case 5: cf.bgColor = new Color(255, 128, 92);
                           break; 		     
                   case 6: cf.bgColor = new Color(192, 255, 192); 
                           break;
  		   case 7: cf.bgColor = new Color(128, 255, 255);
			   break;
                }
		String data = sect.division + " " + sect.course + "\n" +
		    sect.sectionTypeAbbrev + " " + sect.sectionNum + " (" + sect.classNum + ")\n";
		if (sect.openSeats > 0)
		    data += "# Seats: " + sect.openSeats;
		else data += "Closed! Waitlist: " + sect.waitlistDisplay;


                schedulesTable.setData(data, cf, rstart, c);
	    }
	}
        for (int i=0; i<sked.overlap.length; i++)
	{
            if (sked.overlap[i] > 1)
	    {
                int row = TimeHolder.getHalfHourFromTime(i) + 1 - top;
                int col = TimeHolder.getDayFromTime(i) + 1;
                CellFormat cf = new CellFormat();
                cf.bgColor = Color.black;
                cf.fgColor = Color.red;
                schedulesTable.setData("Overlap", cf, row, col);
	    }
        }

        schedulesTable.finalizeData();
        schedulesTable.setBounds(10,topBar.bounds.height+scheduleMode.bounds.height+30,schedulesTable.bounds.width,schedulesTable.bounds.height);
        add(schedulesTable);
        
    }


    private void addSchedulesList()
    {
        Schedule sked = (Schedule) skeds.elementAt(activeSked);

        schedulesList = factory.getScheduleListTable();
        schedulesList.format.numRows = sked.getNumSections()+1;
        schedulesList.format.numCols = 9;
        schedulesList.format.font = new Font("TimesRoman", Font.PLAIN, 14);
        schedulesList.format.fontMetrics = getFontMetrics(schedulesList.format.font);
        schedulesList.format.inset = 5;
        schedulesList.format.outsideBorder = true;
        schedulesList.format.insideBorder = true;
        schedulesList.init();

        schedulesList.setData("Course", 0, 0);
        schedulesList.setData("Class#", 0, 1);
        schedulesList.setData("Credits", 0, 2);
        schedulesList.setData("#Open", 0, 3);
        schedulesList.setData("Wait?", 0, 4);
        schedulesList.setData("Section", 0, 5);
        schedulesList.setData("Times", 0, 6);
        schedulesList.setData("Location", 0, 7);
        schedulesList.setData("Instructor", 0, 8);
              
        for (int r=0; r<sked.getNumSections(); r++)
	{
            Section s = sked.getSectionAt(r);              
            schedulesList.setData(s.division + " " + s.course, r+1, 0);
            schedulesList.setData(s.classNum, r+1, 1);
            schedulesList.setData(s.credits, r+1, 2);
            schedulesList.setData(new Integer(s.openSeats).toString(), r+1, 3);
            schedulesList.setData(s.waitlistDisplay, r+1, 4);
            schedulesList.setData(s.sectionTypeAbbrev + " " + s.sectionNum, r+1, 5);
            String tempTimeString = s.timeString[0];
	    for (int i=1; i<s.timeString.length; i++)
	    {
		tempTimeString += "\n" + s.timeString[i];
	    }
            schedulesList.setData(tempTimeString, r+1, 6);
            String tempLocation = s.location[0];
            for (int i=1; i<s.location.length; i++)
	    {
                tempLocation += "\n" + s.location[i];
	    }
            schedulesList.setData(tempLocation, r+1, 7);
            schedulesList.setData(s.instructor, r+1, 8);
	}       

        schedulesList.finalizeData();
        schedulesList.setBounds(10,topBar.bounds.height+scheduleMode.bounds.height+30,schedulesList.bounds.width,schedulesList.bounds.height);
        add(schedulesList);
        
    }

    public void actionPerformed(ActionEvent e)
    {
        if (e.getSource() == print)
	{
            PrintSchedules p = factory.getPrintSchedules();
            p.show();
	}
    }
 
}
