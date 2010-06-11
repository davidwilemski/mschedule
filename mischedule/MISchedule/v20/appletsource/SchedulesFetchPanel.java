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
import java.util.*;
import java.awt.event.*;

/* 
    This is the status panel that will be displayed while the 
    sections are being retrieved from the server
*/

public class SchedulesFetchPanel extends MISchedulePanel implements ActionListener
{
    private Vector slotsToRetrieve;
    private boolean errors=false;
    private Button ok;
    private int loaded[];
    private Font headerFont, normalFont, errorFont;
    private int nodesExamined=0;
 
    private final static int LOAD_PENDING = 0;
    private final static int LOAD_ERROR = 1;
    private final static int LOAD_SUCCESS = 2;

    public SchedulesFetchPanel()
    {
	setLayout(null);
        slotsToRetrieve = new Vector(10,5);
        headerFont = new Font("TimesRoman", Font.PLAIN, 20);
        normalFont = new Font("TimesRoman", Font.PLAIN, 12);
        errorFont = new Font("TimesRoman", Font.ITALIC, 12);
    }

    public void setSlotsToRetrieve(Vector toRetrieve)
    {
        removeAll();
	nodesExamined = 0;
	slotsToRetrieve = toRetrieve;
	if (slotsToRetrieve.size() == 0)
	{
	    errors = true;
	    allRetrieved();
	}
	else
	{
	    loaded = new int[slotsToRetrieve.size()];
	    errors = false;
	}
    }

    public void setRetrieved(String division, String course, boolean valid)
    {           
        for (int i=0; i<slotsToRetrieve.size(); i++)
	{
            Slot s = (Slot) slotsToRetrieve.elementAt(i);
            if ( s.division.compareTo(division) == 0 && s.course.compareTo(course) == 0 )
	    {
		if (valid) 
		    loaded[i] = LOAD_SUCCESS; 
		else
	        {
		    loaded[i] = LOAD_ERROR;
		    errors = true;
		}
                break;
            }
        }
        repaint();
    }

    public void allRetrieved()
    {
        if (errors) 
        {     
	    if (ok == null) 
	    {
		ok = new Button("OK");
		ok.setBounds(300,32,50,20);
                ok.addActionListener(this);
	    }     
            add(ok);
	}
	else fireMIScheduleEvent( new MIScheduleEvent(this, null, MIScheduleEvent.DONE_FETCHING_SECTIONS) );
    }

    public void setNodesExamined(int n)
    {
	nodesExamined = n;
	repaint();
    }
 
    public void paint(Graphics screen)
    {
	super.paint(screen);
        screen.setColor(Color.black);
        screen.setFont(headerFont);
        screen.drawString("Retrieving Sections (This may take a few moments)",20,30);
        if (errors)
	{
            screen.setColor(Color.red);
            screen.setFont(errorFont);
	    if ( slotsToRetrieve.size() == 0 )
		screen.drawString("Error: There are no courses selected.",20,50);
	    else
		screen.drawString("Warning: Errors Occurred",20,50);
        }
    
        Slot slot;
	int rowStart = 100;
        int rowSize = 15;
        for (int i=0; i<slotsToRetrieve.size(); i++)
	{
            slot = (Slot) slotsToRetrieve.elementAt(i);
            screen.setColor(Color.black);
            screen.setFont(normalFont);
            screen.drawString(slot.division + " " + slot.course + "...",100,rowStart);
	    rowStart += rowSize;
	    if (loaded[i] == LOAD_SUCCESS)
	    { 
		screen.drawString("Loaded Successfully.",120,rowStart);
	    }
	    else if (loaded[i] == LOAD_ERROR)
	    {
		screen.setColor(Color.red);
		screen.drawString("Error: invalid course or course contains no sections.",120,rowStart);
	    }
	    rowStart += rowSize;
	}

	if (nodesExamined > 0)
	{
	    screen.setColor(Color.black);
	    screen.setFont(normalFont);
	    rowStart += rowSize;
	    screen.drawString("Building Schedules - Nodes Examined: " + nodesExamined,100,rowStart);
	}
	
    }

    public void actionPerformed(ActionEvent e)
    {
        fireMIScheduleEvent( new MIScheduleEvent(this, null, MIScheduleEvent.DONE_FETCHING_SECTIONS) );
    }
}
