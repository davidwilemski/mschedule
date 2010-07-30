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

public class DisplaySectionsPanel extends MISchedulePanel implements ActionListener, ItemListener
{
    private Vector sectionPackage;
    private int activeSection;
    private TableData topBar;
    private TableData sectionData;
    private Dimension preferredSize;
    private ComponentFactory factory;
    private Button considerAll;
    private Button considerNone;
    private Checkbox consider[];
    private Button print;

    public DisplaySectionsPanel(ComponentFactory pFactory, Vector pSectionPackage)
    {
        factory = pFactory;
        sectionPackage = pSectionPackage;   
        preferredSize = new Dimension(0,0);
        activeSection = 0;
        considerAll = new Button("Consider All");
        considerAll.addActionListener(this);
        considerNone = new Button("Consider None");
        considerNone.addActionListener(this);
        print = new Button("Print This");
        print.addActionListener(this);
    }

    public void reset()
    {
        removeAll();   
        addTopBar();
        SectionList sl = (SectionList) sectionPackage.elementAt(activeSection);
        consider = new Checkbox[sl.getNumSections()];
        considerAll.setBounds(200,15+topBar.bounds.height,100,20);
        considerNone.setBounds(320,15+topBar.bounds.height,100,20);
        print.setBounds(440,15+topBar.bounds.height,100,20);
        add(considerAll);
        add(considerNone);
        add(print);
        addSectionData();
        calculatePreferredSize();
	repaint();
    }

    public void paint(Graphics screen)
    {
        topBar.repaint();
        SectionList sl = (SectionList) sectionPackage.elementAt(activeSection);
        screen.setFont(new Font("TimesRoman", Font.PLAIN, 16));
        screen.drawString("Sections for " + sl.getDivision() + " " + sl.getCourse(),10,30+topBar.bounds.height);
        sectionData.repaint();
    }

    public Dimension getPreferredSize()
    {
        return preferredSize;
    }

    public void setActiveSection(int pActiveSection)
    {
        if (activeSection != pActiveSection)
	{
            activeSection = pActiveSection;
            reset();
        }
    }

    public int getActiveSection()
    {
	return activeSection;
    }

    public void actionPerformed(ActionEvent e)
    {
        SectionList sl = (SectionList) sectionPackage.elementAt(activeSection);
        if (e.getSource() == considerAll)
        {
            for (int i=0; i<consider.length; i++)
            {
                sl.getSectionAt(i).consider = true;
                consider[i].setState(true);
            }
        }
        if (e.getSource() == considerNone)
        {
            for (int i=0; i<consider.length; i++)
            {
                sl.getSectionAt(i).consider = false;
                consider[i].setState(false);
            }
        }
        if (e.getSource() == print)
	{
            PrintSections p = factory.getPrintSections();
            p.show();
	}
    }

    public void itemStateChanged(ItemEvent e)
    {
        for (int i=0; i<consider.length; i++)
	    if (e.getSource() == consider[i])
	    {
		SectionList sl = (SectionList) sectionPackage.elementAt(activeSection);
		sl.getSectionAt(i).consider = consider[i].getState();
	    }
    }

    private void calculatePreferredSize()
    {
	Rectangle bounds = sectionData.getBounds();
        preferredSize = new Dimension(
				      bounds.x + bounds.width + 10,
				      bounds.y + bounds.height + 10
				      );
    }

    private void addTopBar()
    {
        topBar = factory.getTopBarTable();
        topBar.format.numRows = 1;
        topBar.format.numCols = sectionPackage.size();
        topBar.format.font = new Font("TimesRoman", Font.PLAIN, 14);
        topBar.format.fontMetrics = getFontMetrics(topBar.format.font);
        topBar.format.inset = 5;
        topBar.format.outsideBorder = true;
        topBar.format.insideBorder = true;
        topBar.init();

        for (int c=0; c<sectionPackage.size(); c++)
	{
            SectionList sl = (SectionList) sectionPackage.elementAt(c);
            if (c==activeSection) 
	    {
                CellFormat cf = new CellFormat();
                cf.bgColor = new Color(0,0,51);
                cf.fgColor = new Color(255,255,255);
                topBar.setData( sl.getDivision() + " " + sl.getCourse(), cf, 0, c );           
            }
            else
            {
                topBar.setData( sl.getDivision() + " " + sl.getCourse(), 0, c );           
	    }
        }
        topBar.finalizeData();
        topBar.setBounds(10,10,topBar.bounds.width,topBar.bounds.height);
        add(topBar);
    }

    private void addSectionData()
    {
        SectionList sl = (SectionList) sectionPackage.elementAt(activeSection);
        sectionData = factory.getSectionDataTable();
        sectionData.format.font = new Font("TimesRoman", Font.PLAIN, 12);
	sectionData.format.fontMetrics = getFontMetrics(sectionData.format.font);
	sectionData.format.inset = 5;
	sectionData.format.outsideBorder = true;
        sectionData.format.insideBorder = true;
        sectionData.format.numRows = sl.getNumSections() + 1;
        sectionData.format.numCols = 9;
        sectionData.init();

        sectionData.setData("Class#", 0, 1);
        sectionData.setData("Credits", 0, 2);
        sectionData.setData("#Seats", 0, 3);
        sectionData.setData("Wait?", 0, 4);
        sectionData.setData("Section", 0, 5);
        sectionData.setData("Times", 0, 6);
        sectionData.setData("Location", 0, 7);
        sectionData.setData("Instructor", 0, 8);
              
        for (int r=0; r<sl.getNumSections(); r++)
	{
            Section s = sl.getSectionAt(r);              
            consider[r] = new Checkbox();
            consider[r].addItemListener(this);
            consider[r].setState(s.consider);
            consider[r].setSize(15,15);
            sectionData.setComponent(consider[r], r+1, 0);
            sectionData.setData(s.classNum, r+1, 1);
            sectionData.setData(s.credits, r+1, 2);
            sectionData.setData(new Integer(s.openSeats).toString(), r+1, 3);
            sectionData.setData(s.waitlistDisplay, r+1, 4);
            sectionData.setData(s.sectionTypeAbbrev + " " + s.sectionNum, r+1, 5);
            String tempTimeString = s.timeString[0];
	    for (int i=1; i<s.timeString.length; i++)
	    {
		tempTimeString += "\n" + s.timeString[i];
	    }
            sectionData.setData(tempTimeString, r+1, 6);
            String tempLocation = s.location[0];
            for (int i=1; i<s.location.length; i++)
	    {
                tempLocation += "\n" + s.location[i];
	    }
            sectionData.setData(tempLocation, r+1, 7);
            sectionData.setData(s.instructor, r+1, 8);
	}       
        sectionData.finalizeData();   
        sectionData.setBounds(10,topBar.bounds.height + 45,sectionData.bounds.width,sectionData.bounds.height);
        add(sectionData);

    }
}
